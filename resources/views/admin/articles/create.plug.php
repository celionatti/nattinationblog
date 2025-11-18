@extends('layouts.admin')

@section('title', 'Admin Articles Create')

@push('styles')
<!-- Summernote CSS -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet">

<style>
    /* ========== FORM VALIDATION STYLES ========== */
    .is-invalid {
        border-color: var(--danger) !important;
        box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.1) !important;
    }

    .error-message {
        font-size: 0.85rem;
        font-weight: 500;
    }

    .form-control.is-invalid:focus {
        border-color: var(--danger);
        box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.25);
    }

    .category-badge {
        display: inline-block;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        margin-right: 6px;
    }

    .form-check {
        padding: 6px 0;
    }

    .form-check-label {
        display: flex;
        align-items: center;
        cursor: pointer;
        user-select: none;
    }

    .form-check-input:checked~.form-check-label {
        font-weight: 500;
    }
</style>
@endpush

@section('content')
<h1 class="page-title">Create New Post</h1>
<p class="page-subtitle">Write and publish engaging content for your audience.</p>

<div class="editor-container">
    <!-- Main Editor Area -->
    <div class="editor-main">
        <!-- Title Input -->
        <div class="editor-card">
            <div class="editor-card-body">
                <div class="form-group">
                    <input type="text" class="form-control" id="postTitle" placeholder="Add title"
                        style="font-size: 1.5rem; font-weight: 700; border: none; padding: 0; background: transparent;"
                        autocomplete="off">
                </div>
            </div>
        </div>

        <!-- Summernote Editor -->
        <div class="editor-card">
            <div class="editor-card-body">
                <div class="form-group">
                    <textarea id="summernote" class="form-control"></textarea>
                </div>
            </div>
        </div>

        <!-- Excerpt -->
        <div class="editor-card">
            <div class="editor-card-header">
                <h2 class="editor-card-title">Excerpt</h2>
            </div>
            <div class="editor-card-body">
                <div class="form-group">
                    <textarea class="form-control" id="postExcerpt" rows="3"
                        placeholder="Write a brief excerpt that will appear in post previews..."></textarea>
                    <div class="form-text">An optional hand-crafted summary of your post.</div>
                </div>
            </div>
        </div>
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
                    <div class="form-label">Status: <span class="status-badge status-draft">Draft</span></div>
                    <div class="form-text">Saved: Just now</div>
                </div>

                <div class="publish-actions">
                    <button class="btn-custom btn-secondary" id="saveDraft">
                        <i class="bi bi-file-earmark"></i>
                        Save Draft
                    </button>
                    <button class="btn-custom btn-primary" id="publishPost">
                        <i class="bi bi-send"></i>
                        Publish
                    </button>
                </div>
            </div>
        </div>

        <!-- Categories -->
        <div class="editor-card">
            <div class="editor-card-header">
                <h2 class="editor-card-title">Categories</h2>
            </div>
            <div class="editor-card-body">
                <div class="form-group">
                    <?php if (!empty($categories) && $categories->count() > 0): ?>
                        <?php foreach ($categories as $category): ?>
                            <div class="form-check">
                                <input class="form-check-input category-checkbox" type="checkbox"
                                    id="category{{ $category->id }}" data-category-id="{{ $category->id }}"
                                    value="{{ $category->id }}">
                                <label class="form-check-label" for="category{{ $category->id }}">
                                    <?php if ($category->color): ?>
                                        <span class="category-badge" style="background-color: {{ $category->color }}"></span>
                                    <?php endif; ?>
                                    {{ $category->name }}

                                    <?php if ($category->parent_id): ?>
                                        <small class="text-muted">(Subcategory)</small>
                                    <?php endif; ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted">No categories available</p>
                    <?php endif; ?>

                    <button class="btn-custom btn-secondary" style="margin-top: 0.5rem; width: 100%;" type="button"
                        onclick="alert('Category management coming soon!')">
                        <i class="bi bi-plus"></i>
                        Add New Category
                    </button>
                </div>
            </div>
        </div>

        <!-- Featured Image -->
        <div class="editor-card">
            <div class="editor-card-header">
                <h2 class="editor-card-title">Featured Image</h2>
            </div>
            <div class="editor-card-body">
                <div class="featured-image-container" id="featuredImageContainer">
                    <div class="featured-image-placeholder">
                        <i class="bi bi-image"></i>
                        <p>Set featured image</p>
                        <p class="text-muted">Click to upload or drag and drop</p>
                    </div>
                    <img src="" class="featured-image-preview" id="featuredImagePreview" alt="Featured Image">
                </div>
                <div class="featured-image-actions" id="featuredImageActions" style="display: none;">
                    <button class="btn-custom btn-secondary" id="changeImage">
                        <i class="bi bi-arrow-repeat"></i>
                        Change
                    </button>
                    <button class="btn-custom btn-secondary" id="removeImage">
                        <i class="bi bi-trash"></i>
                        Remove
                    </button>
                </div>
            </div>
        </div>

        <!-- SEO Settings -->
        <div class="editor-card">
            <div class="editor-card-header">
                <h2 class="editor-card-title">SEO Settings</h2>
            </div>
            <div class="editor-card-body">
                <div class="form-group">
                    <label for="seoTitle" class="form-label">SEO Title</label>
                    <input type="text" class="form-control" id="seoTitle" placeholder="SEO optimized title">
                    <div class="form-text">Recommended: 50-60 characters</div>
                </div>
                <div class="form-group">
                    <label for="seoDescription" class="form-label">Meta Description</label>
                    <textarea class="form-control" id="seoDescription" rows="3"
                        placeholder="Write a compelling meta description"></textarea>
                    <div class="form-text">Recommended: 150-160 characters</div>
                </div>
                <div class="seo-preview">
                    <div class="seo-title" id="seoPreviewTitle">Your SEO title will appear here</div>
                    <div class="seo-url">https://blogname.com/your-post-url</div>
                    <div class="seo-description" id="seoPreviewDescription">Your meta description will appear here in
                        search results.</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Summernote JS -->
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js"></script>
<script>
    // ========== SUMMERNOTE INITIALIZATION ==========
    $(document).ready(function () {
        $('#summernote').summernote({
            height: 400,
            placeholder: 'Start writing your post...',
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
                onChange: function (contents, $editable) {
                    // Trigger auto-save on content change
                    startAutoSave();
                }
            }
        });
    });

    // ========== FEATURED IMAGE HANDLING ==========
    const featuredImageContainer = document.getElementById('featuredImageContainer');
    const featuredImagePreview = document.getElementById('featuredImagePreview');
    const featuredImageActions = document.getElementById('featuredImageActions');
    const changeImageBtn = document.getElementById('changeImage');
    const removeImageBtn = document.getElementById('removeImage');

    // Click handler for image container
    featuredImageContainer.addEventListener('click', function (e) {
        // Don't trigger if clicking action buttons
        if (e.target.closest('.featured-image-actions')) {
            return;
        }
        uploadFeaturedImage();
    });

    // Change image button
    if (changeImageBtn) {
        changeImageBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            uploadFeaturedImage();
        });
    }

    // Remove image button
    if (removeImageBtn) {
        removeImageBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            removeFeaturedImage();
        });
    }

    /**
     * Upload featured image
     */
    function uploadFeaturedImage() {
        // Create file input
        const fileInput = document.createElement('input');
        fileInput.type = 'file';
        fileInput.accept = 'image/jpeg,image/jpg,image/png,image/gif,image/webp';

        fileInput.onchange = async (e) => {
            const file = e.target.files[0];
            if (!file) return;

            // Show loading state
            showImageUploadLoading();

            const formData = new FormData();
            formData.append('featured_image', file);

            try {
                const response = await fetch('/admin/articles/upload-image', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    }
                });

                const result = await response.json();

                if (result.success) {
                    // Update preview
                    featuredImagePreview.src = result.file_path;
                    featuredImagePreview.style.display = 'block';
                    featuredImageContainer.querySelector('.featured-image-placeholder').style.display = 'none';
                    featuredImageActions.style.display = 'flex';

                    // Show success notification
                    createArticleForm.showSuccessMessage('Image uploaded successfully!');
                } else {
                    createArticleForm.showErrorMessage('Upload failed: ' + result.message);
                    hideImageUploadLoading();
                }
            } catch (error) {
                console.error('Error uploading image:', error);
                createArticleForm.showErrorMessage('Error uploading image. Please try again.');
                hideImageUploadLoading();
            }
        };

        fileInput.click();
    }

    /**
     * Remove featured image
     */
    function removeFeaturedImage() {
        if (confirm('Are you sure you want to remove the featured image?')) {
            featuredImagePreview.src = '';
            featuredImagePreview.style.display = 'none';
            featuredImageContainer.querySelector('.featured-image-placeholder').style.display = 'block';
            featuredImageActions.style.display = 'none';
        }
    }

    /**
     * Show loading state for image upload
     */
    function showImageUploadLoading() {
        const placeholder = featuredImageContainer.querySelector('.featured-image-placeholder');
        if (placeholder) {
            placeholder.innerHTML = '<i class="bi bi-hourglass-split"></i><p>Uploading...</p>';
        }
    }

    /**
     * Hide loading state for image upload
     */
    function hideImageUploadLoading() {
        const placeholder = featuredImageContainer.querySelector('.featured-image-placeholder');
        if (placeholder && placeholder.style.display !== 'none') {
            placeholder.innerHTML = `
            <i class="bi bi-image"></i>
            <p>Set featured image</p>
            <p class="text-muted">Click to upload or drag and drop</p>
        `;
        }
    }

    // ========== SEO PREVIEW ==========
    const seoTitle = document.getElementById('seoTitle');
    const seoDescription = document.getElementById('seoDescription');
    const seoPreviewTitle = document.getElementById('seoPreviewTitle');
    const seoPreviewDescription = document.getElementById('seoPreviewDescription');
    const postTitle = document.getElementById('postTitle');
    const postExcerpt = document.getElementById('postExcerpt');

    /**
     * Update SEO preview in real-time
     */
    function updateSeoPreview() {
        // Update SEO title preview
        const titleText = seoTitle.value || postTitle.value || 'Your SEO title will appear here';
        seoPreviewTitle.textContent = titleText;

        // Update SEO description preview
        const descText = seoDescription.value || postExcerpt.value || 'Your meta description will appear here in search results.';
        seoPreviewDescription.textContent = descText;

        // Add character count indicators
        updateCharacterCount(seoTitle, 60, 'seo-title-count');
        updateCharacterCount(seoDescription, 160, 'seo-description-count');
    }

    /**
     * Update character count helper
     */
    function updateCharacterCount(element, maxLength, counterId) {
        const currentLength = element.value.length;
        let countElement = document.getElementById(counterId);

        if (!countElement) {
            countElement = document.createElement('small');
            countElement.id = counterId;
            countElement.className = 'character-count';
            element.parentNode.appendChild(countElement);
        }

        countElement.textContent = `${currentLength}/${maxLength}`;
        countElement.style.color = currentLength > maxLength ? '#ef4444' : '#6b7280';
    }

    // Attach event listeners for SEO preview
    if (seoTitle) seoTitle.addEventListener('input', updateSeoPreview);
    if (seoDescription) seoDescription.addEventListener('input', updateSeoPreview);
    if (postTitle) postTitle.addEventListener('input', updateSeoPreview);
    if (postExcerpt) postExcerpt.addEventListener('input', updateSeoPreview);

    // Initialize SEO preview
    updateSeoPreview();

    // ========== FORM SUBMISSION HANDLER ==========
    const saveDraftBtn = document.getElementById('saveDraft');
    const publishBtn = document.getElementById('publishPost');

    const createArticleForm = {
        isSubmitting: false,

        init() {
            this.bindEvents();
            this.initializeStyles();
        },

        bindEvents() {
            // Save Draft button
            if (saveDraftBtn) {
                saveDraftBtn.addEventListener('click', () => this.saveDraft());
            }

            // Publish Post button
            if (publishBtn) {
                publishBtn.addEventListener('click', () => this.publishPost());
            }

            // Prevent double submission
            document.addEventListener('keydown', (e) => {
                // Ctrl/Cmd + S to save draft
                if ((e.ctrlKey || e.metaKey) && e.key === 's') {
                    e.preventDefault();
                    this.saveDraft();
                }
            });
        },

        /**
         * Save article as draft
         */
        async saveDraft() {
            if (this.isSubmitting) {
                return;
            }

            const formData = this.getFormData();

            // Basic validation
            if (!formData.title) {
                this.showErrorMessage('Please add a title before saving.');
                postTitle.focus();
                return;
            }

            this.isSubmitting = true;
            this.setButtonLoading(saveDraftBtn, true);

            try {
                const response = await fetch('/admin/articles/save-draft', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    },
                    body: JSON.stringify(formData)
                });

                const result = await response.json();

                if (result.success) {
                    this.showSuccessMessage('Draft saved successfully!');

                    // Store draft ID for future saves
                    if (result.draft_id && !formData.article_id) {
                        this.setArticleId(result.draft_id);
                    }

                    // Update URL if slug is returned
                    if (result.slug) {
                        this.updateBrowserUrl(result.draft_id, result.slug);
                    }
                } else {
                    this.showErrorMessage(result.message || 'Failed to save draft');
                    this.displayErrors(result.errors);
                }
            } catch (error) {
                console.error('Error saving draft:', error);
                this.showErrorMessage('Error saving draft. Please try again.');
            } finally {
                this.isSubmitting = false;
                this.setButtonLoading(saveDraftBtn, false);
            }
        },

        /**
         * Publish article
         */
        async publishPost() {
            if (this.isSubmitting) {
                return;
            }

            const formData = this.getFormData();

            // Validation
            const validationErrors = this.validatePublishData(formData);
            if (Object.keys(validationErrors).length > 0) {
                this.displayErrors(validationErrors);
                this.showErrorMessage('Please fix the validation errors before publishing.');
                return;
            }

            if (!confirm('Are you ready to publish this post?')) {
                return;
            }

            this.isSubmitting = true;
            this.setButtonLoading(publishBtn, true);

            try {
                const response = await fetch('/admin/articles/create', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    },
                    body: JSON.stringify(formData)
                });

                const result = await response.json();

                if (result.success) {
                    this.showSuccessMessage('Post published successfully!');

                    // Redirect after short delay
                    setTimeout(() => {
                        window.location.href = result.redirect_url || '/admin/articles';
                    }, 1500);
                } else {
                    this.showErrorMessage(result.message || 'Failed to publish post');
                    this.displayErrors(result.errors);
                    this.isSubmitting = false;
                    this.setButtonLoading(publishBtn, false);
                }
            } catch (error) {
                console.error('Error publishing post:', error);
                this.showErrorMessage('Error publishing post. Please try again.');
                this.isSubmitting = false;
                this.setButtonLoading(publishBtn, false);
            }
        },

        /**
         * Get form data
         */
        getFormData() {
            // Get article ID if editing existing draft
            const articleIdInput = document.getElementById('article_id');
            const articleId = articleIdInput ? articleIdInput.value : null;

            return {
                article_id: articleId,
                title: postTitle.value.trim(),
                content: $('#summernote').summernote('code'),
                excerpt: postExcerpt.value.trim(),
                seo_title: seoTitle.value.trim(),
                seo_description: seoDescription.value.trim(),
                seo_keywords: this.getSeoKeywords(),
                featured_image: featuredImagePreview.src || '',
                categories: this.getSelectedCategories()
            };
        },

        /**
         * Get selected category IDs
         */
        getSelectedCategories() {
            const categories = [];
            const checkboxes = document.querySelectorAll('.category-checkbox:checked');

            checkboxes.forEach(checkbox => {
                const categoryId = checkbox.getAttribute('data-category-id');
                if (categoryId) {
                    categories.push(parseInt(categoryId, 10));
                }
            });

            return categories;
        },

        /**
         * Get SEO keywords (if field exists)
         */
        getSeoKeywords() {
            const keywordsInput = document.getElementById('seoKeywords');
            return keywordsInput ? keywordsInput.value.trim() : '';
        },

        /**
         * Validate data before publishing
         */
        validatePublishData(data) {
            const errors = {};

            // Title validation
            if (!data.title) {
                errors.title = 'Title is required';
            } else if (data.title.length > 255) {
                errors.title = 'Title must not exceed 255 characters';
            }

            // Content validation
            const textContent = this.stripHtml(data.content);
            if (!textContent || textContent.length < 50) {
                errors.content = 'Content must be at least 50 characters';
            }

            // SEO title validation
            if (data.seo_title && data.seo_title.length > 60) {
                errors.seo_title = 'SEO title should not exceed 60 characters';
            }

            // SEO description validation
            if (data.seo_description && data.seo_description.length > 160) {
                errors.seo_description = 'SEO description should not exceed 160 characters';
            }

            return errors;
        },

        /**
         * Strip HTML tags from content
         */
        stripHtml(html) {
            const tmp = document.createElement('div');
            tmp.innerHTML = html;
            return tmp.textContent || tmp.innerText || '';
        },

        /**
         * Display validation errors
         */
        displayErrors(errors) {
            if (!errors || Object.keys(errors).length === 0) {
                return;
            }

            // Clear previous errors
            document.querySelectorAll('.error-message').forEach(el => el.remove());
            document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

            // Display new errors
            Object.keys(errors).forEach(field => {
                const input = this.getInputElement(field);

                if (input) {
                    input.classList.add('is-invalid');

                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'error-message text-danger mt-1';
                    errorDiv.textContent = errors[field];

                    // Insert after the input's parent
                    if (input.parentNode) {
                        input.parentNode.appendChild(errorDiv);
                    }
                }
            });

            // Scroll to first error
            const firstError = document.querySelector('.is-invalid');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        },

        /**
         * Get input element by field name
         */
        getInputElement(field) {
            // Try multiple ways to find the input
            const selectors = [
                `#${field}`,
                `#post${field.charAt(0).toUpperCase() + field.slice(1)}`,
                `[name="${field}"]`
            ];

            for (const selector of selectors) {
                const element = document.querySelector(selector);
                if (element) return element;
            }

            // Special case for summernote
            if (field === 'content') {
                return document.querySelector('.note-editable');
            }

            return null;
        },

        /**
         * Set article ID for draft updates
         */
        setArticleId(articleId) {
            let articleIdInput = document.getElementById('article_id');
            if (!articleIdInput) {
                articleIdInput = document.createElement('input');
                articleIdInput.type = 'hidden';
                articleIdInput.id = 'article_id';
                articleIdInput.name = 'article_id';
                document.body.appendChild(articleIdInput);
            }
            articleIdInput.value = articleId;
        },

        /**
         * Update browser URL without reload
         */
        updateBrowserUrl(articleId, slug) {
            if (window.history && window.history.pushState) {
                const newUrl = `/admin/articles/edit/${slug}/${articleId}`;
                window.history.pushState({ path: newUrl }, '', newUrl);
            }
        },

        /**
         * Set button loading state
         */
        setButtonLoading(button, isLoading) {
            if (!button) return;

            if (isLoading) {
                button.disabled = true;
                button.dataset.originalText = button.innerHTML;
                button.innerHTML = '<i class="bi bi-hourglass-split"></i> Processing...';
            } else {
                button.disabled = false;
                if (button.dataset.originalText) {
                    button.innerHTML = button.dataset.originalText;
                }
            }
        },

        /**
         * Show success message
         */
        showSuccessMessage(message) {
            this.showNotification(message, 'success');
        },

        /**
         * Show error message
         */
        showErrorMessage(message) {
            this.showNotification(message, 'error');
        },

        /**
         * Show notification toast
         */
        showNotification(message, type = 'info') {
            // Remove existing notification if any
            const existing = document.querySelector('.notification-toast');
            if (existing) {
                existing.remove();
            }

            // Create notification element
            const notification = document.createElement('div');
            notification.className = `notification-toast notification-${type}`;
            notification.innerHTML = `
            <div class="notification-content">
                <span class="notification-icon">
                    ${type === 'success' ? '✓' : type === 'error' ? '✕' : 'ℹ'}
                </span>
                <span class="notification-message">${message}</span>
                <button class="notification-close" onclick="this.parentElement.parentElement.remove()">
                    ×
                </button>
            </div>
        `;

            // Add to page
            document.body.appendChild(notification);

            // Auto-remove after 5 seconds
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.style.animation = 'slideOut 0.3s ease-out';
                    setTimeout(() => notification.remove(), 300);
                }
            }, 5000);
        },

        /**
         * Initialize notification styles
         */
        initializeStyles() {
            if (document.getElementById('notification-styles')) {
                return;
            }

            const style = document.createElement('style');
            style.id = 'notification-styles';
            style.textContent = `
            .notification-toast {
                position: fixed;
                top: 20px;
                right: 20px;
                min-width: 300px;
                padding: 16px 24px;
                border-radius: 8px;
                background: white;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                z-index: 10000;
                animation: slideIn 0.3s ease-out;
            }
            .notification-success {
                border-left: 4px solid #10b981;
            }
            .notification-error {
                border-left: 4px solid #ef4444;
            }
            .notification-info {
                border-left: 4px solid #3b82f6;
            }
            .notification-content {
                display: flex;
                align-items: center;
                gap: 12px;
            }
            .notification-icon {
                font-size: 20px;
                font-weight: bold;
                flex-shrink: 0;
            }
            .notification-success .notification-icon {
                color: #10b981;
            }
            .notification-error .notification-icon {
                color: #ef4444;
            }
            .notification-info .notification-icon {
                color: #3b82f6;
            }
            .notification-message {
                color: #1f2937;
                font-weight: 500;
                flex: 1;
            }
            .notification-close {
                background: none;
                border: none;
                font-size: 24px;
                line-height: 1;
                color: #6b7280;
                cursor: pointer;
                padding: 0;
                margin-left: auto;
                flex-shrink: 0;
            }
            .notification-close:hover {
                color: #1f2937;
            }
            .character-count {
                display: block;
                margin-top: 4px;
                font-size: 0.75rem;
            }
            @keyframes slideIn {
                from {
                    transform: translateX(400px);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
            @keyframes slideOut {
                from {
                    transform: translateX(0);
                    opacity: 1;
                }
                to {
                    transform: translateX(400px);
                    opacity: 0;
                }
            }
        `;
            document.head.appendChild(style);
        }
    };

    // ========== AUTO-SAVE FUNCTIONALITY ==========
    let autoSaveTimer;
    const AUTO_SAVE_DELAY = 30000; // 30 seconds

    /**
     * Start auto-save timer
     */
    function startAutoSave() {
        clearTimeout(autoSaveTimer);

        autoSaveTimer = setTimeout(() => {
            const title = postTitle.value.trim();
            const content = $('#summernote').summernote('code').trim();

            // Only auto-save if there's content
            if (title && content && content !== '<p><br></p>') {
                console.log('Auto-saving draft...');
                createArticleForm.saveDraft();
            }
        }, AUTO_SAVE_DELAY);
    }

    // Attach auto-save listeners
    if (postTitle) {
        postTitle.addEventListener('input', startAutoSave);
    }

    // Summernote change handler is already in initialization

    // ========== INITIALIZE ==========
    // Initialize the form handling when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            createArticleForm.init();
        });
    } else {
        createArticleForm.init();
    }

    // ========== PREVENT ACCIDENTAL PAGE LEAVE ==========
    let hasUnsavedChanges = false;

    // Track changes
    if (postTitle) {
        postTitle.addEventListener('input', () => { hasUnsavedChanges = true; });
    }

    $('#summernote').on('summernote.change', function () {
        hasUnsavedChanges = true;
    });

    // Warn before leaving
    window.addEventListener('beforeunload', (e) => {
        if (hasUnsavedChanges) {
            e.preventDefault();
            e.returnValue = 'You have unsaved changes. Are you sure you want to leave?';
            return e.returnValue;
        }
    });

    // Clear flag on successful save/publish
    const originalSaveDraft = createArticleForm.saveDraft;
    createArticleForm.saveDraft = async function () {
        await originalSaveDraft.call(this);
        hasUnsavedChanges = false;
    };
</script>
@endpush