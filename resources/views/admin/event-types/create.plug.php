@extends('layouts.admin')

@section('title', 'Admin Create Event Type')

@section('content')
<h1 class="page-title">Create New Event Type</h1>
<p class="page-subtitle">Add a new event type to organize your content effectively.</p>

<!-- ========== CATEGORY FORM ========== -->
<div class="form-card">
   <div class="form-card-header">
      <h2 class="form-card-title">Type Details</h2>
   </div>
   <div class="form-card-body">
      <form method="POST" action="{{ route('admin.event-type.store') }}" id="categoryForm">
         @csrf
         
         <div class="form-group">
            <label for="categoryName" class="form-label">Type Name *</label>
            <input type="text" class="form-control" id="categoryName" name="name" 
                   placeholder="Enter event type name" value="{{ old('name') }}" required>
            <div class="form-text">The name is how it appears on your site.</div>
            @error('name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
         </div>

         <div class="form-group">
            <label for="categorySlug" class="form-label">Slug</label>
            <input type="text" class="form-control" id="categorySlug" name="slug" 
                   placeholder="event-type-slug" value="{{ old('slug') }}">
            <div class="form-text">The "slug" is the URL-friendly version of the name.</div>
            @error('slug')
                <div class="text-danger">{{ $message }}</div>
            @enderror
         </div>

         <div class="form-group">
            <label for="categoryDescription" class="form-label">Description</label>
            <textarea class="form-control" id="categoryDescription" name="description" 
                      rows="3" placeholder="Enter a description (optional)">{{ old('description') }}</textarea>
            <div class="form-text">The description is not prominent by default; however, some themes may show it.</div>
            @error('description')
                <div class="text-danger">{{ $message }}</div>
            @enderror
         </div>

         <div class="form-group">
            <label for="categoryColor" class="form-label">Color</label>
            <div class="color-picker">
               <input type="color" class="color-input" id="categoryColor" name="color" 
                      value="{{ old('color', '#00f5d4') }}">
               <span>Choose a color to represent this type</span>
            </div>
            @error('color')
                <div class="text-danger">{{ $message }}</div>
            @enderror
         </div>

         <div class="form-group">
            <label for="categoryIcon" class="form-label">Icon</label>
            <select class="form-control" id="categoryIcon" name="icon">
               <option value="bi-emoji-laughing" {{ old('icon') == 'bi-emoji-laughing' ? 'selected' : '' }}>Default (LifeStyle/Social/Party)</option>
               <option value="bi-mortarboard" {{ old('icon') == 'bi-mortarboard' ? 'selected' : '' }}>Education / Workshops</option>
               <option value="bi-music-note-beamed" {{ old('icon') == 'bi-music-note-beamed' ? 'selected' : '' }}>Concerts / Performances</option>
               <option value="bi-controller" {{ old('icon') == 'bi-controller' ? 'selected' : '' }}>Games / Tournaments</option>
               <option value="bi-stopwatch" {{ old('icon') == 'bi-stopwatch' ? 'selected' : '' }}>Sports / Fitness</option>
               <option value="bi-diagram-3" {{ old('icon') == 'bi-diagram-3' ? 'selected' : '' }}>Tech / Conferences / Seminars</option>
               <option value="bi-briefcase" {{ old('icon') == 'bi-briefcase' ? 'selected' : '' }}>Business / Networking</option>
               <option value="bi-palette" {{ old('icon') == 'bi-palette' ? 'selected' : '' }}>Art / Creativity</option>
               <option value="bi-egg-fried" {{ old('icon') == 'bi-egg-fried' ? 'selected' : '' }}>Food & Drinks</option>
               <option value="bi-geo-alt" {{ old('icon') == 'bi-geo-alt' ? 'selected' : '' }}>Travel / Outdoor</option>
            </select>
            <div class="icon-preview">
               <div class="icon-preview-box">
                  <i class="bi bi-emoji-laughing" id="iconPreview"></i>
               </div>
               <span>This is how the icon will appear in the category list</span>
            </div>
            @error('icon')
                <div class="text-danger">{{ $message }}</div>
            @enderror
         </div>

      </form>
   </div>
   <div class="form-actions p-3 gap-3">
      <a href="{{ route('admin.event-type.index') }}" class="btn-custom btn-secondary" id="cancelButton">
         <i class="bi bi-x-lg"></i>
         Cancel
      </a>
      <button type="submit" form="categoryForm" class="btn-custom btn-success" id="saveButton">
         <i class="bi bi-check-lg"></i>
         Create Type
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
         <div class="category-preview-color" id="previewColor"></div>
         <div class="category-preview-info">
            <div class="category-preview-name" id="previewName">Event Type Name</div>
            <div class="category-preview-slug" id="previewSlug">event-type-slug</div>
            <div class="category-preview-description" id="previewDescription">Event Type description will appear here</div>
         </div>
         <div class="category-preview-icon">
            <i class="bi bi-folder" id="previewIcon"></i>
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

        // Initialize preview with old values or defaults
        previewName.textContent = categoryName.value || 'Event Type Name';
        previewSlug.textContent = categorySlug.value || 'event-type-slug';
        previewDescription.textContent = categoryDescription.value || 'Event Type description will appear here';
        previewColor.style.backgroundColor = categoryColor.value;
        previewIcon.className = `bi ${categoryIcon.value}`;
        iconPreview.className = `bi ${categoryIcon.value}`;

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