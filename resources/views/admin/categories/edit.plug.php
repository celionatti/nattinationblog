@extends('layouts.admin')

@section('title', 'Admin Edit Category')

@section('content')
<h1 class="page-title">Edit Category</h1>
<p class="page-subtitle">Update category details and settings.</p>

<!-- ========== CATEGORY FORM ========== -->
<div class="form-card">
    <div class="form-card-header">
        <h2 class="form-card-title">Category Details</h2>
    </div>
    <div class="form-card-body">
        <form method="POST" action="{{ route('admin.categories.update', ['id' => $category->id]) }}" id="categoryForm">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="categoryName" class="form-label">Category Name *</label>
                <input type="text" class="form-control" id="categoryName" name="name"
                    placeholder="Enter category name" value="{{ old('name', $category->name) }}" required>
                <div class="form-text">The name is how it appears on your site.</div>
                @error('name')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="categorySlug" class="form-label">Slug</label>
                <input type="text" class="form-control" id="categorySlug" name="slug"
                    placeholder="category-slug" value="{{ old('slug', $category->slug) }}">
                <div class="form-text">The "slug" is the URL-friendly version of the name.</div>
                @error('slug')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="categoryDescription" class="form-label">Description</label>
                <textarea class="form-control" id="categoryDescription" name="description"
                    rows="3" placeholder="Enter a description (optional)">{{ old('description', $category->description) }}</textarea>
                <div class="form-text">The description is not prominent by default; however, some themes may show it.</div>
                @error('description')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="categoryColor" class="form-label">Color</label>
                <div class="color-picker">
                    <input type="color" class="color-input" id="categoryColor" name="color"
                        value="{{ old('color', $category->color ?: '#667eea') }}">
                    <span>Choose a color to represent this category</span>
                </div>
                @error('color')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="categoryIcon" class="form-label">Icon</label>
                <select class="form-control" id="categoryIcon" name="icon">
                    <option value="bi-folder" {{ old('icon', $category->icon) == 'bi-folder' ? 'selected' : '' }}>Default</option>
                    <option value="bi-cpu" {{ old('icon', $category->icon) == 'bi-cpu' ? 'selected' : '' }}>Technology</option>
                    <option value="bi-geo-alt" {{ old('icon', $category->icon) == 'bi-geo-alt' ? 'selected' : '' }}>Travel</option>
                    <option value="bi-heart" {{ old('icon', $category->icon) == 'bi-heart' ? 'selected' : '' }}>Lifestyle</option>
                    <option value="bi-egg-fried" {{ old('icon', $category->icon) == 'bi-egg-fried' ? 'selected' : '' }}>Food</option>
                    <option value="bi-book" {{ old('icon', $category->icon) == 'bi-book' ? 'selected' : '' }}>Education</option>
                    <option value="bi-briefcase" {{ old('icon', $category->icon) == 'bi-briefcase' ? 'selected' : '' }}>Business</option>
                    <option value="bi-film" {{ old('icon', $category->icon) == 'bi-film' ? 'selected' : '' }}>Entertainment</option>
                </select>
                <div class="icon-preview">
                    <div class="icon-preview-box">
                        <i class="bi {{ old('icon', $category->icon ?: 'bi-folder') }}" id="iconPreview"></i>
                    </div>
                    <span>This is how the icon will appear in the category list</span>
                </div>
                @error('icon')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="parentCategory" class="form-label">Parent Category</label>
                <select class="form-control" id="parentCategory" name="parent_id">
                    <option value="">None</option>
                    @foreach($parentCategories as $parentCategory)
                    <option value="{{ $parentCategory->id }}"
                        {{ old('parent_id', $category->parent_id) == $parentCategory->id ? 'selected' : '' }}>
                        {{ $parentCategory->name }}
                    </option>
                    @endforeach
                </select>
                <div class="form-text">Categories can have a hierarchy. You might have a Jazz category, and under that have children categories for Bebop and Big Band.</div>
                @error('parent_id')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="categoryActive" name="is_active"
                        value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label" for="categoryActive">
                        Active Category
                    </label>
                </div>
                <div class="form-text">Inactive categories won't be displayed on the site.</div>
                @error('is_active')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </form>
    </div>
    <div class="form-actions p-3 gap-3">
        <a href="{{ route('admin.categories.index') }}" class="btn-custom btn-secondary text-decoration-none" id="cancelButton">
            <i class="bi bi-x-lg"></i>
            Cancel
        </a>
        <button type="submit" form="categoryForm" class="btn-custom btn-primary" id="saveButton">
            <i class="bi bi-check-lg"></i>
            Update Category
        </button>
    </div>
</div>

<!-- ========== PREVIEW CARD ========== -->
<div class="preview-card">
    <div class="preview-card-header">
        <h3 class="preview-card-title">Preview</h3>
    </div>
    <div class="preview-card-body">
        <div class="category-preview">
            <div class="category-preview-color" id="previewColor" style="background-color: <?= old('color', $category->color ?: '#667eea') ?>"></div>
            <div class="category-preview-info">
                <div class="category-preview-name" id="previewName">{{ old('name', $category->name) }}</div>
                <div class="category-preview-slug" id="previewSlug">{{ old('slug', $category->slug) }}</div>
                <div class="category-preview-description" id="previewDescription">{{ old('description', $category->description) ?: 'Category description will appear here' }}</div>
            </div>
            <div class="category-preview-icon">
                <i class="bi {{ old('icon', $category->icon ?: 'bi-folder') }}" id="previewIcon"></i>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // ========== FORM FUNCTIONALITY ==========
    const categoryName = document.getElementById('categoryName');
    const categorySlug = document.getElementById('categorySlug');
    const categoryDescription = document.getElementById('categoryDescription');
    const categoryColor = document.getElementById('categoryColor');
    const categoryIcon = document.getElementById('categoryIcon');
    const parentCategory = document.getElementById('parentCategory');

    // Preview elements
    const previewName = document.getElementById('previewName');
    const previewSlug = document.getElementById('previewSlug');
    const previewDescription = document.getElementById('previewDescription');
    const previewColor = document.getElementById('previewColor');
    const previewIcon = document.getElementById('previewIcon');
    const iconPreview = document.getElementById('iconPreview');

    // ========== AUTO-GENERATE SLUG ==========
    categoryName.addEventListener('input', function() {
        if (!categorySlug.dataset.manual) {
            const slug = this.value
                .toLowerCase()
                .trim()
                .replace(/[^\w\s-]/g, '')
                .replace(/[\s_-]+/g, '-')
                .replace(/^-+|-+$/g, '');
            categorySlug.value = slug;

            // Update preview
            previewName.textContent = this.value || 'Category Name';
            previewSlug.textContent = slug || 'category-slug';
        }
    });

    categorySlug.addEventListener('input', function() {
        this.dataset.manual = 'true';
        previewSlug.textContent = this.value || 'category-slug';
    });

    // ========== UPDATE PREVIEW ==========
    categoryDescription.addEventListener('input', function() {
        previewDescription.textContent = this.value || 'Category description will appear here';
    });

    categoryColor.addEventListener('input', function() {
        previewColor.style.backgroundColor = this.value;
    });

    categoryIcon.addEventListener('change', function() {
        const iconClass = this.value;
        previewIcon.className = `bi ${iconClass}`;
        iconPreview.className = `bi ${iconClass}`;
    });

    // ========== FORM VALIDATION ==========
    document.getElementById('categoryForm').addEventListener('submit', function(e) {
        const name = categoryName.value.trim();
        if (!name) {
            e.preventDefault();
            alert('Please enter a category name.');
            categoryName.focus();
            return;
        }
    });
</script>
@endpush