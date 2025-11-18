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
use App\Models\Category;
use Plugs\Paginator\Paginator;
use Plugs\Base\Controller\Controller;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AdminCategoryController extends Controller
{
    /**
     * Display article management page
     */
    public function manage(Request $request): Response
    {
        try {
            // Get query parameters
            $queryParams = $request->getQueryParams();
            $sortBy = $queryParams['sort_by'] ?? 'name';
            $statusFilter = $queryParams['status'] ?? 'all';
            $perPage = 2;
            $currentPage = (int)($queryParams['page'] ?? 1);

            // Build query
            $query = Category::query();

            // Apply status filter
            if ($statusFilter === '1') {
                $query = $query->where('is_active', 1);
            } elseif ($statusFilter === '0') {
                $query = $query->where('is_active', 0);
            }

            // Apply sorting
            switch ($sortBy) {
                case 'posts':
                    $query = $query->orderBy('sort_order', 'DESC');
                    break;
                case 'date':
                    $query = $query->orderBy('created_at', 'DESC');
                    break;
                case 'updated':
                    $query = $query->orderBy('updated_at', 'DESC');
                    break;
                case 'name':
                default:
                    $query = $query->orderBy('name', 'ASC');
                    break;
            }

            // Create paginator
            $paginator = Paginator::fromQuery($query, $perPage, $currentPage);

            // Append query parameters to pagination links
            $paginator->appends([
                'sort_by' => $sortBy,
                'status' => $statusFilter,
            ]);

            $categories = $paginator->items();

            // Get statistics for each category (placeholder for now)
            foreach ($categories as $category) {
                $category->posts_count = 0;
                $category->views_count = 0;
                $category->comments_count = 0;
            }

            $data = [
                'categories' => $categories,
                'paginator' => $paginator,
                'sort_by' => $sortBy,
                'status_filter' => $statusFilter,
                'page_title' => 'Manage Categories'
            ];

            return $this->view('admin.categories.manage', $data);
        } catch (Exception $e) {
            $_SESSION['error_message'] = 'Failed to load categories: ' . $e->getMessage();
            return $this->view('admin.categories.manage', [
                'categories' => [],
                'paginator' => null,
                'page_title' => 'Manage Categories'
            ]);
        }
    }
}
