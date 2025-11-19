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
use Plugs\Utils\FlashMessage;
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

    public function newCategory()
    {
        // Get parent categories for the dropdown
        $parentCategories = Category::whereNull('parent_id')
            ->orWhere('parent_id', 0)
            ->orderBy('name', 'ASC')
            ->get();

        $data = [
            'page_title' => 'Create New Category',
            'parentCategories' => $parentCategories
        ];

        return $this->view('admin.categories.create', $data);
    }

    /**
     * Handle bulk actions (POST)
     */
    public function bulkAction(Request $request): Response
    {
        try {
            $body = $request->getParsedBody();
            $action = $body['action'] ?? '';
            $categoryIds = $body['category_ids'] ?? [];

            if (empty($categoryIds) || !is_array($categoryIds)) {
                // $_SESSION['error_message'] = 'No categories selected';
                FlashMessage::error('No categories selected');
                return $this->redirect('/admin/categories');
            }

            $count = 0;

            switch ($action) {
                case 'activate':
                    foreach ($categoryIds as $id) {
                        $category = Category::find($id);
                        if ($category) {
                            $category->is_active = true;
                            $category->save();
                            $count++;
                        }
                    }
                    // $_SESSION['success_message'] = "Successfully activated {$count} " . ($count === 1 ? 'category' : 'categories');
                    FlashMessage::success("Successfully activated {$count} " . ($count === 1 ? 'category' : 'categories'));
                    break;

                case 'archive':
                    foreach ($categoryIds as $id) {
                        $category = Category::find($id);
                        if ($category) {
                            $category->is_active = false;
                            $category->save();
                            $count++;
                        }
                    }
                    // $_SESSION['success_message'] = "Successfully archived {$count} " . ($count === 1 ? 'category' : 'categories');
                    FlashMessage::success("Successfully archived {$count} " . ($count === 1 ? 'category' : 'categories'));
                    break;

                case 'delete':
                    $count = Category::destroyMany($categoryIds);
                    $_SESSION['success_message'] = "Successfully deleted {$count} " . ($count === 1 ? 'category' : 'categories');
                    FlashMessage::success("Successfully deleted {$count} " . ($count === 1 ? 'category' : 'categories'));
                    break;

                default:
                    // $_SESSION['error_message'] = 'Invalid action';
                    FlashMessage::error("Invalid action");
                    break;
            }

            return $this->redirect('/admin/categories');
        } catch (Exception $e) {
            // $_SESSION['error_message'] = 'Bulk action failed: ' . $e->getMessage();
            FlashMessage::error("Bulk action failed: " . $e->getMessage());
            return $this->redirect('/admin/categories');
        }
    }

    /**
     * Delete single category (POST with DELETE method spoofing)
     */
    public function delete(Request $request, $id): Response
    {
        try {
            $id = (int)$id;
            $category = Category::find($id);

            if (!$category) {
                // $_SESSION['error_message'] = 'Category not found';
                FlashMessage::error("Category not found");
                return $this->redirect('/admin/categories');
            }

            $categoryName = $category->name;
            $category->delete();

            // $_SESSION['success_message'] = "Category '{$categoryName}' deleted successfully";
            FlashMessage::success("Category '{$categoryName}' deleted successfully");
            return $this->redirect('/admin/categories');
        } catch (Exception $e) {
            // $_SESSION['error_message'] = 'Failed to delete category: ' . $e->getMessage();
            FlashMessage::error("Failed to delete category: " . $e->getMessage());
            return $this->redirect('/admin/categories');
        }
    }
}
