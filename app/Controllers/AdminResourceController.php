<?php

declare(strict_types=1);

namespace App\Controllers;

/*
|--------------------------------------------------------------------------
| Admin Resources Controller
|--------------------------------------------------------------------------
| This controller handles administrative functionalities for resources.
*/

use Exception;
use App\Models\User;
use App\Models\Resource;
use Plugs\View\ErrorMessage;
use App\Models\ResourceDownload;
use Plugs\Utils\FlashMessage;
use Plugs\Paginator\Paginator;
use Plugs\Upload\FileUploader;
use Plugs\Upload\UploadedFile;
use Plugs\Base\Controller\Controller;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AdminResourceController extends Controller
{
    private $uploader;

    public function onConstruct()
    {
        $this->uploader = new FileUploader();
        $this->uploader->usePublicFolder("uploads/resources");
        $this->uploader->imagesOnly(5 * 1024 * 1024);
        $this->uploader->disableSecurityFiles();
    }

    /**
     * Display resource management page
     */
    public function manage(Request $request)
    {
        try {
            $queryParams = $request->getQueryParams();
            $perPage = 10;
            $currentPage = (int)($queryParams['page'] ?? 1);

            // Build query
            $query = Resource::with(['user']);

            // Search functionality
            $search = $queryParams['search'] ?? null;
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'LIKE', "%{$search}%")
                        ->orWhere('description', 'LIKE', "%{$search}%");
                });
            }

            // Filter by status
            $status = $queryParams['status'] ?? null;
            if ($status && in_array($status, ['published', 'draft', 'pending'])) {
                $query->where('status', $status);
            }

            // Filter by file type
            $fileType = $queryParams['file_type'] ?? null;
            if ($fileType && in_array($fileType, ['image', 'video', 'audio', 'document', 'other'])) {
                $query->where('file_type', $fileType);
            }

            // Apply ordering
            $query = $query->orderBy('created_at', 'DESC')
                ->orderBy('published_at', 'DESC');

            // Create paginator and get results
            $paginator = Paginator::fromQuery($query, $perPage, $currentPage);
            $resources = $paginator->items();

            return $this->view('admin.resources.manage', [
                'resources' => $resources,
                'paginator' => $paginator,
                'search' => $search,
                'status' => $status,
                'file_type' => $fileType,
                'page_title' => 'Resources Management',
                'page_subtitle' => 'Upload and manage media, files, and documents for your users.'
            ]);
        } catch (Exception $e) {
            FlashMessage::error('Failed to load resources: ' . $e->getMessage());
            return $this->view('admin.resources.manage', [
                'resources' => [],
                'paginator' => null,
                'page_title' => 'Resources Management'
            ]);
        }
    }

    /**
     * Display create resource form
     */
    public function create(Request $request)
    {
        try {
            return $this->view('admin.resources.create', [
                'page_title' => 'Create New Resource',
                'page_subtitle' => 'Add New Resource for user Free or Paid.',
            ]);
        } catch (Exception $e) {
            FlashMessage::error('Failed to load resource creation page: ' . $e->getMessage());
            return $this->redirect('/admin/resources');
        }
    }

    /**
     * Store new resource
     */
    public function store(Request $request)
    {
        try {
            $this->currentRequest = $request;

            $data = $request->getParsedBody();
            $action = $data['action'] ?? 'pending';
            $isPublish = $action === 'published';

            // Validate resource data
            $errors = $this->validateResourceData($request);

            // If validation fails, redirect back with errors
            if ($errors->any()) {
                return $this->back($errors);
            }

            // Get current user ID
            $userId = $this->getCurrentUserId($request);
            if (!$userId) {
                FlashMessage::error('You must be logged in to create a resource.');
                return $this->redirect('/admin/resources/create');
            }

            // Handle file upload
            $fileName = null;
            $filePath = null;
            $fileSize = 0;
            $fileExtension = null;
            $mimeType = null;

            if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
                $file = UploadedFile::createFromFilesArray($_FILES['file']);

                // Configure uploader based on file type
                $fileType = $data['file_type'] ?? 'other';
                $this->configureUploaderForFileType($fileType);

                try {
                    $uploadResult = $this->uploader->upload($file, null, (string)$userId);

                    // Extract file information
                    $fileName = basename($uploadResult['path']);
                    $filePath = $uploadResult['url'];
                    $fileSize = $file->getSize();
                    $fileExtension = $file->getClientExtension();
                    $mimeType = $file->getMimeType();
                } catch (Exception $e) {
                    FlashMessage::error("File upload failed: " . $e->getMessage());
                    return $this->redirect("/admin/resources/create");
                }
            } else {
                FlashMessage::error('Please select a file to upload.');
                return $this->redirect("/admin/resources/create");
            }

            // Determine if resource is free based on price
            $price = (float)$data['price'];
            $isFree = $price <= 0;

            // Determine published_at date
            $publishedAt = null;
            if ($isPublish) {
                $publishedAt = date('Y-m-d H:i:s');
            }

            // Prepare resource data
            $resourceData = [
                'title' => trim($data['title']),
                'description' => trim($data['description'] ?? ''),
                'file_name' => $fileName,
                'file_path' => $filePath,
                'file_size' => $fileSize,
                'file_type' => $data['file_type'],
                'file_extension' => $fileExtension,
                'mime_type' => $mimeType,
                'price' => $price,
                'is_free' => $isFree,
                'status' => $isPublish ? 'published' : 'pending',
                'created_by' => $userId,
                'download_count' => 0,
                'paid_download_count' => 0,
                'revenue_generated' => 0.00,
                'published_at' => $publishedAt,
            ];

            // Handle featured image if provided
            if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK) {
                $featuredImageFile = UploadedFile::createFromFilesArray($_FILES['featured_image']);
                $this->uploader->imagesOnly(); // Only allow images for featured image

                try {
                    $uploadResult = $this->uploader->upload($featuredImageFile, null, (string)$userId . '_featured');
                    $resourceData['featured_image'] = $uploadResult['url'];
                } catch (Exception $e) {
                    // Featured image upload failed, but don't fail the whole process
                    FlashMessage::warning("Featured image upload failed: " . $e->getMessage());
                }
            }

            // Create the resource
            $resource = Resource::create($resourceData);

            if ($resource) {
                $textStatus = $isPublish ? "published" : "saved as draft";
                FlashMessage::success("Resource {$textStatus} successfully!");

                // Log resource creation
                error_log("Resource created: ID={$resource->id}, Title={$resource->title}, Type={$resource->file_type}");

                // Redirect based on action
                if ($action === 'pending') {
                    return $this->redirect("/admin/resources/edit/{$resource->id}");
                }

                return $this->redirect('/admin/resources');
            }

            FlashMessage::error('Failed to create resource. Please try again.');
            return $this->redirect('/admin/resources/create');
        } catch (Exception $e) {
            FlashMessage::error('Error creating resource: ' . $e->getMessage());
            error_log("Resource creation error: " . $e->getMessage());
            return $this->redirect('/admin/resources/create');
        }
    }

    /**
     * Display edit resource form
     */
    public function edit(Request $request, $id): Response
    {
        try {
            $resource = Resource::find($id);

            if (!$resource) {
                FlashMessage::error('Resource not found');
                return $this->redirect('/admin/resources');
            }

            return $this->view('admin.resources.edit', [
                'resource' => $resource,
                'page_title' => 'Edit Resource',
                'page_subtitle' => 'Update resource information and settings.'
            ]);
        } catch (Exception $e) {
            FlashMessage::error('Error loading resource: ' . $e->getMessage());
            return $this->redirect('/admin/resources');
        }
    }

    /**
     * Update existing resource
     */
    public function update(Request $request, $id): Response
    {
        try {
            $this->currentRequest = $request;

            // Find the resource
            $resource = Resource::find($id);

            if (!$resource) {
                FlashMessage::error('Resource not found');
                return $this->redirect('/admin/resources');
            }

            $data = $request->getParsedBody();
            $action = $data['action'] ?? 'pending';
            $isPublish = $action === 'published';

            // Validate resource data
            $errors = $this->validateResourceData($request, $id);

            if ($errors->any()) {
                return $this->back($errors);
            }

            // Get current user ID
            $userId = $this->getCurrentUserId($request);
            if (!$userId) {
                FlashMessage::error('You must be logged in to update a resource.');
                return $this->redirect("/admin/resources/edit/{$id}");
            }

            // Handle file upload if new file is provided
            $fileName = $resource->file_name;
            $filePath = $resource->file_path;
            $fileSize = $resource->file_size;
            $fileExtension = $resource->file_extension;
            $mimeType = $resource->mime_type;

            if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
                // Delete old file
                if ($filePath) {
                    try {
                        $relativePath = $this->extractRelativeImagePath($filePath);
                        if ($relativePath) {
                            $this->uploader->delete($relativePath);
                        }
                    } catch (Exception $e) {
                        error_log("Failed to delete old file: " . $e->getMessage());
                    }
                }

                // Upload new file
                $file = UploadedFile::createFromFilesArray($_FILES['file']);
                $fileType = $data['file_type'] ?? 'other';
                $this->configureUploaderForFileType($fileType);

                try {
                    $uploadResult = $this->uploader->upload($file, null, (string)$userId);
                    $fileName = basename($uploadResult['path']);
                    $filePath = $uploadResult['url'];
                    $fileSize = $file->getSize();
                    $fileExtension = $file->getClientExtension();
                    $mimeType = $file->getMimeType();
                } catch (Exception $e) {
                    FlashMessage::error("File upload failed: " . $e->getMessage());
                    return $this->redirect("/admin/resources/edit/{$id}");
                }
            }

            // Handle featured image
            $featuredImage = $resource->featured_image;

            // Check if user wants to remove the image
            if (isset($data['remove_featured_image']) && $data['remove_featured_image'] == '1') {
                if ($featuredImage) {
                    try {
                        $relativePath = $this->extractRelativeImagePath($featuredImage);
                        if ($relativePath) {
                            $this->uploader->delete($relativePath);
                        }
                    } catch (Exception $e) {
                        error_log("Failed to delete featured image: " . $e->getMessage());
                    }
                }
                $featuredImage = null;
            }
            // Check if new image is uploaded
            elseif (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK) {
                // Delete old image if exists
                if ($featuredImage) {
                    try {
                        $relativePath = $this->extractRelativeImagePath($featuredImage);
                        if ($relativePath) {
                            $this->uploader->delete($relativePath);
                        }
                    } catch (Exception $e) {
                        error_log("Failed to delete old featured image: " . $e->getMessage());
                    }
                }

                // Upload new image
                $file = UploadedFile::createFromFilesArray($_FILES['featured_image']);
                $this->uploader->imagesOnly();
                try {
                    $uploadResult = $this->uploader->upload($file, null, (string)$userId . '_featured');
                    $featuredImage = $uploadResult['url'];
                } catch (Exception $e) {
                    FlashMessage::error("Featured image upload failed: " . $e->getMessage());
                }
            }

            // Determine if resource is free based on price
            $price = (float)$data['price'];
            $isFree = $price <= 0;

            // Determine published_at date
            $publishedAt = $resource->published_at;
            if ($publishedAt instanceof \DateTime) {
                $publishedAt = $publishedAt->format('Y-m-d H:i:s');
            }

            // If resource is being published for the first time
            if ($isPublish && $resource->status !== 'published' && !$publishedAt) {
                $publishedAt = date('Y-m-d H:i:s');
            }

            // Prepare update data
            $updateData = [
                'title' => trim($data['title']),
                'description' => trim($data['description'] ?? ''),
                'file_name' => $fileName,
                'file_path' => $filePath,
                'file_size' => $fileSize,
                'file_type' => $data['file_type'],
                'file_extension' => $fileExtension,
                'mime_type' => $mimeType,
                'price' => $price,
                'is_free' => $isFree,
                'status' => $isPublish ? 'published' : $data['status'] ?? 'pending',
                'featured_image' => $featuredImage,
                'published_at' => $publishedAt,
            ];

            // Update the resource
            $updated = $resource->update($updateData);

            if ($updated) {
                $textStatus = $isPublish ? "published" : "updated";
                FlashMessage::success("Resource {$textStatus} successfully!");

                // Redirect based on action
                if ($action === 'pending') {
                    return $this->redirect("/admin/resources/edit/{$resource->id}");
                }

                return $this->redirect('/admin/resources');
            } else {
                FlashMessage::error('Failed to update resource. Please try again.');
                return $this->redirect("/admin/resources/edit/{$id}");
            }
        } catch (Exception $e) {
            FlashMessage::error('Error updating resource: ' . $e->getMessage());
            return $this->redirect("/admin/resources/edit/{$id}");
        }
    }

    /**
     * Display resource details
     */
    public function show(Request $request, $id): Response
    {
        try {
            $resource = Resource::with(['user'])->find($id);

            if (!$resource) {
                FlashMessage::error('Resource not found');
                return $this->redirect('/admin/resources');
            }

            // Get download statistics
            $downloadStats = ResourceDownload::where('resource_id', $id)
                ->selectRaw('download_type, COUNT(*) as count, SUM(amount_paid) as total_revenue')
                ->groupBy('download_type')
                ->get();

            $totalDownloads = ResourceDownload::where('resource_id', $id)->count();
            $paidDownloads = ResourceDownload::where('resource_id', $id)
                ->where('download_type', 'paid')
                ->count();
            $freeDownloads = ResourceDownload::where('resource_id', $id)
                ->where('download_type', 'free')
                ->count();
            $totalRevenue = ResourceDownload::where('resource_id', $id)
                ->where('download_type', 'paid')
                ->sum('amount_paid');

            // Get recent downloads
            $recentDownloads = ResourceDownload::with(['user'])
                ->where('resource_id', $id)
                ->orderBy('downloaded_at', 'DESC')
                ->limit(10)
                ->get();

            return $this->view('admin.resources.show', [
                'resource' => $resource,
                'downloadStats' => $downloadStats,
                'totalDownloads' => $totalDownloads,
                'paidDownloads' => $paidDownloads,
                'freeDownloads' => $freeDownloads,
                'totalRevenue' => $totalRevenue,
                'recentDownloads' => $recentDownloads,
                'page_title' => 'Resource Details',
                'page_subtitle' => 'View resource information and statistics.'
            ]);
        } catch (Exception $e) {
            FlashMessage::error('Error loading resource: ' . $e->getMessage());
            return $this->redirect('/admin/resources');
        }
    }

    /**
     * Delete resource
     */
    public function destroy(Request $request, $id): Response
    {
        try {
            $resource = Resource::find($id);

            if (!$resource) {
                FlashMessage::error('Resource not found');
                return $this->redirect('/admin/resources');
            }

            // Delete files
            if ($resource->file_path) {
                try {
                    $relativePath = $this->extractRelativeImagePath($resource->file_path);
                    if ($relativePath) {
                        $this->uploader->delete($relativePath);
                    }
                } catch (Exception $e) {
                    error_log("Failed to delete resource file: " . $e->getMessage());
                }
            }

            if ($resource->featured_image) {
                try {
                    $relativePath = $this->extractRelativeImagePath($resource->featured_image);
                    if ($relativePath) {
                        $this->uploader->delete($relativePath);
                    }
                } catch (Exception $e) {
                    error_log("Failed to delete featured image: " . $e->getMessage());
                }
            }

            // Delete resource downloads
            ResourceDownload::where('resource_id', $id)->delete();

            // Delete the resource
            $deleted = $resource->delete();

            if ($deleted) {
                FlashMessage::success('Resource deleted successfully!');
            } else {
                FlashMessage::error('Failed to delete resource.');
            }

            return $this->redirect('/admin/resources');
        } catch (Exception $e) {
            FlashMessage::error('Error deleting resource: ' . $e->getMessage());
            return $this->redirect('/admin/resources');
        }
    }

    /**
     * Bulk actions
     */
    public function bulk(Request $request): Response
    {
        try {
            $data = $request->getParsedBody();
            $action = $data['action'] ?? null;
            $ids = $data['ids'] ?? [];

            if (empty($ids)) {
                FlashMessage::error('No resources selected.');
                return $this->redirect('/admin/resources');
            }

            $successCount = 0;
            $errorCount = 0;

            switch ($action) {
                case 'publish':
                    foreach ($ids as $id) {
                        $resource = Resource::find($id);
                        if ($resource) {
                            $resource->update([
                                'status' => 'published',
                                'published_at' => date('Y-m-d H:i:s')
                            ]);
                            $successCount++;
                        } else {
                            $errorCount++;
                        }
                    }
                    FlashMessage::success("Published {$successCount} resource(s).");
                    break;

                case 'draft':
                    foreach ($ids as $id) {
                        $resource = Resource::find($id);
                        if ($resource) {
                            $resource->update(['status' => 'draft']);
                            $successCount++;
                        } else {
                            $errorCount++;
                        }
                    }
                    FlashMessage::success("Moved {$successCount} resource(s) to draft.");
                    break;

                case 'delete':
                    foreach ($ids as $id) {
                        $resource = Resource::find($id);
                        if ($resource) {
                            // Delete files
                            if ($resource->file_path) {
                                try {
                                    $relativePath = $this->extractRelativeImagePath($resource->file_path);
                                    if ($relativePath) {
                                        $this->uploader->delete($relativePath);
                                    }
                                } catch (Exception $e) {
                                    error_log("Failed to delete resource file: " . $e->getMessage());
                                }
                            }

                            if ($resource->featured_image) {
                                try {
                                    $relativePath = $this->extractRelativeImagePath($resource->featured_image);
                                    if ($relativePath) {
                                        $this->uploader->delete($relativePath);
                                    }
                                } catch (Exception $e) {
                                    error_log("Failed to delete featured image: " . $e->getMessage());
                                }
                            }

                            // Delete resource downloads
                            ResourceDownload::where('resource_id', $id)->delete();

                            // Delete resource
                            if ($resource->delete()) {
                                $successCount++;
                            } else {
                                $errorCount++;
                            }
                        } else {
                            $errorCount++;
                        }
                    }
                    FlashMessage::success("Deleted {$successCount} resource(s).");
                    break;

                default:
                    FlashMessage::error('Invalid action.');
                    return $this->redirect('/admin/resources');
            }

            if ($errorCount > 0) {
                FlashMessage::warning("{$errorCount} resource(s) could not be processed.");
            }

            return $this->redirect('/admin/resources');
        } catch (Exception $e) {
            FlashMessage::error('Error performing bulk action: ' . $e->getMessage());
            return $this->redirect('/admin/resources');
        }
    }

    /**
     * Toggle resource status
     */
    public function toggleStatus(Request $request, $id): Response
    {
        try {
            $resource = Resource::find($id);

            if (!$resource) {
                FlashMessage::error('Resource not found');
                return $this->redirect('/admin/resources');
            }

            $newStatus = $resource->status === 'published' ? 'draft' : 'published';
            $publishedAt = $newStatus === 'published' ? date('Y-m-d H:i:s') : null;

            $resource->update([
                'status' => $newStatus,
                'published_at' => $publishedAt
            ]);

            FlashMessage::success("Resource status updated to {$newStatus}.");
            return $this->redirect('/admin/resources');
        } catch (Exception $e) {
            FlashMessage::error('Error toggling resource status: ' . $e->getMessage());
            return $this->redirect('/admin/resources');
        }
    }

    /**
     * Download resource (admin version)
     */
    public function download(Request $request, $id): Response
    {
        try {
            $resource = Resource::find($id);

            if (!$resource) {
                FlashMessage::error('Resource not found');
                return $this->redirect('/admin/resources');
            }

            // Increment download count
            $resource->increment('download_count');

            // Get file path
            $filePath = $resource->file_path;
            $fullPath = $this->uploader->getFullPath($this->extractRelativeImagePath($filePath));

            if (!file_exists($fullPath)) {
                FlashMessage::error('File not found on server.');
                return $this->redirect("/admin/resources/show/{$id}");
            }

            // Get file info
            $fileName = $resource->file_name ?: 'resource_' . $id . '.' . $resource->file_extension;
            $fileSize = filesize($fullPath);
            $mimeType = mime_content_type($fullPath);

            // Set headers for download
            header('Content-Description: File Transfer');
            header('Content-Type: ' . $mimeType);
            header('Content-Disposition: attachment; filename="' . basename($fileName) . '"');
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . $fileSize);

            // Clear output buffer
            if (ob_get_length()) ob_clean();
            flush();

            // Read file
            readfile($fullPath);
            exit;
        } catch (Exception $e) {
            FlashMessage::error('Error downloading resource: ' . $e->getMessage());
            return $this->redirect("/admin/resources/show/{$id}");
        }
    }

    /**
     * Export resources to CSV
     */
    public function export(Request $request): Response
    {
        try {
            $query = Resource::with(['user'])->orderBy('created_at', 'DESC');

            // Apply filters if any
            $params = $request->getQueryParams();
            if (!empty($params['status'])) {
                $query->where('status', $params['status']);
            }
            if (!empty($params['file_type'])) {
                $query->where('file_type', $params['file_type']);
            }

            $resources = $query->get();

            // Set headers for CSV download
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename=resources_' . date('Y-m-d') . '.csv');

            $output = fopen('php://output', 'w');

            // Write CSV header
            fputcsv($output, [
                'ID',
                'Title',
                'Description',
                'File Type',
                'File Size',
                'Price',
                'Status',
                'Downloads',
                'Paid Downloads',
                'Revenue',
                'Created By',
                'Created At',
                'Published At'
            ]);

            // Write data rows
            foreach ($resources as $resource) {
                fputcsv($output, [
                    $resource->id,
                    $resource->title,
                    $resource->description,
                    $resource->file_type,
                    $this->formatBytes($resource->file_size),
                    number_format($resource->price, 2),
                    $resource->status,
                    $resource->download_count,
                    $resource->paid_download_count,
                    number_format($resource->revenue_generated, 2),
                    $resource->user->name ?? 'Unknown',
                    $resource->created_at,
                    $resource->published_at
                ]);
            }

            fclose($output);
            exit;
        } catch (Exception $e) {
            FlashMessage::error('Error exporting resources: ' . $e->getMessage());
            return $this->redirect('/admin/resources');
        }
    }

    /**
     * Configure uploader based on file type
     */
    private function configureUploaderForFileType(string $fileType): void
    {
        switch ($fileType) {
            case 'image':
                $this->uploader->imagesOnly(10 * 1024 * 1024); // 10MB max for images
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg'];
                $this->uploader->setAllowedExtensions($allowedExtensions);
                break;

            case 'video':
                $this->uploader->videosOnly(100 * 1024 * 1024); // 100MB max for videos
                $allowedExtensions = ['mp4', 'avi', 'mov', 'wmv', 'flv', 'mkv', 'webm'];
                $this->uploader->setAllowedExtensions($allowedExtensions);
                break;

            case 'audio':
                $this->uploader->audiosOnly(50 * 1024 * 1024); // 50MB max for audio
                $allowedExtensions = ['mp3', 'wav', 'ogg', 'm4a', 'flac'];
                $this->uploader->setAllowedExtensions($allowedExtensions);
                break;

            case 'document':
                $this->uploader->documentsOnly(20 * 1024 * 1024); // 20MB max for documents
                $allowedExtensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt', 'rtf'];
                $this->uploader->setAllowedExtensions($allowedExtensions);
                break;

            default: // 'other'
                $this->uploader->setMaxFileSize(50 * 1024 * 1024); // 50MB max
                $this->uploader->setAllowedExtensions([]); // Allow all extensions for 'other'
                break;
        }
    }

    /**
     * Validate resource data
     */
    private function validateResourceData($request, $resourceId = null): ErrorMessage
    {
        $data = $request->getParsedBody();
        $errors = new ErrorMessage();

        // Title validation
        if (empty($data['title'])) {
            $errors->add('title', 'Resource title is required');
        } elseif (strlen($data['title']) > 255) {
            $errors->add('title', 'Resource title must not exceed 255 characters');
        }

        // Description validation
        if (empty($data['description'])) {
            $errors->add('description', 'Resource description is required');
        } elseif (strlen(strip_tags($data['description'])) < 10) {
            $errors->add('description', 'Resource description must be at least 10 characters');
        }

        // File type validation
        if (!in_array($data['file_type'] ?? '', ['image', 'video', 'audio', 'document', 'other'])) {
            $errors->add('file_type', 'Resource file type must be a valid file type.');
        }

        // Price validation
        if (!isset($data['price']) || $data['price'] === '') {
            $errors->add('price', 'Resource price is required');
        } elseif (!is_numeric($data['price']) || $data['price'] < 0) {
            $errors->add('price', 'Resource price must be a valid non-negative number');
        }

        // Status validation
        if (!in_array($data['status'] ?? '', ['published', 'pending', 'draft'])) {
            $errors->add('status', 'Resource status must be valid.');
        }

        // File validation (only for new resources or when file is being uploaded)
        if (!$resourceId && (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK)) {
            $errors->add('file', 'Please select a file to upload');
        } elseif (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            if ($_FILES['file']['error'] === UPLOAD_ERR_INI_SIZE || $_FILES['file']['error'] === UPLOAD_ERR_FORM_SIZE) {
                $errors->add('file', 'File is too large. Please upload a smaller file.');
            } elseif ($_FILES['file']['error'] !== UPLOAD_ERR_OK) {
                $errors->add('file', 'File upload error: ' . $_FILES['file']['error']);
            }
        }

        return $errors;
    }

    /**
     * Extract relative image path from full URL or path
     */
    private function extractRelativeImagePath(string $imagePath): ?string
    {
        if (empty($imagePath)) {
            return null;
        }

        // If it's already a relative path, return it
        if (!filter_var($imagePath, FILTER_VALIDATE_URL) && strpos($imagePath, '://') === false) {
            return ltrim($imagePath, '/\\');
        }

        // Parse URL to get the path
        $parsedUrl = parse_url($imagePath);
        $path = $parsedUrl['path'] ?? '';

        if (empty($path)) {
            return null;
        }

        // Remove the base URL prefix if present
        $baseUrl = rtrim($this->uploader->getBaseUrl(), '/');
        if (!empty($baseUrl) && strpos($path, $baseUrl) === 0) {
            $path = substr($path, strlen($baseUrl));
        }

        return ltrim($path, '/');
    }

    /**
     * Get current user ID from session
     */
    private function getCurrentUserId(Request $request): ?int
    {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Get user ID from session
        $userId = $_SESSION['user_id'] ?? $_SESSION['auth_user_id'] ?? $_SESSION['admin_id'] ?? null;

        return $userId ? (int) $userId : null;
    }

    /**
     * Format bytes to human readable format
     */
    private function formatBytes($bytes, $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
