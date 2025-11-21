@extends('layouts.admin')

@section('title', 'Admin Articles Management')

@section('content')
<h1 class="page-title">Articles Management</h1>
<p class="page-subtitle">Manage all your blog articles, create new content, and track performance.</p>

<!-- ========== POSTS CONTROLS ========== -->
<div class="posts-controls">
    <form action="{{ route('admin.articles.index') }}" method="get" id="filterForm">
        <div class="filters-container">
            <div class="filter-group">
                <label class="filter-label">Status</label>
                <select class="filter-select" name="status" id="statusFilter" onchange="this.form.submit()">
                    <option value="all" {{ ($status_filter ?? 'all') === 'all' ? 'selected' : '' }}>All Status</option>
                    <option value="published" {{ ($status_filter ?? '') === 'published' ? 'selected' : '' }}>Published</option>
                    <option value="draft" {{ ($status_filter ?? '') === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="archived" {{ ($status_filter ?? '') === 'archived' ? 'selected' : '' }}>Archived</option>
                </select>
            </div>
            <div class="filter-group">
                <label class="filter-label">Author</label>
                <select class="filter-select" name="author" id="authorFilter" onchange="this.form.submit()">
                    <option value="all" {{ ($author_filter ?? 'all') === 'all' ? 'selected' : '' }}>All Authors</option>
                    @foreach($authors as $author)
                    <option value="{{ $author->id }}"
                        {{ ($author_filter ?? '') == $author->id ? 'selected' : '' }}>
                        {{ $author->name ?? 'User #' . $author->id }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group">
                <label class="filter-label">Date</label>
                <select class="filter-select" name="date" id="dateFilter" onchange="this.form.submit()">
                    <option value="all" {{ ($date_filter ?? 'all') === 'all' ? 'selected' : '' }}>All Time</option>
                    <option value="today" {{ ($date_filter ?? '') === 'today' ? 'selected' : '' }}>Today</option>
                    <option value="week" {{ ($date_filter ?? '') === 'week' ? 'selected' : '' }}>This Week</option>
                    <option value="month" {{ ($date_filter ?? '') === 'month' ? 'selected' : '' }}>This Month</option>
                </select>
            </div>
        </div>
        <!-- Preserve page parameter -->
        <input type="hidden" name="page" value="{{ $_GET['page'] ?? 1 }}">
    </form>

    <button type="button" onclick="window.location.href='<?= route('admin.articles.create') ?>'" class="btn-custom btn-primary text-decoration-none" id="newPostBtn">
        <i class="bi bi-plus-lg"></i>
        New Post
    </button>
</div>

<!-- ========== DATA TABLE ========== -->
<div class="table-card">
    <div class="table-header">
        <h2 class="table-title">All Posts</h2>
        <div class="table-actions">
            <button class="btn-custom btn-secondary">
                <i class="bi bi-filter"></i>
                Filter
            </button>
            <button class="btn-custom btn-secondary">
                <i class="bi bi-download"></i>
                Export
            </button>
            <!-- Bulk Actions Button -->
            <div class="bulk-actions-container" id="bulkActionsContainer">
                <button class="btn-custom btn-secondary" id="bulkActionsButton">
                    <i class="bi bi-three-dots"></i>
                    Bulk Actions
                    <span id="selectedCount" class="ms-1">(0)</span>
                </button>
                <div class="bulk-actions-menu" id="bulkActionsMenu">
                    <div class="bulk-actions-header">
                        <span id="bulkActionsCount">0 items selected</span>
                    </div>
                    <ul class="bulk-actions-list">
                        <li class="bulk-action-item" data-action="publish">
                            <i class="bi bi-check-circle"></i>
                            Publish
                        </li>
                        <li class="bulk-action-item" data-action="draft">
                            <i class="bi bi-file-earmark"></i>
                            Move to Draft
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
    </div>

    <div class="table-container">
        <?php if ($articles): ?>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>
                            <input type="checkbox" class="table-checkbox" id="selectAll">
                        </th>
                        <th>Post</th>
                        <th>Author</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Views</th>
                        <th>Comments</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($articles as $article)
                    <tr>
                        <td>
                            <input type="checkbox" class="table-checkbox" name="article_ids[]" value="{{ $article->id }}" form="bulkActionsForm">
                        </td>
                        <td>
                            <div class="post-info">
                                @if($article->featured_image)
                                <img src="{{ $article->featured_image }}" alt="{{ $article->title }}" class="post-thumbnail">
                                @else
                                <div class="post-thumbnail placeholder">
                                    <i class="bi bi-image"></i>
                                </div>
                                @endif
                                <div class="post-details">
                                    <div class="post-title-text">@titleTruncate($article->title, 50)</div>
                                    <div class="post-excerpt">@truncate($article->excerpt)</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="author-info">
                                @php
                                $authorName = $article->author ? $article->author->name : 'Unknown';
                                $initials = substr($authorName, 0, 2);
                                @endphp
                                <div class="author-avatar">{{ $initials }}</div>
                                <span>{{ $authorName }}</span>
                            </div>
                        </td>
                        <td>
                            @php
                            $categoryName = $article->category ? $article->category->name : 'Uncategorized';
                            @endphp
                            <span>{{ $categoryName }}</span>
                        </td>
                        <td>
                            @php
                            $statusClass = [
                            'published' => 'status-published',
                            'draft' => 'status-draft',
                            'archived' => 'status-archived'
                            ][$article->status] ?? 'status-draft';
                            @endphp
                            <span class="status-badge {{ $statusClass }}">{{ ucfirst($article->status) }}</span>
                        </td>
                        <td>@number($article->view_count ?? 0)</td>
                        <td>@number($article->comment_count ?? 0)</td>
                        <td>
                            @if($article->published_at)
                            @diffForHumans($article->published_at)
                            @else
                            @diffForHumans($article->created_at)
                            @endif
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('admin.articles.edit', ['id' => $article->id]) }}" class="action-btn edit" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="{{ route('articles.show', ['id' => $article->id, 'slug' => $article->slug]) }}" target="_blank" class="action-btn view" title="View">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.articles.toggle-status', ['id' => $article->id]) }}" style="display: inline;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="action-btn status" title="{{ $article->status === 'published' ? 'Unpublish' : 'Publish' }}">
                                        <i class="bi bi-{{ $article->status === 'published' ? 'eye-slash' : 'eye' }}"></i>
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.articles.destroy', ['id' => $article->id]) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this article?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn delete" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        <?php else: ?>
            <!-- Empty State -->
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="bi bi-file-earmark-text"></i>
                </div>
                <h3 class="empty-state-title">No Articles Found</h3>
                <p>No articles match your current filters. Try adjusting your search criteria or create a new article.</p>
                <div class="empty-state-actions">
                    <button type="button" onclick="window.location.href='<?= route('admin.articles.create') ?>'" class="btn-custom btn-primary">
                        <i class="bi bi-plus-lg"></i>
                        Create Article
                    </button>
                    <button type="button" onclick="window.location.href='<?= route('admin.articles.index') ?>'" class="btn-custom btn-secondary">
                        <i class="bi bi-arrow-clockwise"></i>
                        Clear Filters
                    </button>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <?php if (isset($paginator) && $paginator && $paginator->hasPages()): ?>
        {{{ $paginator->render() }}}
    <?php endif; ?>
</div>

<!-- Bulk Actions Form -->
<form action="{{ route('admin.articles.bulk') }}" method="post" id="bulkActionsForm">
    @csrf
    <input type="hidden" name="action" id="bulkActionType" value="">
</form>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ========== SELECT ALL CHECKBOXES ==========
        const selectAllCheckbox = document.getElementById('selectAll');
        const tableCheckboxes = document.querySelectorAll('.data-table tbody .table-checkbox');
        const bulkActionsContainer = document.getElementById('bulkActionsContainer');
        const bulkActionsButton = document.getElementById('bulkActionsButton');
        const bulkActionsMenu = document.getElementById('bulkActionsMenu');
        const selectedCount = document.getElementById('selectedCount');
        const bulkActionsCount = document.getElementById('bulkActionsCount');
        const bulkActionType = document.getElementById('bulkActionType');
        const bulkActionsForm = document.getElementById('bulkActionsForm');

        function updateSelectedCount() {
            const checkedCount = Array.from(tableCheckboxes).filter(cb => cb.checked).length;
            selectedCount.textContent = `(${checkedCount})`;
            bulkActionsCount.textContent = `${checkedCount} item${checkedCount !== 1 ? 's' : ''} selected`;

            // Show/hide bulk actions container
            if (checkedCount > 0) {
                bulkActionsContainer.classList.add('show');
            } else {
                bulkActionsContainer.classList.remove('show');
                bulkActionsMenu.style.display = 'none';
            }
        }

        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                tableCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                updateSelectedCount();
            });
        }

        tableCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const allChecked = Array.from(tableCheckboxes).every(cb => cb.checked);
                const someChecked = Array.from(tableCheckboxes).some(cb => cb.checked);
                if (selectAllCheckbox) {
                    selectAllCheckbox.checked = allChecked;
                    selectAllCheckbox.indeterminate = someChecked && !allChecked;
                }
                updateSelectedCount();
            });
        });

        // ========== BULK ACTIONS MENU ==========
        if (bulkActionsButton) {
            bulkActionsButton.addEventListener('click', function(e) {
                e.stopPropagation();
                bulkActionsMenu.style.display = bulkActionsMenu.style.display === 'block' ? 'none' : 'block';
            });
        }

        // Close bulk actions menu when clicking outside
        document.addEventListener('click', function() {
            if (bulkActionsMenu) {
                bulkActionsMenu.style.display = 'none';
            }
        });

        // Handle bulk action selection
        document.querySelectorAll('.bulk-action-item').forEach(item => {
            item.addEventListener('click', function() {
                const action = this.getAttribute('data-action');
                const checkedItems = Array.from(tableCheckboxes).filter(cb => cb.checked);

                if (checkedItems.length === 0) {
                    alert('Please select at least one article.');
                    return;
                }

                let actionText;
                switch (action) {
                    case 'publish':
                        actionText = 'publish';
                        break;
                    case 'draft':
                        actionText = 'move to draft';
                        break;
                    case 'archive':
                        actionText = 'archive';
                        break;
                    case 'delete':
                        actionText = 'delete';
                        break;
                    default:
                        actionText = action;
                }

                if (confirm(`Are you sure you want to ${actionText} ${checkedItems.length} article${checkedItems.length !== 1 ? 's' : ''}?`)) {
                    bulkActionType.value = action;
                    bulkActionsForm.submit();
                }
            });
        });

        // ========== FILTER FUNCTIONALITY ==========
        const statusFilter = document.getElementById('statusFilter');
        const authorFilter = document.getElementById('authorFilter');
        const dateFilter = document.getElementById('dateFilter');

        [statusFilter, authorFilter, dateFilter].forEach(filter => {
            if (filter) {
                filter.addEventListener('change', function() {
                    document.getElementById('filterForm').submit();
                });
            }
        });
    });
</script>
@endpush