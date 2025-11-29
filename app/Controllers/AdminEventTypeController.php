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
use App\Models\EventType;
use Plugs\Paginator\Paginator;
use Plugs\Base\Controller\Controller;
use Plugs\Utils\FlashMessage;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AdminEventTypeController extends Controller
{
    /**
     * Display article management page
     */
    public function manage(Request $request): Response
    {
        try {
            // Get query parameters
            $queryParams = $request->getQueryParams();
            $perPage = 15;
            $currentPage = (int)($queryParams['page'] ?? 1);

            // Build query
            $query = EventType::query();

            // Create paginator
            $paginator = Paginator::fromQuery($query, $perPage, $currentPage);

            $types = $paginator->items();

            $data = [
                'types' => $types,
                'paginator' => $paginator,
                'page_title' => 'Manage Event Types'
            ];

            return $this->view('admin.event-types.manage', $data);
        } catch (Exception $e) {
            $_SESSION['error_message'] = 'Failed to load categories: ' . $e->getMessage();
            return $this->view('admin.categories.manage', [
                'types' => [],
                'paginator' => null,
                'page_title' => 'Manage Event Types'
            ]);
        }
    }

    public function newType()
    {
        $data = [
            'page_title' => 'Create New Event Type',
            'category' => null
        ];

        return $this->view('admin.event-types.create', $data);
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
                FlashMessage::error('Event Type name is required');
                return $this->redirect('/admin/event-types/create');
            }

            // Prepare category data
            $categoryData = [
                'name' => $body['name'],
                'slug' => $body['slug'] ?? null,
                'description' => $body['description'] ?? null,
                'color' => $body['color'] ?? '#00f5d4',
                'icon' => $body['icon'] ?? 'bi-folder',
                'is_active' => true,
            ];

            // Auto-generate slug if not provided
            if (empty($categoryData['slug'])) {
                $categoryData['slug'] = $this->generateSlug($categoryData['name']);
            }

            // Check if slug is unique
            $existingCategory = EventType::where('slug', $categoryData['slug'])->first();
            if ($existingCategory) {
                FlashMessage::error('Slug already exists. Please choose a different one.');
                return $this->redirect('/admin/event-types/create');
            }

            // Create the category
            $category = EventType::create($categoryData);

            if ($category) {
                FlashMessage::success('Event Type created successfully!');
                return $this->redirect('/admin/event-types');
            } else {
                FlashMessage::error('Failed to create category. Please try again.');
                return $this->redirect('/admin/event-types/create');
            }
        } catch (Exception $e) {
            FlashMessage::error('Error creating category: ' . $e->getMessage());
            return $this->redirect('/admin/event-types/create');
        }
    }

    /**
     * Show edit category form
     */
    public function edit(Request $request, $id): Response
    {
        try {
            $type = EventType::find($id);

            if (!$type) {
                FlashMessage::error('Event Type not found');
                return $this->redirect('/admin/event-types');
            }

            $data = [
                'page_title' => 'Edit Event Type',
                'type' => $type,
            ];

            return $this->view('admin.event-types.edit', $data);
        } catch (Exception $e) {
            FlashMessage::error('Error loading category: ' . $e->getMessage());
            return $this->redirect('/admin/event-types');
        }
    }

    /**
     * Update existing category
     */
    public function update(Request $request, $id): Response
    {
        try {
            $category = EventType::find($id);

            if (!$category) {
                FlashMessage::error('Event Type not found');
                return $this->redirect('/admin/event-types');
            }

            $body = $request->getParsedBody();

            // Validate required fields
            if (empty($body['name'])) {
                FlashMessage::error('Event Type name is required');
                return $this->redirect("/admin/event-types/edit/{$id}");
            }

            // Prepare category data
            $categoryData = [
                'name' => $body['name'],
                'slug' => $body['slug'] ?? null,
                'description' => $body['description'] ?? null,
                'color' => $body['color'] ?? '#667eea',
                'icon' => $body['icon'] ?? 'bi-folder',
                'is_active' => isset($body['is_active']), // Checkbox for active status
            ];

            // Auto-generate slug if not provided
            if (empty($categoryData['slug'])) {
                $categoryData['slug'] = $this->generateSlug($categoryData['name']);
            }

            // Check if slug is unique (excluding current category)
            $existingCategory = EventType::query()
                ->instanceWhere('slug', $categoryData['slug'])
                ->instanceWhere('id', '!=', $id)
                ->first();

            if ($existingCategory) {
                FlashMessage::error('Slug "' . $categoryData['slug'] . '" already exists. Please choose a different one.');
                return $this->redirect("/admin/event-types/edit/{$id}");
            }

            // Prevent circular reference (category cannot be its own parent)
            if ($categoryData['parent_id'] == $id) {
                FlashMessage::error('A category cannot be its own parent.');
                return $this->redirect("/admin/event-types/edit/{$id}");
            }

            // Update the category
            $updated = $category->update($categoryData);

            if ($updated) {
                FlashMessage::success('Event Type updated successfully!');
                return $this->redirect('/admin/event-types');
            } else {
                FlashMessage::error('Failed to update type. Please try again.');
                return $this->redirect("/admin/event-types/edit/{$id}");
            }
        } catch (Exception $e) {
            FlashMessage::error('Error updating category: ' . $e->getMessage());
            return $this->redirect("/admin/event-types/edit/{$id}");
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
     * Delete single category (POST with DELETE method spoofing)
     */
    public function delete(Request $request, $id): Response
    {
        try {
            $id = (int)$id;
            $category = EventType::find($id);

            if (!$category) {
                FlashMessage::error("Event Type not found");
                return $this->redirect('/admin/event-types');
            }

            $categoryName = $category->name;
            $category->delete();

            FlashMessage::success("Event Type '{$categoryName}' deleted successfully");
            return $this->redirect('/admin/event-types');
        } catch (Exception $e) {
            FlashMessage::error("Failed to delete category: " . $e->getMessage());
            return $this->redirect('/admin/event-types');
        }
    }
}
