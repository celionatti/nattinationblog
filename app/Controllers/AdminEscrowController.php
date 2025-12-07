<?php

declare(strict_types=1);

namespace App\Controllers;

/*
|--------------------------------------------------------------------------
| Admin Categories Controller
|--------------------------------------------------------------------------
| This controller handles administrative functionalities for categories.
| Includes draft/revision system, category management.
*/

use Exception;
use App\Models\Escrow;
use Plugs\Paginator\Paginator;
use Plugs\Base\Controller\Controller;
use Plugs\Utils\FlashMessage;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AdminEscrowController extends Controller
{
    public function manage(Request $request)
    {
        try {
            $queryParams = $request->getQueryParams();
            $perPage = 10;
            $currentPage = (int)($queryParams['page'] ?? 1);

            // Build query
            $query = Escrow::with(['user']);

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

            return $this->view('admin.escrow.manage', [
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
            return $this->view('admin.escrow.manage', [
                'resources' => [],
                'paginator' => null,
                'status' => null,
                'file_type' => null,
                'page_title' => 'Resources Management',
                'page_subtitle' => 'Upload and manage media, files, and documents for your users.',
            ]);
        }
    }
}
