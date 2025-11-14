@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<h1 class="page-title">Dashboard</h1>
<p class="page-subtitle">Welcome back! Here's what's happening with your blog today.</p>

<!-- ========== DATA TABLE ========== -->
<div class="table-card">
    <div class="table-header">
        <h2 class="table-title">Recent Posts</h2>
        <div class="table-actions">
            <button class="btn-custom btn-primary">
                <i class="bi bi-plus-lg"></i>
                New Post
            </button>
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
                            <button class="action-btn" title="View">
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
                            <button class="action-btn" title="View">
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
                            <button class="action-btn" title="View">
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
                            <button class="action-btn" title="View">
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
                            <button class="action-btn" title="View">
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
                        <span class="status-badge status-published">Published</span>
                    </td>
                    <td>5,234</td>
                    <td>102</td>
                    <td>1 day ago</td>
                    <td>
                        <div class="action-buttons">
                            <button class="action-btn edit" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="action-btn" title="View">
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
                            <img src="https://images.unsplash.com/photo-1478720568477-152d9b164e26?w=120&h=90&fit=crop"
                                alt="Post" class="post-thumbnail">
                            <div class="post-details">
                                <div class="post-title-text">Box Office: New Blockbuster Breaks Records
                                </div>
                                <div class="post-category">Entertainment</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="author-info">
                            <div class="author-avatar">RT</div>
                            <span>Rachel Taylor</span>
                        </div>
                    </td>
                    <td>Movies</td>
                    <td>
                        <span class="status-badge status-draft">Draft</span>
                    </td>
                    <td>2,103</td>
                    <td>34</td>
                    <td>2 days ago</td>
                    <td>
                        <div class="action-buttons">
                            <button class="action-btn edit" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="action-btn" title="View">
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
                            <img src="https://images.unsplash.com/photo-1529107386315-e1a2ed48a620?w=120&h=90&fit=crop"
                                alt="Post" class="post-thumbnail">
                            <div class="post-details">
                                <div class="post-title-text">Senate Passes Major Infrastructure Bill</div>
                                <div class="post-category">Politics</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="author-info">
                            <div class="author-avatar">KB</div>
                            <span>Kevin Brown</span>
                        </div>
                    </td>
                    <td>Politics</td>
                    <td>
                        <span class="status-badge status-published">Published</span>
                    </td>
                    <td>6,789</td>
                    <td>156</td>
                    <td>2 days ago</td>
                    <td>
                        <div class="action-buttons">
                            <button class="action-btn edit" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="action-btn" title="View">
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
            Showing <strong>1-8</strong> of <strong>1,247</strong> posts
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
            <li><button>156</button></li>
            <li>
                <button>
                    <i class="bi bi-chevron-right"></i>
                </button>
            </li>
        </ul>
    </div>
</div>
@endsection