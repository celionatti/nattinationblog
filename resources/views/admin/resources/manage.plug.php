@extends("layouts.admin")

@section('title', 'Admin Resources Management')

@push("styles")
<style>

</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-start mb-2">
    <div>
        <h1 class="page-title">{{ $page_title }}</h1>
        <p class="page-subtitle">{{ $page_subtitle }}</p>
    </div>

</div>

<?php if ($resources): ?>
    <!-- ========== STATS CARDS ========== -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon blue">
                    <i class="bi bi-file-earmark"></i>
                </div>
                <div class="stat-change up">
                    <i class="bi bi-arrow-up"></i> 12.5%
                </div>
            </div>
            <div class="stat-value">247</div>
            <div class="stat-label">Total Resources</div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon green">
                    <i class="bi bi-download"></i>
                </div>
                <div class="stat-change up">
                    <i class="bi bi-arrow-up"></i> 8.2%
                </div>
            </div>
            <div class="stat-value">1,568</div>
            <div class="stat-label">Total Downloads</div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon orange">
                    <i class="bi bi-currency-dollar"></i>
                </div>
                <div class="stat-change up">
                    <i class="bi bi-arrow-up"></i> 15.3%
                </div>
            </div>
            <div class="stat-value">$2,450</div>
            <div class="stat-label">Revenue Generated</div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon purple">
                    <i class="bi bi-person-check"></i>
                </div>
                <div class="stat-change up">
                    <i class="bi bi-arrow-up"></i> 6.7%
                </div>
            </div>
            <div class="stat-value">892</div>
            <div class="stat-label">Paid Downloads</div>
        </div>
    </div>
<?php endif; ?>

<!-- ========== RESOURCES TABLE ========== -->
<div class="table-card">
    <div class="table-header">
        <h2 class="table-title">All Resources</h2>
        <div class="table-actions">
            <button class="btn-custom btn-primary" onclick="window.location.href='<?= route('admin.resources.create') ?>'">
                <i class="bi bi-plus-lg"></i>
                Add Resource
            </button>

            <!-- <button class="btn-custom btn-secondary">
                <i class="bi bi-download"></i>
                Export
            </button> -->
        </div>
    </div>

    <div class="table-container">
        <?php if ($resources): ?>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Resource</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Price</th>
                        <th>Downloads</th>
                        <th>Date Added</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div class="resource-info">
                                <div class="resource-icon image">
                                    <i class="bi bi-image"></i>
                                </div>
                                <div class="resource-details">
                                    <div class="resource-title">Landscape Photography Collection</div>
                                    <div class="resource-meta">45.2 MB • JPG, PNG</div>
                                </div>
                            </div>
                        </td>
                        <td>Image</td>
                        <td>
                            <span class="status-badge status-published">Published</span>
                        </td>
                        <td>
                            <span class="price-badge price-paid">$12.99</span>
                        </td>
                        <td>245</td>
                        <td>2 days ago</td>
                        <td>
                            <div class="action-buttons">
                                <button class="action-btn edit" data-bs-toggle="modal" data-bs-target="#editResourceModal">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="action-btn delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                                <button class="action-btn">
                                    <i class="bi bi-download"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="resource-info">
                                <div class="resource-icon document">
                                    <i class="bi bi-file-earmark-pdf"></i>
                                </div>
                                <div class="resource-details">
                                    <div class="resource-title">Digital Marketing Guide 2024</div>
                                    <div class="resource-meta">12.8 MB • PDF</div>
                                </div>
                            </div>
                        </td>
                        <td>Document</td>
                        <td>
                            <span class="status-badge status-published">Published</span>
                        </td>
                        <td>
                            <span class="price-badge price-free">Free</span>
                        </td>
                        <td>892</td>
                        <td>1 week ago</td>
                        <td>
                            <div class="action-buttons">
                                <button class="action-btn edit" data-bs-toggle="modal" data-bs-target="#editResourceModal">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="action-btn delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                                <button class="action-btn">
                                    <i class="bi bi-download"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="resource-info">
                                <div class="resource-icon video">
                                    <i class="bi bi-play-circle"></i>
                                </div>
                                <div class="resource-details">
                                    <div class="resource-title">Web Development Masterclass</div>
                                    <div class="resource-meta">245.7 MB • MP4</div>
                                </div>
                            </div>
                        </td>
                        <td>Video</td>
                        <td>
                            <span class="status-badge status-published">Published</span>
                        </td>
                        <td>
                            <span class="price-badge price-paid">$24.99</span>
                        </td>
                        <td>156</td>
                        <td>3 weeks ago</td>
                        <td>
                            <div class="action-buttons">
                                <button class="action-btn edit" data-bs-toggle="modal" data-bs-target="#editResourceModal">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="action-btn delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                                <button class="action-btn">
                                    <i class="bi bi-download"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="resource-info">
                                <div class="resource-icon audio">
                                    <i class="bi bi-music-note-beamed"></i>
                                </div>
                                <div class="resource-details">
                                    <div class="resource-title">Productivity Podcast Series</div>
                                    <div class="resource-meta">87.3 MB • MP3</div>
                                </div>
                            </div>
                        </td>
                        <td>Audio</td>
                        <td>
                            <span class="status-badge status-draft">Draft</span>
                        </td>
                        <td>
                            <span class="price-badge price-free">Free</span>
                        </td>
                        <td>34</td>
                        <td>1 month ago</td>
                        <td>
                            <div class="action-buttons">
                                <button class="action-btn edit" data-bs-toggle="modal" data-bs-target="#editResourceModal">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="action-btn delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                                <button class="action-btn">
                                    <i class="bi bi-download"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        <?php else: ?>
            <!-- Empty State -->
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="bi bi-file-earmark-zip"></i>
                </div>
                <h3 class="empty-state-title">No Resources Found</h3>
                <p>No articles match your current filters. Try adjusting your search criteria or create a new resource.</p>
                <div class="empty-state-actions">
                    <button type="button" onclick="window.location.href='<?= route('admin.resources.create') ?>'" class="btn-custom btn-primary">
                        <i class="bi bi-plus-lg"></i>
                        Create Resource
                    </button>
                    <button type="button" onclick="window.location.href='<?= route('admin.resources.index') ?>'" class="btn-custom btn-secondary">
                        <i class="bi bi-arrow-clockwise"></i>
                        Clear Filters
                    </button>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
@endsection