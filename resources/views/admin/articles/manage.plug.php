@extends('layouts.admin')

@section('title', 'Admin Articles Management')

@section('content')
<h1 class="page-title">Articles Management</h1>
<p class="page-subtitle">Manage all your blog articles, create new content, and track performance.</p>

<!-- ========== POSTS CONTROLS ========== -->
<div class="posts-controls">
    <div class="filters-container">
        <div class="filter-group">
            <label class="filter-label">Status</label>
            <select class="filter-select" id="statusFilter">
                <option value="all">All Status</option>
                <option value="published">Published</option>
                <option value="draft">Draft</option>
                <option value="pending">Pending</option>
                <option value="archived">Archived</option>
            </select>
        </div>
        <div class="filter-group">
            <label class="filter-label">Category</label>
            <select class="filter-select" id="categoryFilter">
                <option value="all">All Categories</option>
                <option value="world">World</option>
                <option value="technology">Technology</option>
                <option value="sports">Sports</option>
                <option value="travel">Travel</option>
                <option value="politics">Politics</option>
            </select>
        </div>
        <div class="filter-group">
            <label class="filter-label">Author</label>
            <select class="filter-select" id="authorFilter">
                <option value="all">All Authors</option>
                <option value="john">John Doe</option>
                <option value="sarah">Sarah Adams</option>
                <option value="mike">Mike Johnson</option>
                <option value="emily">Emily Chen</option>
            </select>
        </div>
        <div class="filter-group">
            <label class="filter-label">Date</label>
            <select class="filter-select" id="dateFilter">
                <option value="all">All Time</option>
                <option value="today">Today</option>
                <option value="week">This Week</option>
                <option value="month">This Month</option>
            </select>
        </div>
    </div>

    <a href="{{ url('admin/articles/new-article') }}" class="btn-custom btn-primary text-decoration-none" id="newPostBtn">
        <i class="bi bi-plus-lg"></i>
        New Post
    </a>
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
                        <li class="bulk-action-item" data-action="duplicate">
                            <i class="bi bi-copy"></i>
                            Duplicate
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
                <tr>
                    <td>
                        <input type="checkbox" class="table-checkbox">
                    </td>
                    <td>
                        <div class="post-info">
                            <img src="https://images.unsplash.com/photo-1504711434969-e33886168f5c?w=120&h=90&fit=crop"
                                alt="Post" class="post-thumbnail">
                            <div class="post-details">
                                <div class="post-title-text">Breaking: Major Development Reshapes Global
                                    Economy</div>
                                <div class="post-excerpt">In a stunning turn of events that has sent shockwaves through
                                    financial markets worldwide...</div>
                                <div class="post-category">Featured Article</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="author-info">
                            <div class="author-avatar">JD</div>
                            <span>John Doe</span>
                        </div>
                    </td>
                    <td>World</td>
                    <td>
                        <span class="status-badge status-published">Published</span>
                    </td>
                    <td>2,548</td>
                    <td>45</td>
                    <td>2 hours ago</td>
                    <td>
                        <div class="action-buttons">
                            <button class="action-btn edit" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="action-btn view" title="View">
                                <i class="bi bi-eye"></i>
                            </button>
                            <button class="action-btn delete" title="Delete">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="checkbox" class="table-checkbox">
                    </td>
                    <td>
                        <div class="post-info">
                            <img src="https://images.unsplash.com/photo-1526628953301-3e589a6a8b74?w=120&h=90&fit=crop"
                                alt="Post" class="post-thumbnail">
                            <div class="post-details">
                                <div class="post-title-text">AI Revolution: New Breakthrough Changes
                                    Everything</div>
                                <div class="post-excerpt">Artificial intelligence has reached a new milestone with the
                                    development of...</div>
                                <div class="post-category">Technology News</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="author-info">
                            <div class="author-avatar">SA</div>
                            <span>Sarah Adams</span>
                        </div>
                    </td>
                    <td>Technology</td>
                    <td>
                        <span class="status-badge status-published">Published</span>
                    </td>
                    <td>3,892</td>
                    <td>67</td>
                    <td>4 hours ago</td>
                    <td>
                        <div class="action-buttons">
                            <button class="action-btn edit" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="action-btn view" title="View">
                                <i class="bi bi-eye"></i>
                            </button>
                            <button class="action-btn delete" title="Delete">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="checkbox" class="table-checkbox">
                    </td>
                    <td>
                        <div class="post-info">
                            <img src="https://images.unsplash.com/photo-1461896836934-ffe607ba8211?w=120&h=90&fit=crop"
                                alt="Post" class="post-thumbnail">
                            <div class="post-details">
                                <div class="post-title-text">Champions League: Stunning Upset Shocks Fans
                                </div>
                                <div class="post-excerpt">Underdogs triumph in a spectacular match that will be
                                    remembered for years to come...</div>
                                <div class="post-category">Sports Update</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="author-info">
                            <div class="author-avatar">MJ</div>
                            <span>Mike Johnson</span>
                        </div>
                    </td>
                    <td>Sports</td>
                    <td>
                        <span class="status-badge status-draft">Draft</span>
                    </td>
                    <td>1,245</td>
                    <td>23</td>
                    <td>6 hours ago</td>
                    <td>
                        <div class="action-buttons">
                            <button class="action-btn edit" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="action-btn view" title="View">
                                <i class="bi bi-eye"></i>
                            </button>
                            <button class="action-btn delete" title="Delete">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="checkbox" class="table-checkbox">
                    </td>
                    <td>
                        <div class="post-info">
                            <img src="https://images.unsplash.com/photo-1495020689067-958852a7765e?w=120&h=90&fit=crop"
                                alt="Post" class="post-thumbnail">
                            <div class="post-details">
                                <div class="post-title-text">Climate Summit Reaches Historic Agreement</div>
                                <div class="post-excerpt">World leaders unite on ambitious climate targets that could
                                    reshape global policies...</div>
                                <div class="post-category">World News</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="author-info">
                            <div class="author-avatar">EC</div>
                            <span>Emily Chen</span>
                        </div>
                    </td>
                    <td>World</td>
                    <td>
                        <span class="status-badge status-pending">Pending</span>
                    </td>
                    <td>876</td>
                    <td>12</td>
                    <td>8 hours ago</td>
                    <td>
                        <div class="action-buttons">
                            <button class="action-btn edit" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="action-btn view" title="View">
                                <i class="bi bi-eye"></i>
                            </button>
                            <button class="action-btn delete" title="Delete">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="checkbox" class="table-checkbox">
                    </td>
                    <td>
                        <div class="post-info">
                            <img src="https://images.unsplash.com/photo-1483058712412-4245e9b90334?w=120&h=90&fit=crop"
                                alt="Post" class="post-thumbnail">
                            <div class="post-details">
                                <div class="post-title-text">10 Hidden Gems You Must Visit in 2025</div>
                                <div class="post-excerpt">Discover these breathtaking destinations before they become
                                    mainstream tourist spots...</div>
                                <div class="post-category">Travel Guide</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="author-info">
                            <div class="author-avatar">LM</div>
                            <span>Lisa Martinez</span>
                        </div>
                    </td>
                    <td>Travel</td>
                    <td>
                        <span class="status-badge status-published">Published</span>
                    </td>
                    <td>4,521</td>
                    <td>89</td>
                    <td>1 day ago</td>
                    <td>
                        <div class="action-buttons">
                            <button class="action-btn edit" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="action-btn view" title="View">
                                <i class="bi bi-eye"></i>
                            </button>
                            <button class="action-btn delete" title="Delete">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="checkbox" class="table-checkbox">
                    </td>
                    <td>
                        <div class="post-info">
                            <img src="https://images.unsplash.com/photo-1512941937669-90a1b58e7e9c?w=120&h=90&fit=crop"
                                alt="Post" class="post-thumbnail">
                            <div class="post-details">
                                <div class="post-title-text">Quantum Computing Makes Giant Leap Forward
                                </div>
                                <div class="post-excerpt">Scientists achieve breakthrough that brings practical quantum
                                    computing closer to reality...</div>
                                <div class="post-category">Tech Innovation</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="author-info">
                            <div class="author-avatar">DW</div>
                            <span>David Wilson</span>
                        </div>
                    </td>
                    <td>Technology</td>
                    <td>
                        <span class="status-badge status-archived">Archived</span>
                    </td>
                    <td>5,234</td>
                    <td>102</td>
                    <td>1 day ago</td>
                    <td>
                        <div class="action-buttons">
                            <button class="action-btn edit" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="action-btn view" title="View">
                                <i class="bi bi-eye"></i>
                            </button>
                            <button class="action-btn delete" title="Delete">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="table-footer">
        <div class="showing-info">
            Showing <strong>1-6</strong> of <strong>1,247</strong> posts
        </div>
        <ul class="pagination">
            <li>
                <button disabled>
                    <i class="bi bi-chevron-left"></i>
                </button>
            </li>
            <li><button class="active">1</button></li>
            <li><button>2</button></li>
            <li><button>3</button></li>
            <li><button>4</button></li>
            <li><button>5</button></li>
            <li><button>...</button></li>
            <li><button>208</button></li>
            <li>
                <button>
                    <i class="bi bi-chevron-right"></i>
                </button>
            </li>
        </ul>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // ========== SELECT ALL CHECKBOXES ==========
        const selectAllCheckbox = document.getElementById('selectAll');
        const tableCheckboxes = document.querySelectorAll('.data-table tbody .table-checkbox');
        const bulkActionsContainer = document.getElementById('bulkActionsContainer');
        const bulkActionsButton = document.getElementById('bulkActionsButton');
        const bulkActionsMenu = document.getElementById('bulkActionsMenu');
        const selectedCount = document.getElementById('selectedCount');
        const bulkActionsCount = document.getElementById('bulkActionsCount');

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

        selectAllCheckbox.addEventListener('change', function () {
            tableCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateSelectedCount();
        });

        tableCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function () {
                const allChecked = Array.from(tableCheckboxes).every(cb => cb.checked);
                const someChecked = Array.from(tableCheckboxes).some(cb => cb.checked);
                selectAllCheckbox.checked = allChecked;
                selectAllCheckbox.indeterminate = someChecked && !allChecked;
                updateSelectedCount();
            });
        });

        // ========== BULK ACTIONS MENU ==========
        bulkActionsButton.addEventListener('click', function (e) {
            e.stopPropagation();
            bulkActionsMenu.style.display = bulkActionsMenu.style.display === 'block' ? 'none' : 'block';
        });

        // Close bulk actions menu when clicking outside
        document.addEventListener('click', function () {
            bulkActionsMenu.style.display = 'none';
        });

        // Handle bulk action selection
        document.querySelectorAll('.bulk-action-item').forEach(item => {
            item.addEventListener('click', function () {
                const action = this.getAttribute('data-action');
                const checkedItems = Array.from(tableCheckboxes).filter(cb => cb.checked);

                if (checkedItems.length === 0) {
                    alert('Please select at least one item.');
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
                    case 'duplicate':
                        actionText = 'duplicate';
                        break;
                    case 'delete':
                        actionText = 'delete';
                        break;
                    default:
                        actionText = action;
                }

                if (confirm(`Are you sure you want to ${actionText} ${checkedItems.length} item${checkedItems.length !== 1 ? 's' : ''}?`)) {
                    console.log(`Performing ${action} on ${checkedItems.length} items`);
                    // Here you would typically make an API call to perform the action

                    // Close the menu
                    bulkActionsMenu.style.display = 'none';

                    // Show a success message
                    alert(`Successfully ${actionText}ed ${checkedItems.length} item${checkedItems.length !== 1 ? 's' : ''}`);

                    // Reset checkboxes
                    tableCheckboxes.forEach(cb => cb.checked = false);
                    selectAllCheckbox.checked = false;
                    selectAllCheckbox.indeterminate = false;
                    updateSelectedCount();
                }
            });
        });

        // ========== FILTER FUNCTIONALITY ==========
        const statusFilter = document.getElementById('statusFilter');
        const categoryFilter = document.getElementById('categoryFilter');
        const authorFilter = document.getElementById('authorFilter');
        const dateFilter = document.getElementById('dateFilter');

        [statusFilter, categoryFilter, authorFilter, dateFilter].forEach(filter => {
            filter.addEventListener('change', function () {
                console.log('Filter changed:', this.id, this.value);
                // In a real application, you would filter the table data here
            });
        });

        // ========== NEW POST BUTTON ==========
        const newPostBtn = document.getElementById('newPostBtn');
        newPostBtn.addEventListener('click', function () {
            console.log('Create new post');
            // In a real application, you would navigate to the post editor
        });

        // ========== ACTION BUTTONS ==========
        document.querySelectorAll('.action-btn.edit').forEach(btn => {
            btn.addEventListener('click', function (e) {
                e.stopPropagation();
                console.log('Edit post');
            });
        });

        document.querySelectorAll('.action-btn.view').forEach(btn => {
            btn.addEventListener('click', function (e) {
                e.stopPropagation();
                console.log('View post');
            });
        });

        document.querySelectorAll('.action-btn.delete').forEach(btn => {
            btn.addEventListener('click', function (e) {
                e.stopPropagation();
                if (confirm('Are you sure you want to delete this post?')) {
                    console.log('Delete post');
                }
            });
        });

        // ========== TABLE ROW CLICK ==========
        document.querySelectorAll(".data-table tbody tr").forEach((row) => {
            row.addEventListener("click", function (e) {
                if (
                    !e.target.closest(".table-checkbox") &&
                    !e.target.closest(".action-btn")
                ) {
                    console.log("View post details");
                }
            });
        });

        // ========== PAGINATION ==========
        document.querySelectorAll(".pagination button").forEach((button, index) => {
            if (!button.disabled && button.textContent.trim() !== "...") {
                button.addEventListener("click", function () {
                    document.querySelectorAll(".pagination button").forEach((btn) => {
                        btn.classList.remove("active");
                    });
                    if (!this.querySelector("i")) {
                        this.classList.add("active");
                    }
                    console.log("Navigate to page:", this.textContent);
                });
            }
        });
        // End....
    });
</script>
@endpush