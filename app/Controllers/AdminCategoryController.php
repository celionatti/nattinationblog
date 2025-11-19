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
            $perPage = 15;
            $currentPage = (int)($queryParams['page'] ?? 1);

            // Build query
            $query = Category::query();

            // Apply status filter
            if ($statusFilter === '1') {
                $query = $query->where('is_active', true);
            } elseif ($statusFilter === '0') {
                $query = $query->where('is_active', false);
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
            'parentCategories' => $parentCategories,
            'category' => null
        ];

        return $this->view('admin.categories.create', $data);
    }

    /**
     * Store a new category
     */
    public function store(Request $request): Response
    {
        try {
            $body = $request->getParsedBody();

            // Validate required fields
            if (empty($body['name'])) {
                FlashMessage::error('Category name is required');
                return $this->redirect('/admin/categories/create');
            }

            // Prepare category data
            $categoryData = [
                'name' => $body['name'],
                'slug' => $body['slug'] ?? null,
                'description' => $body['description'] ?? null,
                'color' => $body['color'] ?? '#667eea',
                'icon' => $body['icon'] ?? 'bi-folder',
                'parent_id' => !empty($body['parent_id']) ? (int)$body['parent_id'] : null,
                'is_active' => true,
            ];

            // Auto-generate slug if not provided
            if (empty($categoryData['slug'])) {
                $categoryData['slug'] = $this->generateSlug($categoryData['name']);
            }

            // Check if slug is unique
            $existingCategory = Category::where('slug', $categoryData['slug'])->first();
            if ($existingCategory) {
                FlashMessage::error('Slug already exists. Please choose a different one.');
                return $this->redirect('/admin/categories/create');
            }

            // Create the category
            $category = Category::create($categoryData);

            if ($category) {
                FlashMessage::success('Category created successfully!');
                return $this->redirect('/admin/categories');
            } else {
                FlashMessage::error('Failed to create category. Please try again.');
                return $this->redirect('/admin/categories/create');
            }
        } catch (Exception $e) {
            FlashMessage::error('Error creating category: ' . $e->getMessage());
            return $this->redirect('/admin/categories/create');
        }
    }

    /**
     * Show edit category form
     */
    public function edit(Request $request, $id): Response
    {
        try {
            $category = Category::find($id);

            if (!$category) {
                FlashMessage::error('Category not found');
                return $this->redirect('/admin/categories');
            }

            // Get parent categories (exclude current category and its children to avoid circular reference)
            $parentCategories = Category::where('id', '!=', $id)
                ->where(function ($query) use ($id) {
                    $query->whereNull('parent_id')
                        ->orWhere('parent_id', 0)
                        ->orWhere('parent_id', '!=', $id);
                })
                ->orderBy('name', 'ASC')
                ->get();

            $data = [
                'page_title' => 'Edit Category',
                'category' => $category,
                'parentCategories' => $parentCategories
            ];

            return $this->view('admin.categories.edit', $data);
        } catch (Exception $e) {
            FlashMessage::error('Error loading category: ' . $e->getMessage());
            return $this->redirect('/admin/categories');
        }
    }

    /**
     * Update existing category
     */
    /**
     * Update existing category
     */
    public function update(Request $request, $id): Response
    {
        try {
            $category = Category::find($id);

            if (!$category) {
                FlashMessage::error('Category not found');
                return $this->redirect('/admin/categories');
            }

            $body = $request->getParsedBody();

            // Validate required fields
            if (empty($body['name'])) {
                FlashMessage::error('Category name is required');
                return $this->redirect("/admin/categories/edit/{$id}");
            }

            // Prepare category data
            $categoryData = [
                'name' => $body['name'],
                'slug' => $body['slug'] ?? null,
                'description' => $body['description'] ?? null,
                'color' => $body['color'] ?? '#667eea',
                'icon' => $body['icon'] ?? 'bi-folder',
                'parent_id' => !empty($body['parent_id']) ? (int)$body['parent_id'] : null,
                'is_active' => isset($body['is_active']), // Checkbox for active status
            ];

            // Auto-generate slug if not provided
            if (empty($categoryData['slug'])) {
                $categoryData['slug'] = $this->generateSlug($categoryData['name']);
            }

            // Check if slug is unique (excluding current category)
            $existingCategory = Category::query()
                ->instanceWhere('slug', $categoryData['slug'])
                ->instanceWhere('id', '!=', $id)
                ->first();

            if ($existingCategory) {
                FlashMessage::error('Slug "' . $categoryData['slug'] . '" already exists. Please choose a different one.');
                return $this->redirect("/admin/categories/edit/{$id}");
            }

            // Prevent circular reference (category cannot be its own parent)
            if ($categoryData['parent_id'] == $id) {
                FlashMessage::error('A category cannot be its own parent.');
                return $this->redirect("/admin/categories/edit/{$id}");
            }

            // Update the category
            $updated = $category->update($categoryData);

            if ($updated) {
                FlashMessage::success('Category updated successfully!');
                return $this->redirect('/admin/categories');
            } else {
                FlashMessage::error('Failed to update category. Please try again.');
                return $this->redirect("/admin/categories/edit/{$id}");
            }
        } catch (Exception $e) {
            FlashMessage::error('Error updating category: ' . $e->getMessage());
            return $this->redirect("/admin/categories/edit/{$id}");
        }
    }

    /**
     * Generate URL-friendly slug
     */
    private function generateSlug(string $name): string
    {
        return strtolower(trim(preg_replace('/[^a-zA-Z0-9]+/', '-', $name), '-'));
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
                    FlashMessage::success("Successfully archived {$count} " . ($count === 1 ? 'category' : 'categories'));
                    break;

                case 'delete':
                    $count = Category::destroyMany($categoryIds);
                    $_SESSION['success_message'] = "Successfully deleted {$count} " . ($count === 1 ? 'category' : 'categories');
                    FlashMessage::success("Successfully deleted {$count} " . ($count === 1 ? 'category' : 'categories'));
                    break;

                default:
                    FlashMessage::error("Invalid action");
                    break;
            }

            return $this->redirect('/admin/categories');
        } catch (Exception $e) {
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
                FlashMessage::error("Category not found");
                return $this->redirect('/admin/categories');
            }

            $categoryName = $category->name;
            $category->delete();

            FlashMessage::success("Category '{$categoryName}' deleted successfully");
            return $this->redirect('/admin/categories');
        } catch (Exception $e) {
            FlashMessage::error("Failed to delete category: " . $e->getMessage());
            return $this->redirect('/admin/categories');
        }
    }
}
