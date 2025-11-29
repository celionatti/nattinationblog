<?php

declare(strict_types=1);

namespace App\Controllers;

/*
|--------------------------------------------------------------------------
| Admin Events Controller
|--------------------------------------------------------------------------
| This controller handles administrative functionalities for events.
*/

use Exception;
use App\Models\User;
use App\Models\Event;
use App\Models\EventCategories;
use App\Models\EventType;
use App\Models\EventTicket;
use Plugs\View\ErrorMessage;
use App\Models\EventDiscount;
use Plugs\Utils\FlashMessage;
use Plugs\Paginator\Paginator;
use Plugs\Upload\FileUploader;
use Plugs\Upload\UploadedFile;
use Plugs\Base\Controller\Controller;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AdminEventController extends Controller
{
    private $uploader;

    public function onConstruct()
    {
        $this->uploader = new FileUploader();
        $this->uploader->usePublicFolder("uploads/events");
        $this->uploader->imagesOnly(5 * 1024 * 1024);
        $this->uploader->disableSecurityFiles();
    }

    public function manage(Request $request)
    {
        try {
            $queryParams = $request->getQueryParams();
            $perPage = 10;
            $currentPage = (int)($queryParams['page'] ?? 1);

            // Build query
            $query = Event::with(['author', 'tickets']);

            // Apply ordering
            $query = $query->orderBy('event_date', 'ASC')
                ->orderBy('event_time', 'ASC');

            // Create paginator and get results
            $paginator = Paginator::fromQuery($query, $perPage, $currentPage);
            $events = $paginator->items();

            return $this->view('admin.events.manage', [
                'events' => $events,
                'paginator' => $paginator,
                'page_title' => 'Manage Events'
            ]);
        } catch (Exception $e) {
            FlashMessage::error('Failed to load events: ' . $e->getMessage());
            return $this->view('admin.events.manage', [
                'events' => [],
                'paginator' => null,
                'page_title' => 'Manage Events'
            ]);
        }
    }

    public function create(Request $request)
    {
        try {
            // Get active categories from database
            $categories = EventType::where('is_active', true)
                ->orderBy('sort_order', 'ASC')
                ->orderBy('name', 'ASC')
                ->get();

            return $this->view('admin.events.create', [
                'categories' => $categories,
                'page_title' => 'Create New Event'
            ]);
        } catch (Exception $e) {
            FlashMessage::error('Failed to load event creation page: ' . $e->getMessage());
            return $this->view('admin.events.create', [
                'categories' => [],
                'page_title' => 'Create New Event'
            ]);
        }
    }

    public function store(Request $request)
    {
        try {
            $this->currentRequest = $request;

            $data = $request->getParsedBody();
            $action = $data['action'] ?? 'pending';
            $isLaunch = $action === 'launch';

            $errors = $this->validateEventData($request, $isLaunch);

            // If validation fails, redirect back with errors
            if ($errors->any()) {
                return $this->back($errors);
            }

            // Get current user ID
            $userId = $this->getCurrentUserId($request);
            if (!$userId) {
                FlashMessage::error('You must be logged in to create an event.');
                return $this->redirect('/admin/events/create');
            }

            // Handle featured image upload
            $eventImage = null;
            if (isset($_FILES['event_image']) && $_FILES['event_image']['error'] === UPLOAD_ERR_OK) {
                $file = UploadedFile::createFromFilesArray($_FILES['event_image']);
                try {
                    $uploadResult = $this->uploader->upload($file, null, (string)$userId);
                    $eventImage = $uploadResult['url'];
                } catch (Exception $e) {
                    FlashMessage::error("Upload failed: " . $e->getMessage());
                    return $this->redirect("/admin/events/create");
                }
            }

            // Generate slug from title
            $slug = generateSlug(
                $data['title'],
                '-',
                fn($s) => Event::where('slug', $s)->exists()
            );

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
            if ($isLaunch) {
                $publishedAt = date('Y-m-d H:i:s');
            }

            // Prepare event data
            $eventData = [
                'title' => trim($data['title']),
                'slug' => $slug,
                'content' => $data['content'],
                'event_date' => $data['event_date'],
                'event_time' => $data['event_time'],
                'location' => trim($data['location']),
                'event_type' => $data['event_type'],
                'event_image' => $eventImage,
                'status' => $isLaunch ? "launched" : 'pending',
                'author_id' => $userId,
                'seo_title' => $seoTitle,
                'seo_description' => $seoDescription,
                'seo_keywords' => $seoKeywords,
                'published_at' => $publishedAt,
            ];

            // Start transaction for event, tickets, and discount creation
            $event = null;
            try {
                // Create the event
                $event = Event::create($eventData);

                if ($event) {
                    // Create tickets for paid events
                    if ($data['event_type'] === 'paid' && isset($data['tickets'])) {
                        $this->createEventTickets($event, $data['tickets']);
                    }

                    // Create discount if enabled
                    $promoData = $data['promo'] ?? [];
                    if (isset($promoData['enabled']) && $promoData['enabled'] === '1') {
                        $this->createEventDiscount($event, $promoData);
                    }

                    // Create event categories
                    if (!empty($data['categories'])) {
                        $this->createEventCategories($event, $data['categories']);
                    }

                    $textStatus = $isLaunch ? "launched" : "saved as pending";
                    FlashMessage::success("Event {$textStatus} successfully!");

                    // Redirect based on action
                    if ($action === 'pending') {
                        return $this->redirect("/admin/events/edit/{$event->id}");
                    }

                    return $this->redirect('/admin/events');
                }
            } catch (Exception $e) {
                // If event was created but tickets/discount failed, delete the event
                if ($event) {
                    $event->delete();
                }
                throw $e;
            }

            FlashMessage::error('Failed to create event. Please try again.');
            return $this->redirect('/admin/events/create');
        } catch (Exception $e) {
            FlashMessage::error('Error creating event: ' . $e->getMessage());
            return $this->redirect('/admin/events/create');
        }
    }

    /**
     * Display edit event form
     */
    public function edit(Request $request, $id): Response
    {
        try {
            $event = Event::with(['tickets', 'discount', 'event_types'])->find($id);

            if (!$event) {
                FlashMessage::error('Event not found');
                return $this->redirect('/admin/events');
            }

            // Get active categories from database
            $categories = EventType::where('is_active', true)
                ->orderBy('sort_order', 'ASC')
                ->orderBy('name', 'ASC')
                ->get();

            return $this->view('admin.events.edit', [
                'event' => $event,
                'categories' => $categories,
                'page_title' => 'Edit Event'
            ]);
        } catch (Exception $e) {
            FlashMessage::error('Error loading event: ' . $e->getMessage());
            return $this->redirect('/admin/events');
        }
    }

    /**
     * Update existing event
     */
    public function update(Request $request, $id): Response
    {
        try {
            $this->currentRequest = $request;

            // Find the event
            $event = Event::find($id);

            if (!$event) {
                FlashMessage::error('Event not found');
                return $this->redirect('/admin/events');
            }

            $data = $request->getParsedBody();
            $action = $data['action'] ?? 'pending';
            $isPublish = $action === 'launch';

            // Validate event data
            $errors = $this->validateEventData($request, $isPublish);

            if ($errors->any()) {
                return $this->back($errors);
            }

            // Get current user ID
            $userId = $this->getCurrentUserId($request);
            if (!$userId) {
                FlashMessage::error('You must be logged in to update an event.');
                return $this->redirect("/admin/events/edit/{$id}");
            }

            // Handle featured image
            $featuredImage = $event->event_image; // Keep existing image by default

            // Check if user wants to remove the image
            if (isset($data['remove_event_image']) && $data['remove_event_image'] == '1') {
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
            elseif (isset($_FILES['event_image']) && $_FILES['event_image']['error'] === UPLOAD_ERR_OK) {
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
                $file = UploadedFile::createFromFilesArray($_FILES['event_image']);
                try {
                    $uploadResult = $this->uploader->upload($file, null, (string)$userId);
                    $featuredImage = $uploadResult['url'];
                } catch (Exception $e) {
                    FlashMessage::error("Upload failed: " . $e->getMessage());
                    return $this->redirect("/admin/events/edit/{$id}");
                }
            }

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
            $publishedAt = $event->published_at;
            if ($publishedAt instanceof \DateTime) {
                $publishedAt = $publishedAt->format('Y-m-d H:i:s');
            }

            // If event is being published for the first time
            if ($isPublish && $event->status !== 'published' && !$publishedAt) {
                $publishedAt = date('Y-m-d H:i:s');
            }

            // Prepare update data
            $updateData = [
                'title' => trim($data['title']),
                'content' => $data['content'],
                'event_date' => $data['event_date'],
                'event_time' => $data['event_time'],
                'location' => trim($data['location']),
                'event_type' => $data['event_type'],
                'event_image' => $featuredImage,
                'status' => $isPublish ? 'launched' : 'pending',
                'seo_title' => $seoTitle,
                'seo_description' => $seoDescription,
                'seo_keywords' => $seoKeywords,
                'published_at' => $publishedAt,
            ];

            // Update the event
            $updated = $event->update($updateData);

            if ($updated) {
                // Update tickets
                if ($data['event_type'] === 'paid' && isset($data['tickets'])) {
                    $this->updateEventTickets($event, $data['tickets']);
                } else {
                    // Remove all tickets if event type changed to free
                    // $event->tickets()->delete();
                    $tickets = $event->tickets;
                    if ($tickets && $tickets->count() > 0) {
                        foreach ($tickets as $ticket) {
                            $ticket->delete();
                        }
                    }
                }

                // Update discount
                $promoData = $data['promo'] ?? [];
                $this->updateEventDiscount($event, $promoData);

                // Update categories
                if (!empty($data['categories'])) {
                    $this->updateEventCategories($event, $data['categories']);
                }

                $textStatus = $isPublish ? "published" : "saved as draft";
                FlashMessage::success("Event {$textStatus} successfully!");

                // Redirect based on action
                if ($action === 'draft') {
                    return $this->redirect("/admin/events/edit/{$event->id}");
                }

                return $this->redirect('/admin/events');
            } else {
                FlashMessage::error('Failed to update event. Please try again.');
                return $this->redirect("/admin/events/edit/{$id}");
            }
        } catch (Exception $e) {
            FlashMessage::error('Error updating event: ' . $e->getMessage());
            return $this->redirect("/admin/events/edit/{$id}");
        }
    }

    /**
     * Validate event data
     */
    private function validateEventData($request, bool $isLaunching = false): ErrorMessage
    {
        $data = $request->getParsedBody();
        $errors = new ErrorMessage();

        // Title validation
        if (empty($data['title'])) {
            $errors->add('title', 'Event title is required');
        } elseif (strlen($data['title']) > 255) {
            $errors->add('title', 'Event title must not exceed 255 characters');
        }

        // Event date validation
        if (empty($data['event_date'])) {
            $errors->add('event_date', 'Event date is required');
        } else {
            $eventDate = \DateTime::createFromFormat('Y-m-d', $data['event_date']);
            if (!$eventDate || $eventDate->format('Y-m-d') !== $data['event_date']) {
                $errors->add('event_date', 'Invalid event date format');
            }
        }

        // Event time validation
        if (empty($data['event_time'])) {
            $errors->add('event_time', 'Event time is required');
        }

        // Location validation
        if (empty($data['location'])) {
            $errors->add('location', 'Event location is required');
        }

        // Content validation (stricter for launching)
        if ($isLaunching) {
            if (empty($data['content'])) {
                $errors->add('content', 'Event description is required');
            } elseif (strlen(strip_tags($data['content'])) < 50) {
                $errors->add('content', 'Event description must be at least 50 characters');
            }
        }

        // Tickets validation for paid events when publishing
        if ($isLaunching && $data['event_type'] === 'paid') {
            if (empty($data['tickets']) || !is_array($data['tickets'])) {
                $errors->add('tickets', 'At least one ticket type is required for paid events');
            } else {
                $hasValidTicket = false;
                foreach ($data['tickets'] as $ticketId => $ticket) {
                    if (empty(trim($ticket['name'] ?? ''))) {
                        $errors->add("tickets.{$ticketId}.name", 'Ticket name is required');
                    }
                    if (!isset($ticket['price']) || $ticket['price'] === '') {
                        $errors->add("tickets.{$ticketId}.price", 'Ticket price is required');
                    } elseif ($ticket['price'] < 0) {
                        $errors->add("tickets.{$ticketId}.price", 'Ticket price cannot be negative');
                    }
                    if (!isset($ticket['quantity']) || $ticket['quantity'] === '') {
                        $errors->add("tickets.{$ticketId}.quantity", 'Ticket quantity is required');
                    } elseif ($ticket['quantity'] < 1) {
                        $errors->add("tickets.{$ticketId}.quantity", 'Ticket quantity must be at least 1');
                    }

                    // If we have at least one valid ticket, mark as having valid tickets
                    if (!empty(trim($ticket['name'] ?? '')) && isset($ticket['price']) && $ticket['price'] >= 0 && isset($ticket['quantity']) && $ticket['quantity'] >= 1) {
                        $hasValidTicket = true;
                    }
                }

                if (!$hasValidTicket) {
                    $errors->add('tickets', 'At least one valid ticket type is required for paid events');
                }
            }
        }

        // Discount validation if enabled - UPDATED FOR PROMO ARRAY
        $promoData = $data['promo'] ?? [];
        if (isset($promoData['enabled']) && $promoData['enabled'] === '1') {
            if (empty(trim($promoData['code'] ?? ''))) {
                $errors->add('promo.code', 'Promo code is required when discount is enabled');
            }
            if (empty($promoData['discount_value'] ?? '')) {
                $errors->add('promo.discount_value', 'Discount value is required when discount is enabled');
            } elseif ($promoData['discount_value'] < 0) {
                $errors->add('promo.discount_value', 'Discount value cannot be negative');
            }
            if (($promoData['discount_type'] ?? '') === 'percentage' && $promoData['discount_value'] > 100) {
                $errors->add('promo.discount_value', 'Percentage discount cannot exceed 100%');
            }
        }

        return $errors;
    }

    /**
     * Create event tickets
     */
    private function createEventTickets(Event $event, array $tickets): bool
    {
        try {
            foreach ($tickets as $ticketData) {
                EventTicket::create([
                    'event_id' => $event->id,
                    'name' => trim($ticketData['name']),
                    'price' => (float)$ticketData['price'],
                    'quantity' => (int)$ticketData['quantity'],
                    'description' => $ticketData['description'] ?? null,
                    'sale_start' => !empty($ticketData['sale_start']) ? $ticketData['sale_start'] : null,
                    'sale_end' => !empty($ticketData['sale_end']) ? $ticketData['sale_end'] : null,
                ]);
            }
            return true;
        } catch (Exception $e) {
            error_log("Failed to create event tickets: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Update event tickets - ALTERNATIVE APPROACH
     * Uses individual deletes instead of whereIn
     */
    private function updateEventTickets(Event $event, array $tickets): bool
    {
        try {
            // Get all existing ticket IDs from database
            $existingTickets = $event->tickets;
            $existingTicketIds = $existingTickets->pluck('id')->toArray();
            $updatedTicketIds = [];

            // Process each ticket from the form
            foreach ($tickets as $ticketId => $ticketData) {
                // Skip empty ticket data
                if (empty($ticketData['name']) && empty($ticketData['price']) && empty($ticketData['quantity'])) {
                    continue;
                }

                // Check if this is an existing ticket
                if (is_numeric($ticketId) && in_array((int)$ticketId, $existingTicketIds)) {
                    // Update existing ticket
                    $ticket = EventTicket::find($ticketId);
                    if ($ticket && $ticket->event_id === $event->id) {
                        $ticket->update([
                            'name' => trim($ticketData['name']),
                            'price' => (float)$ticketData['price'],
                            'quantity' => (int)$ticketData['quantity'],
                            'description' => $ticketData['description'] ?? null,
                            'sale_start' => !empty($ticketData['sale_start']) ? $ticketData['sale_start'] : null,
                            'sale_end' => !empty($ticketData['sale_end']) ? $ticketData['sale_end'] : null,
                        ]);
                        $updatedTicketIds[] = (int)$ticketId;
                    }
                } else {
                    // Create new ticket
                    $newTicket = EventTicket::create([
                        'event_id' => $event->id,
                        'name' => trim($ticketData['name']),
                        'price' => (float)$ticketData['price'],
                        'quantity' => (int)$ticketData['quantity'],
                        'description' => $ticketData['description'] ?? null,
                        'sale_start' => !empty($ticketData['sale_start']) ? $ticketData['sale_start'] : null,
                        'sale_end' => !empty($ticketData['sale_end']) ? $ticketData['sale_end'] : null,
                    ]);
                    $updatedTicketIds[] = $newTicket->id;
                }
            }

            // Delete tickets that were removed from the form
            // Use individual deletes instead of whereIn for more reliability
            $ticketsToDelete = array_diff($existingTicketIds, $updatedTicketIds);

            if (!empty($ticketsToDelete)) {
                foreach ($ticketsToDelete as $ticketIdToDelete) {
                    $ticketToDelete = EventTicket::find($ticketIdToDelete);
                    if ($ticketToDelete && $ticketToDelete->event_id === $event->id) {
                        $ticketToDelete->delete();
                        error_log("Deleted ticket ID: {$ticketIdToDelete}");
                    }
                }
            }

            return true;
        } catch (Exception $e) {
            error_log("Failed to update event tickets: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Create event discount - UPDATED FOR PROMO ARRAY
     */
    private function createEventDiscount(Event $event, array $promoData): bool
    {
        try {
            EventDiscount::create([
                'event_id' => $event->id,
                'promo_code' => strtoupper(trim($promoData['code'])),
                'discount_type' => $promoData['discount_type'],
                'discount_value' => (float)$promoData['discount_value'],
                'promo_valid_until' => !empty($promoData['valid_until']) ? $promoData['valid_until'] : null,
                'promo_usage_limit' => !empty($promoData['usage_limit']) ? (int)$promoData['usage_limit'] : null,
                'used_count' => 0,
            ]);
            return true;
        } catch (Exception $e) {
            error_log("Failed to create event discount: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Update event discount - UPDATED FOR PROMO ARRAY
     */
    private function updateEventDiscount(Event $event, array $promoData): bool
    {
        try {
            if (isset($promoData['enabled']) && $promoData['enabled'] === '1') {
                $discount = $event->discount;
                if ($discount) {
                    // Update existing discount
                    $discount->update([
                        'promo_code' => strtoupper(trim($promoData['code'])),
                        'discount_type' => $promoData['discount_type'],
                        'discount_value' => (float)$promoData['discount_value'],
                        'promo_valid_until' => !empty($promoData['valid_until']) ? $promoData['valid_until'] : null,
                        'promo_usage_limit' => !empty($promoData['usage_limit']) ? (int)$promoData['usage_limit'] : null,
                    ]);
                } else {
                    // Create new discount
                    $this->createEventDiscount($event, $promoData);
                }
            } else {
                // Remove discount if disabled
                $discount = $event->discount;
                if ($discount) {
                    $discount->delete();
                }
            }
            return true;
        } catch (Exception $e) {
            error_log("Failed to update event discount: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Create event categories
     */
    private function createEventCategories(Event $event, $category): bool
    {
        try {
            // Check if the relationship already exists to prevent duplicates
            $exists = EventCategories::where('event_id', $event->id)
                ->where('type_id', $category)
                ->exists();

            if ($exists) {
                // Relationship already exists, no need to create
                return true;
            }

            EventCategories::create([
                'event_id' => $event->id,
                'type_id' => $category
            ]);

            return true;
        } catch (Exception $e) {
            error_log("Failed to create event categories: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Update event categories
     */
    private function updateEventCategories(Event $event, $categoryId): bool
    {
        try {
            // First, check if the category relationship already exists
            $existingCategory = EventCategories::where('event_id', $event->id)->first();

            if ($existingCategory) {
                // Update the existing category
                $existingCategory->update([
                    'type_id' => $categoryId
                ]);
            } else {
                // Create new category relationship
                EventCategories::create([
                    'event_id' => $event->id,
                    'type_id' => $categoryId
                ]);
            }

            return true;
        } catch (Exception $e) {
            error_log("Failed to update event categories: " . $e->getMessage());
            return false;
        }
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
}
