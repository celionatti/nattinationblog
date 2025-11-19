@extends('layouts.admin')

@section('title', 'Admin Create Category')

@section('content')
<h1 class="page-title">Create New Category</h1>
<p class="page-subtitle">Add a new category to organize your content effectively.</p>

<!-- ========== CATEGORY FORM ========== -->
<div class="form-card">
    <div class="form-card-header">
        <h2 class="form-card-title">Category Details</h2>
    </div>
    <div class="form-card-body">
        <form id="categoryForm">
            <div class="form-group">
                <label for="categoryName" class="form-label">Category Name</label>
                <input type="text" class="form-control" id="categoryName" placeholder="Enter category name" required>
                <div class="form-text">The name is how it appears on your site.</div>
            </div>

            <div class="form-group">
                <label for="categorySlug" class="form-label">Slug</label>
                <input type="text" class="form-control" id="categorySlug" placeholder="category-slug">
                <div class="form-text">The "slug" is the URL-friendly version of the name.</div>
            </div>

            <div class="form-group">
                <label for="categoryDescription" class="form-label">Description</label>
                <textarea class="form-control" id="categoryDescription" rows="3" placeholder="Enter a description (optional)"></textarea>
                <div class="form-text">The description is not prominent by default; however, some themes may show it.</div>
            </div>

            <div class="form-group">
                <label for="categoryColor" class="form-label">Color</label>
                <div class="color-picker">
                    <input type="color" class="color-input" id="categoryColor" value="#667eea">
                    <span>Choose a color to represent this category</span>
                </div>
            </div>

            <div class="form-group">
                <label for="categoryIcon" class="form-label">Icon</label>
                <select class="form-control" id="categoryIcon">
                    <option value="bi-folder">Default</option>
                    <option value="bi-cpu">Technology</option>
                    <option value="bi-geo-alt">Travel</option>
                    <option value="bi-heart">Lifestyle</option>
                    <option value="bi-egg-fried">Food</option>
                    <option value="bi-book">Education</option>
                    <option value="bi-briefcase">Business</option>
                    <option value="bi-film">Entertainment</option>
                </select>
                <div class="icon-preview">
                    <div class="icon-preview-box">
                        <i class="bi bi-folder" id="iconPreview"></i>
                    </div>
                    <span>This is how the icon will appear in the category list</span>
                </div>
            </div>

            <div class="form-group">
                <label for="parentCategory" class="form-label">Parent Category</label>
                <select class="form-control" id="parentCategory">
                    <option value="">None</option>
                    <option value="1">Technology</option>
                    <option value="2">Travel</option>
                    <option value="3">Lifestyle</option>
                </select>
                <div class="form-text">Categories can have a hierarchy. You might have a Jazz category, and under that have children categories for Bebop and Big Band.</div>
            </div>
        </form>
    </div>
    <div class="form-actions p-3 gap-3">
        <button class="btn-custom btn-secondary" id="cancelButton">
            <i class="bi bi-x-lg"></i>
            Cancel
        </button>
        <button class="btn-custom btn-success" id="saveButton">
            <i class="bi bi-check-lg"></i>
            Create Category
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
                <div class="category-preview-name" id="previewName">Category Name</div>
                <div class="category-preview-slug" id="previewSlug">category-slug</div>
                <div class="category-preview-description" id="previewDescription">Category description will appear here</div>
            </div>
            <div class="category-preview-icon">
                <i class="bi bi-folder" id="previewIcon"></i>
            </div>
        </div>
    </div>
</div>
@endsection