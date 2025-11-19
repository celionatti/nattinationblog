@extends('layouts.admin')

@section('title', 'Admin Categories Management')

@section('content')
<h1 class="page-title">Categories Management</h1>
<p class="page-subtitle">Organize your content with categories and track their performance.</p>

<!-- ========== CATEGORIES CONTROLS ========== -->
<div class="categories-controls">
    <form method="get" action="" class="filters-container" id="filterForm">
        <div class="filters-container">
            <div class="filter-group">
                <label class="filter-label">Sort By</label>
                <select class="filter-select" name="sort_by" id="sortFilter" onchange="this.form.submit()">
                    <option value="name" {{ ($sort_by ?? 'name' )==='name' ? 'selected' : '' }}>Name</option>
                    <option value="posts" {{ ($sort_by ?? '' )==='posts' ? 'selected' : '' }}>Post Count</option>
                    <option value="date" {{ ($sort_by ?? '' )==='date' ? 'selected' : '' }}>Date Created
                    </option>
                    <option value="updated" {{ ($sort_by ?? '' )==='updated' ? 'selected' : '' }}>Last Updated
                    </option>
                </select>
            </div>
            <div class="filter-group">
                <label class="filter-label">Status</label>
                <select class="filter-select" name="status" id="statusFilter" onchange="this.form.submit()">
                    <option value="all" {{ ($status_filter ?? 'all' )==='all' ? 'selected' : '' }}>All Categories
                    </option>
                    <option value="1" {{ ($status_filter ?? '' )==='1' ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ ($status_filter ?? '' )==='0' ? 'selected' : '' }}>Archived</option>
                </select>
            </div>
        </div>
        <!-- Preserve page parameter -->
        <input type="hidden" name="page" value="{{ $_GET['page'] ?? 1 }}">
    </form>

    <form action="{{ url('admin/categories/bulk-action') }}" method="post" id="bulkActionsForm">
        @csrf
        <input type="hidden" name="action" id="bulkActionType" value="">
        <div class="table-actions">
            <!-- Bulk Actions Button -->
            <div class="bulk-actions-container" id="bulkActionsContainer">
                <button type="button" class="btn-custom btn-secondary" id="bulkActionsButton">
                    <i class="bi bi-three-dots"></i>
                    Bulk Actions
                    <span id="selectedCount" class="ms-1">(0)</span>
                </button>
                <div class="bulk-actions-menu" id="bulkActionsMenu">
                    <div class="bulk-actions-header">
                        <span id="bulkActionsCount">0 items selected</span>
                    </div>
                    <ul class="bulk-actions-list">
                        <li class="bulk-action-item" data-action="activate">
                            <i class="bi bi-check-circle"></i>
                            Activate
                        </li>
                        <li class="bulk-action-item" data-action="archive">
                            <i class="bi bi-archive"></i>
                            Archive
                        </li>
                        <li class="bulk-action-item danger" data-action="delete">
                            <i class="bi bi-trash"></i>
                            Delete
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- ========== CATEGORIES GRID ========== -->
<div class="categories-grid">
    <!-- Add New Category Card -->
    <div class="add-category-card" onclick="window.location.href='/admin/categories/create'">
        <div class="add-category-icon">
            <i class="bi bi-plus-lg"></i>
        </div>
        <div class="add-category-text">Add New Category</div>
        <div class="add-category-subtext">Create a new category to organize your content</div>
    </div>

    <?php foreach ($categories as $category): ?>
        <!-- Category Card -->
        <div class="category-card" data-category-id="{{ $category->id }}">
            <div class="category-header">
                <input type="checkbox" class="category-checkbox" name="category_ids[]" value="{{ $category->id }}"
                    form="bulkActionsForm">
                <div class="category-color" style="background-color: <?= $category->color ?? '#6c757d' ?>;"></div>
            </div>
            <div class="category-body">
                <h3 class="category-title">
                    <i class="bi {{ $category->icon_class ?? 'bi-folder' }}"></i>
                    {{ $category->name }}
                </h3>
                <div class="category-slug">{{ $category->slug }}</div>
                <p class="category-description">{{ $category->description ?? 'No description provided.' }}</p>

                <div class="category-stats">
                    <div class="category-stat">
                        <div class="stat-value">{{ $category->posts_count ?? 0 }}</div>
                        <div class="stat-label">Posts</div>
                    </div>
                    <div class="category-stat">
                        <div class="stat-value">{{ number_format($category->views_count ?? 0) }}</div>
                        <div class="stat-label">Views</div>
                    </div>
                    <div class="category-stat">
                        <div class="stat-value">{{ $category->comments_count ?? 0 }}</div>
                        <div class="stat-label">Comments</div>
                    </div>
                </div>

                <div class="category-actions row">
                    <button class="col action-btn edit"
                        onclick="window.location.href='/admin/categories/edit/{{ $category->id }}'">
                        <i class="bi bi-pencil"></i>
                        Edit
                    </button>
                    <form method="POST" action="/admin/categories/delete/{{ $category->id }}" class="col"
                        style="display: inline;"
                        onsubmit="return confirm('Are you sure you want to delete {{ $category->name }}?')">
                        @csrf
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="action-btn delete w-100">
                            <i class="bi bi-trash"></i>
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php if (empty($categories) || count($categories) === 0): ?>
    <!-- Empty State (Hidden by default) -->
    <div class="empty-state" id="emptyState">
        <div class="empty-state-icon">
            <i class="bi bi-folder-x"></i>
        </div>
        <h3 class="empty-state-title">No Categories Found</h3>
        <p>Get started by creating your first category to organize your content.</p>
        <button class="btn-custom btn-primary" style="margin-top: 1rem;"
            onclick="window.location.href='/admin/categories/create'">
            <i class="bi bi-plus-lg"></i>
            Create Category
        </button>
    </div>
<?php endif; ?>

<?php if (isset($paginator) && $paginator && $paginator->hasPages()): ?>
    {{{ $paginator->render() }}}
<?php endif; ?>
@endsection

@push('scripts')
<script>
    // ========== BULK ACTIONS ==========
    const categoryCheckboxes = document.querySelectorAll('.category-checkbox');
    const bulkActionsContainer = document.getElementById('bulkActionsContainer');
    const bulkActionsButton = document.getElementById('bulkActionsButton');
    const bulkActionsMenu = document.getElementById('bulkActionsMenu');
    const selectedCount = document.getElementById('selectedCount');
    const bulkActionsCount = document.getElementById('bulkActionsCount');
    const bulkActionsForm = document.getElementById('bulkActionsForm');
    const bulkActionType = document.getElementById('bulkActionType');

    function updateSelectedCount() {
        const checkedCount = Array.from(categoryCheckboxes).filter(cb => cb.checked).length;
        selectedCount.textContent = `(${checkedCount})`;
        bulkActionsCount.textContent = `${checkedCount} item${checkedCount !== 1 ? 's' : ''} selected`;

        if (checkedCount > 0) {
            bulkActionsContainer.classList.add('show');
        } else {
            bulkActionsContainer.classList.remove('show');
            bulkActionsMenu.style.display = 'none';
        }
    }

    categoryCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedCount);
    });

    // Toggle bulk actions menu
    bulkActionsButton.addEventListener('click', function(e) {
        e.stopPropagation();
        bulkActionsMenu.style.display = bulkActionsMenu.style.display === 'block' ? 'none' : 'block';
    });

    // Close menu when clicking outside
    document.addEventListener('click', function() {
        bulkActionsMenu.style.display = 'none';
    });

    // Handle bulk action selection
    document.querySelectorAll('.bulk-action-item').forEach(item => {
        item.addEventListener('click', function() {
            const action = this.getAttribute('data-action');
            const checkedItems = Array.from(categoryCheckboxes).filter(cb => cb.checked);

            if (checkedItems.length === 0) {
                alert('Please select at least one category.');
                return;
            }

            let actionText = action;
            let confirmMessage = `Are you sure you want to ${actionText} ${checkedItems.length} categor${checkedItems.length !== 1 ? 'ies' : 'y'}?`;

            if (confirm(confirmMessage)) {
                bulkActionType.value = action;
                bulkActionsForm.submit();
            }
        });
    });
</script>
@endpush