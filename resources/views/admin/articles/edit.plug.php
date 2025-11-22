@extends('layouts.admin')

@section('title', 'Admin Articles Edit')

@push('styles')
<!-- Summernote CSS -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet">
@endpush

@section('content')
<h1 class="page-title">Edit Post</h1>
<p class="page-subtitle">Write and publish engaging content for your audience.</p>

<!-- Form for PHP handling -->
<form action="{{ route('admin.articles.update', ['id' => $article->id]) }}" method="POST" enctype="multipart/form-data" id="postForm">
    @csrf
    @method('PUT')

    <!-- Hidden field to determine action -->
    <input type="hidden" name="action" id="formAction" value="draft">

    <div class="editor-container">
        <!-- Main Editor Area -->
        <div class="editor-main">
            <!-- Title Input -->
            <div class="editor-card">
                <div class="editor-card-body title-container">
                    <div class="form-group" style="width: 100%;">
                        <textarea
                            class="form-control title-input @error('title') is-invalid @enderror"
                            name="title"
                            id="postTitle"
                            placeholder="Add title"
                            rows="1"
                            autocomplete="off"
                            style="height: auto;">{{ old('title', $article->title) }}</textarea>
                        @error('title')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Summernote Editor -->
            <div class="editor-card">
                <div class="editor-card-body">
                    <div class="form-group">
                        <textarea
                            id="summernote"
                            name="content"
                            class="form-control @error('content') is-invalid @enderror">{{ old('content', $article->content) }}</textarea>
                        @error('content')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
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
                        <textarea
                            class="form-control @error('excerpt') is-invalid @enderror"
                            name="excerpt"
                            id="postExcerpt"
                            rows="3"
                            placeholder="Write a brief excerpt that will appear in post previews...">{{ old('excerpt', $article->excerpt) }}</textarea>
                        <div class="form-text">An optional hand-crafted summary of your post.</div>
                        @error('excerpt')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
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
                        <div class="form-label">Status: <span class="status-badge text-capitalize {{ $article->status === 'draft' ? 'status-draft' : ($article->status === 'published' ? 'status-published' : 'status-archived') }}">{{ old('status', $article->status) }}</span></div>
                        <div class="form-text">Ready to publish</div>
                    </div>

                    <div class="publish-actions">
                        <button type="button" class="btn-custom btn-secondary" id="saveDraft">
                            <i class="bi bi-file-earmark"></i>
                            Save Draft
                        </button>
                        <button type="button" class="btn-custom btn-primary" id="publishPost">
                            <i class="bi bi-send"></i>
                            {{ $article->status === 'published' ? 'Update' : 'Publish' }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Categories Dropdown -->
            <div class="editor-card">
                <div class="editor-card-header">
                    <h2 class="editor-card-title">Categories</h2>
                </div>
                <div class="editor-card-body">
                    <div class="form-group">
                        <?php if (!empty($categories) && $categories->count() > 0): ?>
                            <select
                                class="form-select @error('categories') is-invalid @enderror"
                                name="categories"
                                id="categoriesSelect">
                                <option value="">Select a category</option>
                                <?php foreach ($categories as $category): ?>
                                    <option
                                        value="{{ $category->id }}"
                                        data-color="{{ $category->color }}"
                                        {{ old('categories', $article->categories) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        <?php else: ?>
                            <p class="text-muted">No categories available</p>
                        <?php endif; ?>

                        <button type="button" class="btn-custom btn-secondary" style="margin-top: 0.5rem; width: 100%;"
                            onclick="alert('Category management coming soon!')">
                            <i class="bi bi-plus"></i>
                            Add New Category
                        </button>
                        @error('categories')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Featured Image -->
            <div class="editor-card">
                <div class="editor-card-header">
                    <h2 class="editor-card-title">Featured Image</h2>
                </div>
                <div class="editor-card-body">
                    <!-- Hidden input for file upload -->
                    <input type="file" name="featured_image" id="featuredImageInput" accept="image/*" style="display: none;">
                    
                    <!-- Hidden input to track image removal -->
                    <input type="hidden" name="remove_featured_image" id="removeFeaturedImageInput" value="0">
                    
                    <div class="featured-image-container" id="featuredImageContainer">
                        <div class="featured-image-placeholder" style="<?= !empty($article->featured_image) ? 'display: none;' : 'display: flex;' ?></div>">
                            <i class="bi bi-image"></i>
                            <p>Set featured image</p>
                            <p class="text-muted">Click to upload or drag and drop</p>
                        </div>
                        <img src="{{ $article->featured_image ?? '' }}" 
                             class="featured-image-preview" 
                             id="featuredImagePreview" 
                             alt="Featured Image"
                             style="<?= !empty($article->featured_image) ? 'display: block;' : 'display: none;' ?>">
                    </div>

                    <div class="featured-image-actions" id="featuredImageActions" style="<?= !empty($article->featured_image) ? 'display: flex;' : 'display: none;' ?>">
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

            <!-- SEO Settings -->
            <div class="editor-card">
                <div class="editor-card-header">
                    <h2 class="editor-card-title">SEO Settings</h2>
                </div>
                <div class="editor-card-body">
                    <div class="form-group">
                        <label for="seoTitle" class="form-label">SEO Title</label>
                        <input
                            type="text"
                            class="form-control @error('seo_title') is-invalid @enderror"
                            name="seo_title"
                            id="seoTitle"
                            placeholder="SEO optimized title"
                            value="{{ old('seo_title', $article->seo_title) }}">
                        <div class="form-text">Recommended: 50-60 characters</div>
                        @error('seo_title')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="seoDescription" class="form-label">Meta Description</label>
                        <textarea
                            class="form-control @error('seo_description') is-invalid @enderror"
                            name="seo_description"
                            id="seoDescription"
                            rows="3"
                            placeholder="Write a compelling meta description">{{ old('seo_description', $article->seo_description) }}</textarea>
                        <div class="form-text">Recommended: 150-160 characters</div>
                        @error('seo_description')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="seo-preview">
                        <div class="seo-title" id="seoPreviewTitle">Your SEO title will appear here</div>
                        <div class="seo-url">https://blogname.com/{{ old('slug', $article->slug) }}</div>
                        <div class="seo-description" id="seoPreviewDescription">Your meta description will appear here in search results.</div>
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
    // ========== SUMMERNOTE INITIALIZATION ==========
    $(document).ready(function() {
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
                onChange: function(contents, $editable) {
                    $('#summernote').val(contents);
                },
                onInit: function() {
                    $('#summernote').val($('#summernote').summernote('code'));
                }
            }
        });

        // ========== FORM SUBMISSION HANDLING ==========
        $('#postForm').on('submit', function(e) {
            const summernoteContent = $('#summernote').summernote('code');
            $('#summernote').val(summernoteContent);

            const title = $('#postTitle').val().trim();
            const action = $('#formAction').val();

            if (action === 'publish') {
                const contentText = $(summernoteContent).text().trim();

                if (!summernoteContent || contentText.length < 50) {
                    e.preventDefault();
                    alert('Please add at least 50 characters of content before publishing.');
                    return false;
                }
            }
        });

        // ========== SAVE DRAFT BUTTON ==========
        $('#saveDraft').on('click', function() {
            $('#formAction').val('draft');
            $('#postForm').submit();
        });

        // ========== PUBLISH BUTTON ==========
        $('#publishPost').on('click', function() {
            $('#formAction').val('publish');
            $('#postForm').submit();
        });

        // ========== AUTO-RESIZE TITLE TEXTAREA ==========
        const titleTextarea = document.getElementById('postTitle');
        if (titleTextarea) {
            titleTextarea.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = (this.scrollHeight) + 'px';
            });

            // Initial height adjustment
            titleTextarea.style.height = 'auto';
            titleTextarea.style.height = (titleTextarea.scrollHeight) + 'px';
        }
    });

    // ========== SEO PREVIEW ==========
    const seoTitle = document.getElementById('seoTitle');
    const seoDescription = document.getElementById('seoDescription');
    const seoPreviewTitle = document.getElementById('seoPreviewTitle');
    const seoPreviewDescription = document.getElementById('seoPreviewDescription');
    const postTitle = document.getElementById('postTitle');
    const postExcerpt = document.getElementById('postExcerpt');

    function updateSeoPreview() {
        const titleText = seoTitle.value || postTitle.value || 'Your SEO title will appear here';
        seoPreviewTitle.textContent = titleText;

        const descText = seoDescription.value || postExcerpt.value || 'Your meta description will appear here in search results.';
        seoPreviewDescription.textContent = descText;

        updateCharacterCount(seoTitle, 60, 'seo-title-count');
        updateCharacterCount(seoDescription, 160, 'seo-description-count');
    }

    function updateCharacterCount(element, maxLength, counterId) {
        const currentLength = element.value.length;
        let countElement = document.getElementById(counterId);

        if (!countElement) {
            countElement = document.createElement('small');
            countElement.id = counterId;
            countElement.className = 'character-count';
            countElement.style.display = 'block';
            countElement.style.marginTop = '0.25rem';
            element.parentNode.appendChild(countElement);
        }

        countElement.textContent = `${currentLength}/${maxLength}`;
        countElement.style.color = currentLength > maxLength ? '#ef4444' : '#6b7280';
    }

    if (seoTitle) seoTitle.addEventListener('input', updateSeoPreview);
    if (seoDescription) seoDescription.addEventListener('input', updateSeoPreview);
    if (postTitle) postTitle.addEventListener('input', updateSeoPreview);
    if (postExcerpt) postExcerpt.addEventListener('input', updateSeoPreview);

    updateSeoPreview();

    // ========== FEATURED IMAGE HANDLING ==========
    const featuredImageInput = document.getElementById('featuredImageInput');
    const featuredImageContainer = document.getElementById('featuredImageContainer');
    const featuredImagePreview = document.getElementById('featuredImagePreview');
    const featuredImageActions = document.getElementById('featuredImageActions');
    const changeImageBtn = document.getElementById('changeImage');
    const removeImageBtn = document.getElementById('removeImage');
    const removeFeaturedImageInput = document.getElementById('removeFeaturedImageInput');

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
            removeFeaturedImageInput.value = '1';
            featuredImageContainer.querySelector('.featured-image-placeholder').style.display = 'flex';
        });
    }

    if (featuredImageInput) {
        featuredImageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
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
                    removeFeaturedImageInput.value = '0';
                    featuredImageContainer.querySelector('.featured-image-placeholder').style.display = 'none';
                }
                reader.readAsDataURL(file);
            }
        });
    }
</script>
@endpush