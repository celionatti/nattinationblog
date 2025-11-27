@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<h1 class="page-title">Dashboard</h1>
<p class="page-subtitle">Welcome back! Here's what's happening with your blog today.</p>

<!-- ========== STATS CARDS ========== -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon blue">
                <i class="bi bi-eye"></i>
            </div>
            <div class="stat-change up">
                <i class="bi bi-arrow-up"></i> 12.5%
            </div>
        </div>
        <div class="stat-value">24.8K</div>
        <div class="stat-label">Total Views</div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon green">
                <i class="bi bi-file-earmark-text"></i>
            </div>
            <div class="stat-change up">
                <i class="bi bi-arrow-up"></i> 8.2%
            </div>
        </div>
        <div class="stat-value">1,247</div>
        <div class="stat-label">Total Posts</div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon orange">
                <i class="bi bi-chat-dots"></i>
            </div>
            <div class="stat-change up">
                <i class="bi bi-arrow-up"></i> 15.3%
            </div>
        </div>
        <div class="stat-value">5,682</div>
        <div class="stat-label">Total Comments</div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon purple">
                <i class="bi bi-people"></i>
            </div>
            <div class="stat-change up">
                <i class="bi bi-arrow-up"></i> 6.7%
            </div>
        </div>
        <div class="stat-value">3,451</div>
        <div class="stat-label">Registered Users</div>
    </div>
</div>

<!-- ========== DASHBOARD GRID ========== -->
<div class="dashboard-grid">
    <!-- Left Column: Charts and Recent Posts -->
    <div>
        <!-- Traffic Chart -->
        <div class="chart-container">
            <div class="chart-header">
                <h3 class="chart-title">Traffic Overview</h3>
                <div class="chart-actions">
                    <button class="chart-btn active">7 Days</button>
                    <button class="chart-btn">30 Days</button>
                    <button class="chart-btn">90 Days</button>
                </div>
            </div>
            <div class="chart-placeholder">
                <i class="bi bi-bar-chart me-2"></i>
                Traffic Chart Visualization
            </div>
        </div>

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
                            <th>Status</th>
                            <th>Views</th>
                            <th>Comments</th>
                            <th>Date</th>
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
                                <span class="status-badge status-published">Published</span>
                            </td>
                            <td>2,548</td>
                            <td>45</td>
                            <td>2 hours ago</td>
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
                                <span class="status-badge status-published">Published</span>
                            </td>
                            <td>3,892</td>
                            <td>67</td>
                            <td>4 hours ago</td>
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
                                <span class="status-badge status-draft">Draft</span>
                            </td>
                            <td>1,245</td>
                            <td>23</td>
                            <td>6 hours ago</td>
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
                                <span class="status-badge status-pending">Pending</span>
                            </td>
                            <td>876</td>
                            <td>12</td>
                            <td>8 hours ago</td>
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
                                <span class="status-badge status-published">Published</span>
                            </td>
                            <td>4,521</td>
                            <td>89</td>
                            <td>1 day ago</td>
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
                                <span class="status-badge status-published">Published</span>
                            </td>
                            <td>5,234</td>
                            <td>102</td>
                            <td>1 day ago</td>
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
                                <span class="status-badge status-draft">Draft</span>
                            </td>
                            <td>2,103</td>
                            <td>34</td>
                            <td>2 days ago</td>
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
                                <span class="status-badge status-published">Published</span>
                            </td>
                            <td>6,789</td>
                            <td>156</td>
                            <td>2 days ago</td>
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
    </div>
</div>

<!-- ========== BOTTOM GRID ========== -->
<div class="bottom-grid">
    <!-- Activity Feed -->
    <div class="activity-feed">
        <div class="activity-header">
            <h3 class="activity-title">Recent Activity</h3>
            <button class="btn-custom btn-secondary">
                <i class="bi bi-arrow-clockwise"></i>
                Refresh
            </button>
        </div>
        <ul class="activity-list">
            <li class="activity-item">
                <div class="activity-icon post">
                    <i class="bi bi-file-earmark-plus"></i>
                </div>
                <div class="activity-content">
                    <div class="activity-text">
                        <strong>John Doe</strong> published a new post "Global Economic Trends"
                    </div>
                    <div class="activity-time">15 minutes ago</div>
                </div>
            </li>
            <li class="activity-item">
                <div class="activity-icon comment">
                    <i class="bi bi-chat-square-text"></i>
                </div>
                <div class="activity-content">
                    <div class="activity-text">
                        <strong>Sarah Adams</strong> commented on "AI Revolution"
                    </div>
                    <div class="activity-time">1 hour ago</div>
                </div>
            </li>
            <li class="activity-item">
                <div class="activity-icon user">
                    <i class="bi bi-person-plus"></i>
                </div>
                <div class="activity-content">
                    <div class="activity-text">
                        <strong>Mike Johnson</strong> registered as a new user
                    </div>
                    <div class="activity-time">2 hours ago</div>
                </div>
            </li>
            <li class="activity-item">
                <div class="activity-icon system">
                    <i class="bi bi-shield-check"></i>
                </div>
                <div class="activity-content">
                    <div class="activity-text">
                        System backup completed successfully
                    </div>
                    <div class="activity-time">3 hours ago</div>
                </div>
            </li>
        </ul>
    </div>

    <!-- Top Posts -->
    <div class="top-card">
        <div class="top-card-header">
            <h3 class="top-card-title">Top Posts</h3>
            <button class="btn-custom btn-secondary">
                <i class="bi bi-arrow-right"></i>
                View All
            </button>
        </div>
        <ul class="top-list">
            <li class="top-item">
                <div class="top-rank top-1">1</div>
                <div class="top-info">
                    <div class="top-name">AI Revolution: New Breakthrough Changes Everything</div>
                    <div class="top-meta">Technology • 3,892 views</div>
                </div>
                <div class="top-value">3.9K</div>
            </li>
            <li class="top-item">
                <div class="top-rank top-2">2</div>
                <div class="top-info">
                    <div class="top-name">Global Economic Trends 2024</div>
                    <div class="top-meta">World News • 2,548 views</div>
                </div>
                <div class="top-value">2.5K</div>
            </li>
            <li class="top-item">
                <div class="top-rank top-3">3</div>
                <div class="top-info">
                    <div class="top-name">10 Hidden Travel Gems for 2025</div>
                    <div class="top-meta">Travel • 2,103 views</div>
                </div>
                <div class="top-value">2.1K</div>
            </li>
            <li class="top-item">
                <div class="top-rank">4</div>
                <div class="top-info">
                    <div class="top-name">Quantum Computing Breakthrough</div>
                    <div class="top-meta">Technology • 1,876 views</div>
                </div>
                <div class="top-value">1.9K</div>
            </li>
        </ul>
    </div>

    <!-- Top Categories -->
    <div class="top-card">
        <div class="top-card-header">
            <h3 class="top-card-title">Top Categories</h3>
            <button class="btn-custom btn-secondary">
                <i class="bi bi-arrow-right"></i>
                View All
            </button>
        </div>
        <ul class="top-list">
            <li class="top-item">
                <div class="top-rank top-1">1</div>
                <div class="top-info">
                    <div class="top-name">Technology</div>
                    <div class="top-meta">342 posts • 45.2K views</div>
                </div>
                <div class="top-value">45K</div>
            </li>
            <li class="top-item">
                <div class="top-rank top-2">2</div>
                <div class="top-info">
                    <div class="top-name">World News</div>
                    <div class="top-meta">287 posts • 38.7K views</div>
                </div>
                <div class="top-value">39K</div>
            </li>
            <li class="top-item">
                <div class="top-rank top-3">3</div>
                <div class="top-info">
                    <div class="top-name">Sports</div>
                    <div class="top-meta">198 posts • 28.4K views</div>
                </div>
                <div class="top-value">28K</div>
            </li>
            <li class="top-item">
                <div class="top-rank">4</div>
                <div class="top-info">
                    <div class="top-name">Travel</div>
                    <div class="top-meta">156 posts • 22.1K views</div>
                </div>
                <div class="top-value">22K</div>
            </li>
        </ul>
    </div>
</div>
@endsection