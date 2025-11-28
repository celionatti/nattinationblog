@extends('layouts.admin')

@section('title', 'Admin Edit Category')

@section('content')
<h1 class="page-title">Edit Event Type</h1>
<p class="page-subtitle">Update event type details and settings.</p>

<!-- ========== CATEGORY FORM ========== -->
<div class="form-card">
    <div class="form-card-header">
        <h2 class="form-card-title">Event Type Details</h2>
    </div>
    <div class="form-card-body">
        <form method="POST" action="{{ route('admin.event-type.update', ['id' => $type->id]) }}" id="categoryForm">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="categoryName" class="form-label">Event Type Name *</label>
                <input type="text" class="form-control" id="categoryName" name="name"
                    placeholder="Enter event type name" value="{{ old('name', $type->name) }}" required>
                <div class="form-text">The name is how it appears on your site.</div>
                @error('name')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="categorySlug" class="form-label">Slug</label>
                <input type="text" class="form-control" id="categorySlug" name="slug"
                    placeholder="category-slug" value="{{ old('slug', $type->slug) }}">
                <div class="form-text">The "slug" is the URL-friendly version of the name.</div>
                @error('slug')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="categoryDescription" class="form-label">Description</label>
                <textarea class="form-control" id="categoryDescription" name="description"
                    rows="3" placeholder="Enter a description (optional)">{{ old('description', $type->description) }}</textarea>
                <div class="form-text">The description is not prominent by default; however, some themes may show it.</div>
                @error('description')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="categoryColor" class="form-label">Color</label>
                <div class="color-picker">
                    <input type="color" class="color-input" id="categoryColor" name="color"
                        value="{{ old('color', $type->color ?: '#667eea') }}">
                    <span>Choose a color to represent this event type</span>
                </div>
                @error('color')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="categoryIcon" class="form-label">Icon</label>
                <select class="form-control" id="categoryIcon" name="icon">
                    <option value="bi-emoji-laughing" {{ old('icon', $type->icon) == 'bi-emoji-laughing' ? 'selected' : '' }}>Default (LifeStyle/Social/Party)</option>
                    <option value="bi-mortarboard" {{ old('icon', $type->icon) == 'bi-mortarboard' ? 'selected' : '' }}>Education / Workshops</option>
                    <option value="bi-music-note-beamed" {{ old('icon', $type->icon) == 'bi-music-note-beamed' ? 'selected' : '' }}>Concerts / Performances</option>
                    <option value="bi-controller" {{ old('icon', $type->icon) == 'bi-controller' ? 'selected' : '' }}>Games / Tournaments</option>
                    <option value="bi-stopwatch" {{ old('icon', $type->icon) == 'bi-stopwatch' ? 'selected' : '' }}>Sports / Fitness</option>
                    <option value="bi-diagram-3" {{ old('icon', $type->icon) == 'bi-diagram-3' ? 'selected' : '' }}>Tech / Conferences / Seminars</option>
                    <option value="bi-briefcase" {{ old('icon', $type->icon) == 'bi-briefcase' ? 'selected' : '' }}>Business / Networking</option>
                    <option value="bi-palette" {{ old('icon', $type->icon) == 'bi-palette' ? 'selected' : '' }}>Art / Creativity</option>
                    <option value="bi-egg-fried" {{ old('icon', $type->icon) == 'bi-egg-fried' ? 'selected' : '' }}>Food & Drinks</option>
                    <option value="bi-geo-alt" {{ old('icon', $type->icon) == 'bi-geo-alt' ? 'selected' : '' }}>Travel / Outdoor</option>
                </select>
                <div class="icon-preview">
                    <div class="icon-preview-box">
                        <i class="bi {{ old('icon', $type->icon ?: 'bi-emoji-laughing') }}" id="iconPreview"></i>
                    </div>
                    <span>This is how the icon will appear in the event type list</span>
                </div>
                @error('icon')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="categoryActive" name="is_active"
                        value="1" {{ old('is_active', $type->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label" for="categoryActive">
                        Active Type
                    </label>
                </div>
                <div class="form-text">Inactive types won't be displayed on the site.</div>
                @error('is_active')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </form>
    </div>
    <div class="form-actions p-3 gap-3">
        <a href="{{ route('admin.event-type.index') }}" class="btn-custom btn-secondary text-decoration-none" id="cancelButton">
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
            <div class="category-preview-color" id="previewColor" style="background-color: <?= old('color', $type->color ?: '#00f5d4') ?>"></div>
            <div class="category-preview-info">
                <div class="category-preview-name" id="previewName">{{ old('name', $type->name) }}</div>
                <div class="category-preview-slug" id="previewSlug">{{ old('slug', $type->slug) }}</div>
                <div class="category-preview-description" id="previewDescription">{{ old('description', $type->description) ?: 'Category description will appear here' }}</div>
            </div>
            <div class="category-preview-icon">
                <i class="bi {{ old('icon', $type->icon ?: 'bi-emoji-laughing') }}" id="previewIcon"></i>
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
            previewName.textContent = this.value || 'Event Type Name';
            previewSlug.textContent = slug || 'event-type-slug';
        }
    });

    categorySlug.addEventListener('input', function() {
        this.dataset.manual = 'true';
        previewSlug.textContent = this.value || 'event-type-slug';
    });

    // ========== UPDATE PREVIEW ==========
    categoryDescription.addEventListener('input', function() {
        previewDescription.textContent = this.value || 'Event Type description will appear here';
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
            alert('Please enter a event type name.');
            categoryName.focus();
            return;
        }
    });
</script>
@endpush