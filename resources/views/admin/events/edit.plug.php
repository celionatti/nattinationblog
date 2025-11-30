@extends('layouts.admin')

@section('title', 'Admin Edit Event')

@push('styles')
<!-- Summernote CSS -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet">
<style>
    .featured-image-actions {
        display: none;
    }

    .discount-fields {
        display: none;
    }

    .discount-fields.active {
        display: block;
    }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-start mb-2">
    <div>
        <h1 class="page-title">Edit: {{ $event->title }}</h1>
        <p class="page-subtitle">Update event and engaging events for your audience.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.events.index') }}" class="btn-custom btn-secondary text-decoration-none">
            <i class="bi bi-arrow-left"></i>
            Back to Events
        </a>
    </div>
</div>

<form action="{{ route('admin.events.update', ['id' => $event->id]) }}" method="post" id="eventForm" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <!-- Hidden field to determine action -->
    <input type="hidden" name="action" id="formAction" value="pending">

    <div class="editor-container">
        <div class="editor-main">
            <!-- Title Input -->
            <div class="editor-card">
                <div class="editor-card-body title-container">
                    <div class="form-group" style="width: 100%;">
                        <textarea
                            class="form-control title-input @error('title') is-invalid @enderror"
                            name="title"
                            id="eventTitle"
                            placeholder="Event title"
                            rows="1"
                            autocomplete="off"
                            style="height: auto;">{{ old('title', $event->title) }}</textarea>
                        @error('title')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Event Details -->
            <div class="editor-card">
                <div class="editor-card-header">
                    <h2 class="editor-card-title">Event Details</h2>
                </div>
                <div class="editor-card-body">
                    <div class="form-group">
                        <label class="form-label">Event Date & Time</label>
                        <div class="datetime-group">
                            <div>
                                <input type="date" name="event_date" class="form-control @error('event_date') is-invalid @enderror" value="{{ old('event_date', $event->event_date) }}" required>
                                @error('event_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div>
                                <input type="time" name="event_time" class="form-control @error('event_time') is-invalid @enderror" value="{{ old('event_time', $event->event_time) }}" required>
                                @error('event_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Location / Venue</label>
                        <input type="text" name="location" class="form-control @error('location') is-invalid @enderror" placeholder="Enter event location or 'Online'" value="{{ old('location', $event->location) }}" required>
                        @error('location')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Summernote Editor -->
            <div class="editor-card">
                <div class="editor-card-header">
                    <h2 class="editor-card-title">Event Description</h2>
                </div>
                <div class="editor-card-body">
                    <div class="form-group">
                        <textarea
                            id="summernote"
                            name="content"
                            class="form-control @error('content') is-invalid @enderror">{{ old('content', $event->content) }}</textarea>
                        @error('content')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Event Type & Tickets -->
            <div class="editor-card">
                <div class="editor-card-header">
                    <h2 class="editor-card-title">Tickets & Pricing</h2>
                </div>
                <div class="editor-card-body">
                    <div class="form-group">
                        <label class="form-label">Event Type</label>
                        <div class="event-type-toggle">
                            <button type="button" class="event-type-option {{ $event->event_type === 'paid' ? 'active' : '' }}" data-type="paid">
                                <i class="bi bi-ticket-perforated"></i> Paid
                            </button>
                            <button type="button" class="event-type-option {{ $event->event_type === 'free' ? 'active' : '' }}" data-type="free">
                                <i class="bi bi-gift"></i> Free
                            </button>
                        </div>
                        <input type="hidden" name="event_type" id="eventType" value="{{ $event->event_type }}">
                    </div>

                    <!-- Tickets Container (Hidden for Free Events) -->
                    <div id="ticketsContainer" style="margin-top: 24px; <?= $event->event_type === 'free' ? 'display: none;' : '' ?>">
                        <div class="form-label" style="margin-bottom: 16px;">Ticket Types</div>

                        <div id="ticketsList">
                            <!-- Tickets will be added here dynamically -->
                            @if($event->tickets)
                            @foreach($event->tickets as $index => $ticket)
                            <div class="ticket-item" data-ticket-id="{{ $ticket->id }}">
                                <div class="ticket-header">
                                    <span class="ticket-number">Ticket #{{ $index + 1 }}</span>
                                    <button type="button" class="btn-custom btn-danger remove-ticket-btn" onclick="removeTicket(<?= $ticket->id ?>)">
                                        <i class="bi bi-trash"></i> Remove
                                    </button>
                                </div>
                                <div class="ticket-fields">
                                    <div class="form-group">
                                        <label class="form-label">Ticket Name *</label>
                                        <input type="text" name="tickets[{{ $ticket->id }}][name]" class="form-control" value="{{ $ticket->name }}" placeholder="e.g., Early Bird, VIP, General Admission" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Price *</label>
                                        <input type="number" name="tickets[{{ $ticket->id }}][price]" class="form-control" value="{{ $ticket->price }}" placeholder="0.00" min="0" step="0.01" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Quantity Available *</label>
                                        <input type="number" name="tickets[{{ $ticket->id }}][quantity]" class="form-control" value="{{ $ticket->quantity }}" placeholder="e.g., 100" min="1" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Sale Start Date</label>
                                        <input type="date" name="tickets[{{ $ticket->id }}][sale_start]" class="form-control" value="{{ $ticket->sale_start }}">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Sale End Date</label>
                                        <input type="date" name="tickets[{{ $ticket->id }}][sale_end]" class="form-control" value="{{ $ticket->sale_end }}">
                                    </div>
                                    <div class="form-group ticket-field-full">
                                        <label class="form-label">Description</label>
                                        <textarea name="tickets[{{ $ticket->id }}][description]" class="form-control" rows="2" placeholder="What's included with this ticket type?">{{ $ticket->description }}</textarea>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            @endif
                        </div>

                        <button type="button" class="add-ticket-btn" id="addTicketBtn">
                            <i class="bi bi-plus-circle"></i>
                            Add Ticket Type
                        </button>

                        <div class="tickets-empty-state" id="ticketsEmptyState" style="<?= ($event->tickets && $event->tickets->count() > 0) ? 'display: none;' : '' ?>">
                            <i class="bi bi-ticket-perforated"></i>
                            <p style="margin: 8px 0 0 0; font-weight: 500;">No tickets added yet</p>
                            <p style="margin: 4px 0 0 0; font-size: 13px;">Click "Add Ticket Type" to create your first ticket</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Discount/Promo Section -->
            <div class="editor-card" id="discountSection" style="<?= $event->event_type === 'free' ? 'display: none;' : '' ?>">
                <div class="editor-card-header">
                    <h2 class="editor-card-title">Promotions & Discounts</h2>
                </div>
                <div class="editor-card-body">
                    <div class="discount-toggle">
                        <label class="switch">
                            <input type="checkbox" id="enableDiscount" name="promo[enabled]" value="1" {{ $event->discount ? 'checked' : '' }}>
                            <span class="slider"></span>
                        </label>
                        <label for="enableDiscount" style="cursor: pointer; font-weight: 600; font-size: 14px;">Enable Promo Code</label>
                    </div>

                    <div class="discount-fields {{ $event->discount ? 'active' : '' }}" id="discountFields">
                        <div class="form-group">
                            <label class="form-label">Promo Code</label>
                            <input type="text" name="promo[code]" class="form-control" placeholder="e.g., EARLY2024" style="text-transform: uppercase;" value="{{ $event->discount ? $event->discount->promo_code : '' }}">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Discount Type</label>
                            <select name="promo[discount_type]" class="form-select" id="discountType">
                                <option value="percentage" {{ $event->discount && $event->discount->discount_type === 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                                <option value="fixed" {{ $event->discount && $event->discount->discount_type === 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Discount Value</label>
                            <input type="number" name="promo[discount_value]" class="form-control" placeholder="e.g., 10" min="0" step="0.01" value="{{ $event->discount ? $event->discount->discount_value : '' }}">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Valid Until (Optional)</label>
                            <input type="date" name="promo[valid_until]" class="form-control" value="{{ $event->discount ? $event->discount->promo_valid_until : '' }}">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Usage Limit (Optional)</label>
                            <input type="number" name="promo[usage_limit]" class="form-control" placeholder="Leave empty for unlimited" min="1" value="{{ $event->discount ? $event->discount->promo_usage_limit : '' }}">
                        </div>
                    </div>
                </div>
            </div>

            <!-- SEO Fields (Hidden but included for form submission) -->
            <input type="hidden" name="seo_title" value="{{ $event->seo_title }}">
            <input type="hidden" name="seo_description" value="{{ $event->seo_description }}">
            <input type="hidden" name="seo_keywords" value="{{ $event->seo_keywords }}">
        </div>

        <!-- Editor Sidebar -->
        <div class="editor-sidebar">
            <!-- Publish Controls -->
            <div class="editor-card">
                <div class="editor-card-header">
                    <h2 class="editor-card-title">Publish</h2>
                </div>
                <div class="editor-card-body">
                    <div class="form-group">
                        @php
                        $statusClass = 'status-draft';
                        $statusText = 'Cancelled';
                        if ($event->status === 'launched') {
                        $statusClass = 'status-published';
                        $statusText = 'Launched';
                        } elseif ($event->status === 'pending') {
                        $statusClass = 'status-pending';
                        $statusText = 'Pending';
                        }
                        @endphp
                        <div class="form-label">Status: <span class="status-badge {{ $statusClass }}">{{ $statusText }}</span></div>
                        <div class="form-text">Ready to launch</div>
                    </div>

                    <div class="publish-actions">
                        <button type="button" class="btn-custom btn-secondary" id="savePending">
                            <i class="bi bi-file-earmark"></i>
                            Save Pending
                        </button>
                        <button type="button" class="btn-custom btn-primary" id="launchEvent">
                            <i class="bi bi-send"></i>
                            Launch
                        </button>
                    </div>
                </div>
            </div>

            <!-- Categories Dropdown -->
            <div class="editor-card">
                <div class="editor-card-header">
                    <h2 class="editor-card-title">Category</h2>
                </div>
                <div class="editor-card-body">
                    <div class="form-group">
                        @if($categories)
                        <select
                            class="form-select @error('categories') is-invalid @enderror"
                            name="categories"
                            id="categoriesSelect">
                            <option value="">Select a category</option>
                            @foreach ($categories as $category)
                            <option
                                value="{{ $category->id }}"
                                {{ old('categories', $event->event_types->first()->id ?? '') === $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                            @endforeach
                        </select>
                        @else
                        <p class="text-muted">No categories available</p>
                        @endif
                        @error('categories')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Featured Image -->
            <div class="editor-card">
                <div class="editor-card-header">
                    <h2 class="editor-card-title">Event Cover Image</h2>
                </div>
                <div class="editor-card-body">
                    <input type="file" name="event_image" id="featuredImageInput" accept="image/*" style="display: none;">
                    <input type="hidden" name="remove_event_image" id="removeEventImage" value="0">
                    <div class="featured-image-container" id="featuredImageContainer">
                        @if($event->event_image)
                        <img src="{{ $event->event_image }}" class="featured-image-preview" id="featuredImagePreview" alt="Featured Image" style="display: block;">
                        <div class="featured-image-placeholder" style="display: none;">
                            <i class="bi bi-image"></i>
                            <p style="margin: 8px 0 4px 0; font-weight: 600;">Set cover image</p>
                            <p class="text-muted" style="margin: 0; font-size: 12px;">Click to upload or drag and drop</p>
                        </div>
                        @else
                        <div class="featured-image-placeholder">
                            <i class="bi bi-image"></i>
                            <p style="margin: 8px 0 4px 0; font-weight: 600;">Set cover image</p>
                            <p class="text-muted" style="margin: 0; font-size: 12px;">Click to upload or drag and drop</p>
                        </div>
                        <img src="" class="featured-image-preview" id="featuredImagePreview" alt="Featured Image" style="display: none;">
                        @endif
                    </div>

                    <div class="featured-image-actions" id="featuredImageActions" style="<?= $event->event_image ? 'display: flex;' : 'display: none;' ?>">
                        <button type="button" class="btn-custom btn-secondary" id="changeImage">
                            <i class="bi bi-arrow-repeat"></i>
                            Change
                        </button>
                        <button type="button" class="btn-custom btn-secondary" id="removeImage">
                            <i class="bi bi-trash"></i>
                            Remove
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Summernote JS -->
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js"></script>

<script>
    let ticketCounter = 0;

    // ========== SUMMERNOTE INITIALIZATION ==========
    $(document).ready(function() {
        $('#summernote').summernote({
            height: 400,
            placeholder: 'Describe your event, what attendees can expect, schedule, speakers, etc...',
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ],
            callbacks: {
                onChange: function(contents, $editable) {
                    $('#summernote').val(contents);
                },
                onInit: function() {
                    $('#summernote').val($('#summernote').summernote('code'));
                }
            }
        });

        // ========== EVENT TYPE TOGGLE ==========
        $('.event-type-option').on('click', function() {
            $('.event-type-option').removeClass('active');
            $(this).addClass('active');

            const eventType = $(this).data('type');
            $('#eventType').val(eventType);

            if (eventType === 'free') {
                $('#ticketsContainer').hide();
                $('#discountSection').hide();
            } else {
                $('#ticketsContainer').show();
                $('#discountSection').show();
            }
        });

        // ========== ADD TICKET FUNCTIONALITY ==========
        $('#addTicketBtn').on('click', function() {
            addTicket();
        });

        // ========== DISCOUNT TOGGLE ==========
        $('#enableDiscount').on('change', function() {
            if ($(this).is(':checked')) {
                $('#discountFields').addClass('active');
            } else {
                $('#discountFields').removeClass('active');
                // Clear discount fields
                $('#discountFields input, #discountFields select').val('');
                $('#enableDiscount').prop('checked', false);
            }
        });

        // ========== FORM SUBMISSION HANDLING ==========
        $('#eventForm').on('submit', function(e) {
            // Ensure Summernote content is synced
            const summernoteContent = $('#summernote').summernote('code');
            $('#summernote').val(summernoteContent);

            // Validation
            const title = $('#eventTitle').val().trim();
            const action = $('#formAction').val();
            const eventType = $('#eventType').val();

            if (!title) {
                e.preventDefault();
                alert('Please add a title to your event.');
                return false;
            }

            // Check tickets for paid events when publishing
            if (action === 'launch' && eventType === 'paid') {
                const ticketCount = $('#ticketsList .ticket-item').length;

                if (ticketCount === 0) {
                    e.preventDefault();
                    alert('Please add at least one ticket type for paid events, or change the event type to Free.');
                    return false;
                }

                // Validate each ticket
                let isValid = true;
                $('#ticketsList .ticket-item').each(function() {
                    const ticketName = $(this).find('input[name^="tickets"][name$="[name]"]').val().trim();
                    const ticketPrice = $(this).find('input[name^="tickets"][name$="[price]"]').val();
                    const ticketQuantity = $(this).find('input[name^="tickets"][name$="[quantity]"]').val();

                    if (!ticketName || !ticketPrice || !ticketQuantity) {
                        isValid = false;
                        return false;
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                    alert('Please fill in all required ticket fields (Name, Price, Quantity).');
                    return false;
                }
            }

            // Validate promo fields if enabled
            if ($('#enableDiscount').is(':checked')) {
                const promoCode = $('input[name="promo[code]"]').val().trim();
                const discountValue = $('input[name="promo[discount_value]"]').val();

                if (!promoCode) {
                    e.preventDefault();
                    alert('Please enter a promo code.');
                    return false;
                }

                if (!discountValue) {
                    e.preventDefault();
                    alert('Please enter a discount value.');
                    return false;
                }
            }

            // Validate content for publishing
            if (action === 'launch') {
                const contentText = $(summernoteContent).text().trim();

                if (!summernoteContent || contentText.length < 50) {
                    e.preventDefault();
                    alert('Please add at least 50 characters of content before publishing.');
                    return false;
                }
            }

            // If validation passes, form will submit normally to PHP controller
            return true;
        });

        // ========== SAVE DRAFT BUTTON ==========
        $('#savePending').on('click', function() {
            $('#formAction').val('pending');
            $('#eventForm').submit();
        });

        // ========== PUBLISH BUTTON ==========
        $('#launchEvent').on('click', function() {
            $('#formAction').val('launch');
            $('#eventForm').submit();
        });

        // ========== AUTO-RESIZE TITLE TEXTAREA ==========
        const titleTextarea = document.getElementById('eventTitle');
        if (titleTextarea) {
            titleTextarea.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = (this.scrollHeight) + 'px';
            });

            titleTextarea.style.height = 'auto';
            titleTextarea.style.height = (titleTextarea.scrollHeight) + 'px';
        }
    });

    // ========== ADD TICKET FUNCTION ==========
    function addTicket() {
        const timestamp = Date.now();
        const random = Math.floor(Math.random() * 1000);
        const ticketId = 'new_' + timestamp + '_' + random;

        const ticketHTML = `
        <div class="ticket-item" data-ticket-id="${ticketId}">
            <div class="ticket-header">
                <span class="ticket-number">New Ticket</span>
                <button type="button" class="btn-custom btn-danger remove-ticket-btn" onclick="removeTicket('${ticketId}')">
                    <i class="bi bi-trash"></i> Remove
                </button>
            </div>
            <div class="ticket-fields">
                <div class="form-group">
                    <label class="form-label">Ticket Name *</label>
                    <input type="text" name="tickets[${ticketId}][name]" class="form-control" placeholder="e.g., Early Bird, VIP, General Admission" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Price *</label>
                    <input type="number" name="tickets[${ticketId}][price]" class="form-control" placeholder="0.00" min="0" step="0.01" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Quantity Available *</label>
                    <input type="number" name="tickets[${ticketId}][quantity]" class="form-control" placeholder="e.g., 100" min="1" required>
                </div>
                <!-- ... rest of ticket fields ... -->
            </div>
        </div>
    `;

        $('#ticketsList').append(ticketHTML);
        $('#ticketsEmptyState').hide();
        updateTicketNumbers();
    }

    // ========== REMOVE TICKET FUNCTION ==========
    function removeTicket(ticketId) {
        if (confirm('Are you sure you want to remove this ticket type?')) {
            $(`.ticket-item[data-ticket-id="${ticketId}"]`).remove();
            updateTicketNumbers();

            if ($('#ticketsList .ticket-item').length === 0) {
                $('#ticketsEmptyState').show();
            }
        }
    }

    // ========== UPDATE TICKET NUMBERS ==========
    function updateTicketNumbers() {
        $('#ticketsList .ticket-item').each(function(index) {
            $(this).find('.ticket-number').text(`Ticket #${index + 1}`);
        });
    }

    // ========== FEATURED IMAGE HANDLING ==========
    const featuredImageInput = document.getElementById('featuredImageInput');
    const featuredImageContainer = document.getElementById('featuredImageContainer');
    const featuredImagePreview = document.getElementById('featuredImagePreview');
    const featuredImageActions = document.getElementById('featuredImageActions');
    const changeImageBtn = document.getElementById('changeImage');
    const removeImageBtn = document.getElementById('removeImage');
    const removeEventImageInput = document.getElementById('removeEventImage');

    if (featuredImageContainer) {
        featuredImageContainer.addEventListener('click', function() {
            featuredImageInput.click();
        });
    }

    if (changeImageBtn) {
        changeImageBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            featuredImageInput.click();
        });
    }

    if (removeImageBtn) {
        removeImageBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            featuredImagePreview.src = '';
            featuredImagePreview.style.display = 'none';
            featuredImageActions.style.display = 'none';
            featuredImageInput.value = '';
            removeEventImageInput.value = '1';
            featuredImageContainer.querySelector('.featured-image-placeholder').style.display = 'flex';
        });
    }

    if (featuredImageInput) {
        featuredImageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Client-side validation
                const maxSize = 5 * 1024 * 1024; // 5MB
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];

                if (file.size > maxSize) {
                    alert('Image file size must be less than 5MB.');
                    this.value = '';
                    return;
                }

                if (!allowedTypes.includes(file.type)) {
                    alert('Only JPG, PNG, GIF, and WebP images are allowed.');
                    this.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    featuredImagePreview.src = e.target.result;
                    featuredImagePreview.style.display = 'block';
                    featuredImageActions.style.display = 'flex';
                    removeEventImageInput.value = '0';
                    featuredImageContainer.querySelector('.featured-image-placeholder').style.display = 'none';
                }
                reader.readAsDataURL(file);
            }
        });
    }
</script>
@endpush