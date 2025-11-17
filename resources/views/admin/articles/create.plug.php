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
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="categoryTech" checked>
                        <label class="form-check-label" for="categoryTech">
                            Technology
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="categoryNews">
                        <label class="form-check-label" for="categoryNews">
                            News
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="categoryTravel">
                        <label class="form-check-label" for="categoryTravel">
                            Travel
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="categoryLifestyle">
                        <label class="form-check-label" for="categoryLifestyle">
                            Lifestyle
                        </label>
                    </div>
                    <button class="btn-custom btn-secondary" style="margin-top: 0.5rem; width: 100%;">
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
                    // Auto-save functionality
                    startAutoSave();
                }
            }
        });
    });

    // ========== FEATURED IMAGE ==========
    const featuredImageContainer = document.getElementById('featuredImageContainer');
    const featuredImagePreview = document.getElementById('featuredImagePreview');
    const featuredImageActions = document.getElementById('featuredImageActions');
    const changeImageBtn = document.getElementById('changeImage');
    const removeImageBtn = document.getElementById('removeImage');

    featuredImageContainer.addEventListener('click', function () {
        // In a real application, this would open a file picker
        const imageUrl = 'https://images.unsplash.com/photo-1504711434969-e33886168f5c?w=800&h=600&fit=crop';
        featuredImagePreview.src = imageUrl;
        featuredImagePreview.style.display = 'block';
        featuredImageContainer.querySelector('.featured-image-placeholder').style.display = 'none';
        featuredImageActions.style.display = 'flex';
    });

    changeImageBtn.addEventListener('click', function (e) {
        e.stopPropagation();
        featuredImageContainer.click();
    });

    removeImageBtn.addEventListener('click', function (e) {
        e.stopPropagation();
        featuredImagePreview.src = '';
        featuredImagePreview.style.display = 'none';
        featuredImageContainer.querySelector('.featured-image-placeholder').style.display = 'block';
        featuredImageActions.style.display = 'none';
    });

    // ========== SEO PREVIEW ==========
    const seoTitle = document.getElementById('seoTitle');
    const seoDescription = document.getElementById('seoDescription');
    const seoPreviewTitle = document.getElementById('seoPreviewTitle');
    const seoPreviewDescription = document.getElementById('seoPreviewDescription');
    const postTitle = document.getElementById('postTitle');

    function updateSeoPreview() {
        seoPreviewTitle.textContent = seoTitle.value || postTitle.value || 'Your SEO title will appear here';
        seoPreviewDescription.textContent = seoDescription.value || 'Your meta description will appear here in search results.';
    }

    seoTitle.addEventListener('input', updateSeoPreview);
    seoDescription.addEventListener('input', updateSeoPreview);
    postTitle.addEventListener('input', updateSeoPreview);

    // ========== PUBLISHING ==========
    const saveDraftBtn = document.getElementById('saveDraft');
    const publishBtn = document.getElementById('publishPost');

    saveDraftBtn.addEventListener('click', function () {
        // In a real application, this would save to a database
        alert('Draft saved successfully!');
        console.log('Post saved as draft');
    });

    publishBtn.addEventListener('click', function () {
        const title = postTitle.value.trim();
        if (!title) {
            alert('Please add a title before publishing.');
            postTitle.focus();
            return;
        }

        if (confirm('Are you ready to publish this post?')) {
            // In a real application, this would publish to the blog
            alert('Post published successfully!');
            console.log('Post published');
        }
    });

    // ========== AUTO-SAVE ==========
    let autoSaveTimer;

    function startAutoSave() {
        clearTimeout(autoSaveTimer);
        autoSaveTimer = setTimeout(() => {
            if (postTitle.value.trim() || postContent.textContent.trim()) {
                console.log('Auto-saving draft...');
                // In a real application, this would save to a database
            }
        }, 3000); // Auto-save after 3 seconds of inactivity
    }

    postTitle.addEventListener('input', startAutoSave);
    postContent.addEventListener('input', startAutoSave);
</script>
@endpush