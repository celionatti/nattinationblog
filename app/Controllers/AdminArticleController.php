<?php

declare(strict_types=1);

namespace App\Controllers;

/*
|--------------------------------------------------------------------------
| Admin Article Controller
|--------------------------------------------------------------------------
| This controller handles administrative functionalities for articles.
| Includes draft/revision system, category management, and image uploads.
*/

use Exception;
use App\Models\User;
use App\Models\Article;
use App\Models\Category;
use Plugs\View\ErrorMessage;
use Plugs\Utils\FlashMessage;
use Plugs\Paginator\Paginator;
use Plugs\Upload\FileUploader;
use Plugs\Upload\UploadedFile;
use App\Models\ArticleCategories;
use Plugs\Base\Controller\Controller;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AdminArticleController extends Controller
{
    private $uploader;

    public function onConstruct()
    {
        $this->uploader = new FileUploader();
        $this->uploader->usePublicFolder("uploads/articles");
        $this->uploader->imagesOnly(5 * 1024 * 1024);
        $this->uploader->disableSecurityFiles();
    }

    /**
     * Display article management page
     */
    public function manage(Request $request): Response
    {
        try {
            $queryParams = $request->getQueryParams();
            $statusFilter = $queryParams['status'] ?? 'all';
            $authorFilter = $queryParams['author'] ?? 'all';
            $dateFilter = $queryParams['date'] ?? 'all';
            $perPage = 10;
            $currentPage = (int)($queryParams['page'] ?? 1);

            // Build query - Start with base query
            $query = Article::with(['author', 'categories']);

            // Apply status filter
            if ($statusFilter !== 'all') {
                $query = $query->where('status', $statusFilter);
            }

            // Apply author filter
            if ($authorFilter !== 'all') {
                $query = $query->where('author_id', (int)$authorFilter);
            }

            // Apply date filter
            if ($dateFilter !== 'all') {
                switch ($dateFilter) {
                    case 'today':
                        $query = $query->where('created_at', '>=', date('Y-m-d 00:00:00'));
                        break;
                    case 'week':
                        $query = $query->where('created_at', '>=', date('Y-m-d 00:00:00', strtotime('-1 week')));
                        break;
                    case 'month':
                        $query = $query->where('created_at', '>=', date('Y-m-d 00:00:00', strtotime('-1 month')));
                        break;
                }
            }

            // Apply ordering
            $query = $query->orderBy('created_at', 'DESC');

            // Create paginator and get results
            $paginator = Paginator::fromQuery($query, $perPage, $currentPage);
            $articles = $paginator->items();
            $authors = User::all();

            return $this->view('admin.articles.manage', [
                'articles' => $articles,
                'authors' => $authors,
                'paginator' => $paginator,
                'status_filter' => $statusFilter,
                'author_filter' => $authorFilter,
                'date_filter' => $dateFilter,
                'page_title' => 'Manage Articles'
            ]);
        } catch (Exception $e) {
            FlashMessage::error('Failed to load articles: ' . $e->getMessage());
            return $this->view('admin.articles.manage', [
                'articles' => [],
                'authors' => [],
                'paginator' => null,
                'status_filter' => 'all',
                'author_filter' => 'all',
                'date_filter' => 'all',
                'page_title' => 'Manage Articles'
            ]);
        }
    }

    /**
     * Display new article form
     */
    public function newArticle(Request $request): Response
    {
        try {
            // Get active categories from database
            $categories = Category::where('is_active', true)
                ->orderBy('sort_order', 'ASC')
                ->orderBy('name', 'ASC')
                ->get();

            $data = [
                'page_title' => 'Create New Article',
                'categories' => $categories
            ];

            return $this->view('admin.articles.create', $data);
        } catch (Exception $e) {
            return $this->json([
                'success' => false,
                'message' => 'Failed to load page: ' . $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $this->currentRequest = $request;

            $data = $request->getParsedBody();

            $isPublish = ($data['action'] ?? '') === 'publish';

            $errors = $this->validateArticleData($request, $isPublish);

            // If validation fails, redirect back with errors
            if ($errors->any()) {
                return $this->back($errors);
            }

            // Get current user ID
            $userId = $this->getCurrentUserId($request);
            if (!$userId) {
                FlashMessage::error('You must be logged in to create an article.');
                return $this->redirect('/admin/articles/new-article');
            }

            // Handle featured image upload
            $featuredImage = null;

            if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK) {
                $file = UploadedFile::createFromFilesArray($_FILES['featured_image']);
                try {
                    $uploadResult = $this->uploader->upload($file, null, (string)$userId);
                    $featuredImage = $uploadResult['url'];
                } catch (Exception $e) {
                    FlashMessage::error("Upload failed: " . $e->getMessage());
                    return $this->redirect("/admin/articles/new-article");
                }
            }

            // Generate slug from title
            $slug = generateSlug(
                $data['title'],
                '-',
                fn($s) => Article::where('slug', $s)->exists()
            );

            // Generate excerpt if not provided or empty
            $excerpt = $this->getExcerpt($data);

            // Generate SEO fields if not provided
            $seoTitle = !empty(trim($data['seo_title'] ?? ''))
                ? trim($data['seo_title'])
                : generateSeoTitle($data['title']);

            $seoDescription = !empty(trim($data['seo_description'] ?? ''))
                ? trim($data['seo_description'])
                : generateSeoDescription($data['content'], $data['title']);

            $seoKeywords = !empty(trim($data['seo_keywords'] ?? ''))
                ? trim($data['seo_keywords'])
                : generateSeoKeywords($data['content'], $data['title']);

            // Determine published_at date
            $publishedAt = null;
            if ($isPublish) {
                $publishedAt = date('Y-m-d H:i:s');
            }

            // Prepare article data
            $articleData = [
                'title' => trim($data['title']),
                'slug' => $slug,
                'content' => $data['content'],
                'excerpt' => $excerpt,
                'categories' => $data['categories'],
                'featured_image' => $featuredImage,
                'status' => $isPublish ? "published" : 'draft',
                'author_id' => $userId,
                'seo_title' => $seoTitle,
                'seo_description' => $seoDescription,
                'seo_keywords' => $seoKeywords,
                'published_at' => $publishedAt,
            ];

            // Create the article
            $article = Article::create($articleData);

            if ($article) {
                if ($isPublish) {
                    // Create article categories linking
                    $this->createArticleCategories($article, $data['categories']);
                }

                $textStatus = $isPublish ? "created" : "drafted";

                FlashMessage::success("Article {$textStatus} successfully!");


                // Redirect based on action
                if ($data['action'] === 'draft') {
                    return $this->redirect("/admin/articles/edit/{$article->id}");
                }

                return $this->redirect('/admin/articles');
            } else {
                FlashMessage::error('Failed to create article. Please try again.');
                return $this->redirect('/admin/articles/new-article');
            }
        } catch (Exception $e) {
            FlashMessage::error('Error creating article: ' . $e->getMessage());
            return $this->redirect('/admin/articles/new-article');
        }
    }

    public function editArticle(Request $request, $id): Response
    {
        try {
            // Get active categories from database
            $categories = Category::where('is_active', true)
                ->orderBy('sort_order', 'ASC')
                ->orderBy('name', 'ASC')
                ->get();

            $article = Article::find($id);

            if (!$article) {
                FlashMessage::error('Article not found');
                return $this->redirect('/admin/articles');
            }

            $data = [
                'page_title' => 'Edit Article',
                'categories' => $categories,
                'article' => $article
            ];

            return $this->view('admin.articles.edit', $data);
        } catch (Exception $e) {
            FlashMessage::error('Error loading article: ' . $e->getMessage());
            return $this->redirect('/admin/articles');
        }
    }

    /**
     * Update existing article
     */
    public function update(Request $request, $id): Response
    {
        try {
            $this->currentRequest = $request;

            // Find the article
            $article = Article::find($id);

            if (!$article) {
                FlashMessage::error('Article not found');
                return $this->redirect('/admin/articles');
            }

            $data = $request->getParsedBody();
            $isPublish = ($data['action'] ?? '') === 'publish';

            // Validate article data
            $errors = $this->validateArticleData($request, $isPublish);

            if ($errors->any()) {
                return $this->back($errors);
            }

            // Get current user ID
            $userId = $this->getCurrentUserId($request);
            if (!$userId) {
                FlashMessage::error('You must be logged in to update an article.');
                return $this->redirect("/admin/articles/edit/{$id}");
            }

            // Handle featured image
            $featuredImage = $article->featured_image; // Keep existing image by default

            // Check if user wants to remove the image
            if (isset($data['remove_featured_image']) && $data['remove_featured_image'] == '1') {
                // Delete old image if exists
                if ($featuredImage) {
                    try {
                        $relativePath = $this->extractRelativeImagePath($featuredImage);
                        if ($relativePath) {
                            $this->uploader->delete($relativePath);
                        }
                    } catch (Exception $e) {
                        // Log but don't fail the update
                        error_log("Failed to delete old image: " . $e->getMessage());
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
                        error_log("Failed to delete old image: " . $e->getMessage());
                    }
                }

                // Upload new image
                $file = UploadedFile::createFromFilesArray($_FILES['featured_image']);
                try {
                    $uploadResult = $this->uploader->upload($file, null, (string)$userId);
                    $featuredImage = $uploadResult['url'];
                } catch (Exception $e) {
                    FlashMessage::error("Upload failed: " . $e->getMessage());
                    return $this->redirect("/admin/articles/edit/{$id}");
                }
            }

            // Generate excerpt if not provided or empty
            $excerpt = $this->getExcerpt($data);

            // Generate SEO fields if not provided
            $seoTitle = !empty(trim($data['seo_title'] ?? ''))
                ? trim($data['seo_title'])
                : generateSeoTitle($data['title']);

            $seoDescription = !empty(trim($data['seo_description'] ?? ''))
                ? trim($data['seo_description'])
                : generateSeoDescription($data['content'], $data['title']);

            $seoKeywords = !empty(trim($data['seo_keywords'] ?? ''))
                ? trim($data['seo_keywords'])
                : generateSeoKeywords($data['content'], $data['title']);

            // Determine published_at date
            $publishedAt = $article->published_at; // Keep existing publish date

            // Convert DateTime to string if it's a DateTime object
            if ($publishedAt instanceof \DateTime) {
                $publishedAt = $publishedAt->format('Y-m-d H:i:s');
            }

            // If article is being published for the first time
            if ($isPublish && $article->status !== 'published' && !$publishedAt) {
                $publishedAt = date('Y-m-d H:i:s');
            }

            // Prepare update data
            $updateData = [
                'title' => trim($data['title']),
                'content' => $data['content'],
                'excerpt' => $excerpt,
                'categories' => $data['categories'],
                'featured_image' => $featuredImage,
                'status' => $isPublish ? 'published' : 'draft',
                'seo_title' => $seoTitle,
                'seo_description' => $seoDescription,
                'seo_keywords' => $seoKeywords,
                'published_at' => $publishedAt,
            ];

            // Handle article categories FIRST, before updating the article
            if (!empty($data['categories'])) {
                // Always delete existing categories first
                $this->deleteArticleCategories($article->id);

                // Create new category relationship only if publishing
                if ($isPublish) {
                    $this->createArticleCategories($article, $data['categories']);
                }
            }

            // Update the article
            $updated = $article->update($updateData);

            if ($updated) {
                $textStatus = $isPublish ? "updated" : "saved as draft";
                FlashMessage::success("Article {$textStatus} successfully!");

                // Redirect based on action
                if ($data['action'] === 'draft') {
                    return $this->redirect("/admin/articles/edit/{$article->id}");
                }

                return $this->redirect('/admin/articles');
            } else {
                FlashMessage::error('Failed to update article. Please try again.');
                return $this->redirect("/admin/articles/edit/{$id}");
            }
        } catch (Exception $e) {
            FlashMessage::error('Error updating article: ' . $e->getMessage());
            return $this->redirect("/admin/articles/edit/{$id}");
        }
    }

    public function showArticle(Request $request): Response
    {
        try {
            $id = $this->param($request, 'id');

            $article = Article::with(['categories', 'author'])->find($id);

            if (!$article) {
                FlashMessage::error('Article not found');
                return $this->redirect('/admin/articles');
            }

            $data = [
                'page_title' => "{$article->title} Details",
                'article' => $article
            ];

            return $this->view('admin.articles.show', $data);
        } catch (Exception $e) {
            FlashMessage::error('Error loading article: ' . $e->getMessage());
            return $this->redirect('/admin/articles');
        }
    }

    /**
     * Validate article data
     */
    private function validateArticleData($request, bool $isPublishing = false): ErrorMessage
    {
        $data = $request->getParsedBody();
        $errors = new ErrorMessage();

        // Title validation
        if (empty($data['title'])) {
            $errors->add('title', 'Title is required');
        } elseif (strlen($data['title']) > 255) {
            $errors->add('title', 'Title must not exceed 255 characters');
        }

        // Content validation (stricter for publishing)
        if ($isPublishing) {
            if (empty($data['content'])) {
                $errors->add('content', 'Content is required');
            } elseif (strlen(strip_tags($data['content'])) < 50) {
                $errors->add('content', 'Content must be at least 50 characters');
            }
        }

        // Excerpt validation
        if (!empty($data['excerpt']) && strlen($data['excerpt']) > 500) {
            $errors->add('excerpt', 'Excerpt must not exceed 500 characters');
        }

        // SEO title validation - ONLY if user actually entered something
        if (!empty($data['seo_title'])) {
            $seoTitleLength = strlen(trim($data['seo_title']));
            if ($seoTitleLength > 60) {
                $errors->add('seo_title', 'SEO title should not exceed 60 characters (currently ' . $seoTitleLength . ' characters)');
            }
        }

        // SEO description validation - ONLY if user actually entered something
        if (!empty($data['seo_description'])) {
            $seoDescLength = strlen(trim($data['seo_description']));
            if ($seoDescLength > 160) {
                $errors->add('seo_description', 'SEO description should not exceed 160 characters (currently ' . $seoDescLength . ' characters)');
            }
        }

        // SEO keywords validation - ONLY if user actually entered something
        if (!empty($data['seo_keywords'])) {
            $seoKeywordsLength = strlen(trim($data['seo_keywords']));
            if ($seoKeywordsLength > 255) {
                $errors->add('seo_keywords', 'SEO keywords should not exceed 255 characters (currently ' . $seoKeywordsLength . ' characters)');
            }
        }

        // Category validation (optional but recommended for publishing)
        if ($isPublishing && empty($data['categories'])) {
            $errors->add('categories', 'Please select a category for your article');
        }

        return $errors;
    }

    /**
     * Delete article categories for a given article
     */
    private function deleteArticleCategories(int $articleId): bool
    {
        try {
            // Method 1: Using the model (safer, respects model logic)
            $existingCategories = ArticleCategories::where('article_id', $articleId)->get();

            foreach ($existingCategories as $categoryRelation) {
                $categoryRelation->delete();
            }

            return true;
        } catch (Exception $e) {
            error_log("Failed to delete article categories: " . $e->getMessage());

            // Method 2: Fallback to raw SQL if model method fails
            try {
                $connection = ArticleCategories::getPdo();
                $stmt = $connection->prepare("DELETE FROM article_categories WHERE article_id = ?");
                $stmt->execute([$articleId]);
                return true;
            } catch (Exception $e2) {
                error_log("Failed to delete article categories with SQL: " . $e2->getMessage());
                return false;
            }
        }
    }

    /**
     * Create article category relationship
     */
    private function createArticleCategories(Article $article, $category): bool
    {
        try {
            // Check if the relationship already exists to prevent duplicates
            $exists = ArticleCategories::where('article_id', $article->id)
                ->where('category_id', $category)
                ->exists();

            if ($exists) {
                // Relationship already exists, no need to create
                return true;
            }

            ArticleCategories::create([
                'article_id' => $article->id,
                'category_id' => $category
            ]);

            return true;
        } catch (Exception $e) {
            error_log("Failed to create article categories: " . $e->getMessage());
            FlashMessage::error("Failed to create article categories: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Handle bulk actions (POST)
     */
    public function bulkAction(Request $request): Response
    {
        try {
            $body = $request->getParsedBody();
            $action = $body['action'] ?? '';
            $articleIds = $body['article_ids'] ?? [];

            if (empty($articleIds) || !is_array($articleIds)) {
                FlashMessage::error('No articles selected');
                return $this->redirect('/admin/articles');
            }

            // Sanitize article IDs
            $articleIds = array_map('intval', $articleIds);
            $articleIds = array_filter($articleIds, fn($id) => $id > 0);

            if (empty($articleIds)) {
                FlashMessage::error('Invalid article selection');
                return $this->redirect('/admin/articles');
            }

            $count = 0;

            switch ($action) {
                case 'publish':
                    foreach ($articleIds as $id) {
                        $article = Article::find($id);
                        if ($article) {
                            $article->status = "published";
                            if ($article->published_at === null) {
                                $article->published_at = date('Y-m-d H:i:s');
                            }
                            $article->save();
                            $count++;
                        }
                    }
                    FlashMessage::success("Successfully published {$count} " . ($count === 1 ? 'article' : 'articles'));
                    break;

                case 'draft':
                    foreach ($articleIds as $id) {
                        $article = Article::find($id);
                        if ($article) {
                            $article->status = "draft";
                            $article->save();
                            $count++;
                        }
                    }
                    FlashMessage::success("Successfully drafted {$count} " . ($count === 1 ? 'article' : 'articles'));
                    break;

                case 'archive':
                    foreach ($articleIds as $id) {
                        $article = Article::find($id);
                        if ($article) {
                            $article->status = "archived";
                            $article->save();
                            $count++;
                        }
                    }
                    FlashMessage::success("Successfully archived {$count} " . ($count === 1 ? 'article' : 'articles'));
                    break;

                case 'delete':
                    // Get articles with their featured images before deletion
                    $articles = Article::whereIn('id', $articleIds)->get();
                    $imagesToDelete = [];

                    foreach ($articles as $article) {
                        if ($article->featured_image) {
                            $imagesToDelete[] = $article->featured_image;
                        }
                    }

                    // Delete the articles (this will also cascade delete related records like ArticleCategories)
                    $count = Article::destroyMany($articleIds);

                    // Delete associated images after successful article deletion
                    if ($count > 0) {
                        $deletedImages = 0;
                        $failedImages = 0;

                        foreach ($imagesToDelete as $imagePath) {
                            try {
                                // Extract relative path from full URL/path
                                $relativePath = $this->extractRelativeImagePath($imagePath);

                                if ($relativePath && $this->uploader->delete($relativePath)) {
                                    $deletedImages++;
                                } else {
                                    $failedImages++;
                                    FlashMessage::info("Failed to delete image: " . $relativePath, $imagePath ?? "");
                                }
                            } catch (Exception $e) {
                                $failedImages++;
                                FlashMessage::info("Error deleting image: " . $e->getMessage(), $imagePath ?? "");
                            }
                        }

                        $message = "Successfully deleted {$count} " . ($count === 1 ? 'article' : 'articles');

                        if ($deletedImages > 0) {
                            $message .= " and {$deletedImages} " . ($deletedImages === 1 ? 'image' : 'images');
                        }

                        if ($failedImages > 0) {
                            $message .= " ({$failedImages} " . ($failedImages === 1 ? 'image' : 'images') . " could not be deleted)";
                        }

                        FlashMessage::success($message);
                    } else {
                        FlashMessage::warning('No articles were deleted');
                    }
                    break;

                default:
                    FlashMessage::error("Invalid action");
                    break;
            }

            return $this->redirect('/admin/articles');
        } catch (Exception $e) {
            FlashMessage::error("Bulk action failed: " . $e->getMessage(), $action ?? "unknown");
            return $this->redirect('/admin/articles');
        }
    }

    /**
     * Extract relative image path from full URL or path
     * Handles both URL formats (/uploads/articles/...) and file paths
     */
    private function extractRelativeImagePath(string $imagePath): ?string
    {
        if (empty($imagePath)) {
            return null;
        }

        // If it's already a relative path, return it
        if (!filter_var($imagePath, FILTER_VALIDATE_URL) && strpos($imagePath, '://') === false) {
            // Remove leading slashes
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

        // Remove leading slashes
        $path = ltrim($path, '/');

        // Handle common upload directory prefixes
        $commonPrefixes = ['uploads/', 'storage/uploads/', 'public/uploads/'];
        foreach ($commonPrefixes as $prefix) {
            if (strpos($path, $prefix) === 0) {
                // Path might already include the full structure
                return $path;
            }
        }

        return $path;
    }

    /**
     * Delete a single article with its associated image
     * Can be used as a standalone method for single deletions
     */
    public function destroy(Request $request, $id): Response
    {
        try {
            $articleId = $id;

            if ($articleId <= 0) {
                FlashMessage::error('Invalid article ID');
                return $this->redirect('/admin/articles');
            }

            $article = Article::find($articleId);

            if (!$article) {
                FlashMessage::error('Article not found');
                return $this->redirect('/admin/articles');
            }

            // Store featured image path before deletion
            $featuredImage = $article->featured_image;

            // Delete the article
            if ($article->delete()) {
                // Delete associated image if it exists
                if ($featuredImage) {
                    try {
                        $relativePath = $this->extractRelativeImagePath($featuredImage);

                        if ($relativePath) {
                            $this->uploader->delete($relativePath);
                        }
                    } catch (Exception $e) {
                        FlashMessage::error("Failed to delete article image: " . $e->getMessage(), "Image Path: {$featuredImage}");
                        // Don't fail the whole operation if image deletion fails
                    }
                }

                FlashMessage::success('Article deleted successfully');
            } else {
                FlashMessage::error('Failed to delete article');
            }

            return $this->redirect('/admin/articles');
        } catch (Exception $e) {
            FlashMessage::error('Error deleting article: ' . $e->getMessage(), "Article ID: {$articleId}");
            return $this->redirect('/admin/articles');
        }
    }

    /**
     * Get excerpt from data - handles both empty strings and null values
     */
    private function getExcerpt(array $data): string
    {
        // Check if excerpt exists and is not empty (after trimming)
        $excerpt = trim($data['excerpt'] ?? '');

        // If excerpt is provided and not empty, use it
        if (!empty($excerpt)) {
            return $excerpt;
        }

        // Otherwise generate from content
        return generateExcerpt($data['content'] ?? '');
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
}
