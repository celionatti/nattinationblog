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
  <?php
  // Calculate statistics based on actual data
  $totalResources = $paginator->total();
  $totalDownloads = 0;
  $totalRevenue = 0.00;
  $totalPaidDownloads = 0;

  foreach ($resources as $resource) {
    $totalDownloads += $resource->download_count;
    $totalRevenue += $resource->revenue_generated;
    $totalPaidDownloads += $resource->paid_download_count;
  }

  // Format numbers
  $formattedRevenue = number_format($totalRevenue, 2);
  ?>
  <!-- ========== STATS CARDS ========== -->
  <div class="stats-grid">
    <div class="stat-card">
      <div class="stat-header">
        <div class="stat-icon blue">
          <i class="bi bi-file-earmark"></i>
        </div>
        <div class="stat-change up">
          <i class="bi bi-arrow-up"></i>
          <?php
          // Calculate percentage change (placeholder logic)
          $percentageChange = $totalResources > 0 ? min(20, floor($totalResources / 20)) : 0;
          echo $percentageChange . '%';
          ?>
        </div>
      </div>
      <div class="stat-value">{{ number_format($totalResources) }}</div>
      <div class="stat-label">Total Resources</div>
    </div>

    <div class="stat-card">
      <div class="stat-header">
        <div class="stat-icon green">
          <i class="bi bi-download"></i>
        </div>
        <div class="stat-change up">
          <i class="bi bi-arrow-up"></i>
          <?php
          $percentageChange = $totalDownloads > 0 ? min(15, floor($totalDownloads / 100)) : 0;
          echo $percentageChange . '%';
          ?>
        </div>
      </div>
      <div class="stat-value">{{ number_format($totalDownloads) }}</div>
      <div class="stat-label">Total Downloads</div>
    </div>

    <div class="stat-card">
      <div class="stat-header">
        <div class="stat-icon orange">
          <i class="bi bi-currency-dollar"></i>
        </div>
        <div class="stat-change up">
          <i class="bi bi-arrow-up"></i>
          <?php
          $percentageChange = $totalRevenue > 0 ? min(18, floor($totalRevenue / 100)) : 0;
          echo $percentageChange . '%';
          ?>
        </div>
      </div>
      <div class="stat-value">${{ $formattedRevenue }}</div>
      <div class="stat-label">Revenue Generated</div>
    </div>

    <div class="stat-card">
      <div class="stat-header">
        <div class="stat-icon purple">
          <i class="bi bi-person-check"></i>
        </div>
        <div class="stat-change up">
          <i class="bi bi-arrow-up"></i>
          <?php
          $percentageChange = $totalPaidDownloads > 0 ? min(12, floor($totalPaidDownloads / 50)) : 0;
          echo $percentageChange . '%';
          ?>
        </div>
      </div>
      <div class="stat-value">{{ number_format($totalPaidDownloads) }}</div>
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

      <button class="btn-custom btn-secondary" type="button" onclick="exportResources()">
        <i class="bi bi-download"></i>
        Export
      </button>
    </div>
  </div>

  <div class="table-container">
    <?php if ($resources): ?>
      <table class="data-table">
        <thead>
          <tr>
            <th>Resource</th>
            <th>Status</th>
            <th>Price</th>
            <th>Downloads</th>
            <th>Date Added</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($resources as $resource): ?>
            <?php
            // Format file size
            $fileSize = $resource->file_size;
            $fileSizeFormatted = '';
            if ($fileSize >= 1048576) {
              $fileSizeFormatted = number_format($fileSize / 1048576, 1) . ' MB';
            } elseif ($fileSize >= 1024) {
              $fileSizeFormatted = number_format($fileSize / 1024, 1) . ' KB';
            } else {
              $fileSizeFormatted = $fileSize . ' B';
            }

            // Determine icon class based on file type
            $iconClass = 'bi-file-earmark';
            $iconType = 'other';
            switch ($resource->file_type) {
              case 'image':
                $iconClass = 'bi-image';
                $iconType = 'image';
                break;
              case 'video':
                $iconClass = 'bi-play-circle';
                $iconType = 'video';
                break;
              case 'audio':
                $iconClass = 'bi-music-note-beamed';
                $iconType = 'audio';
                break;
              case 'document':
                $iconClass = 'bi-file-earmark-pdf';
                $iconType = 'document';
                break;
              default:
                $iconClass = 'bi-file-earmark';
                $iconType = 'other';
            }

            // Status badge class
            $statusClass = 'status-' . $resource->status;
            $statusText = ucfirst($resource->status);

            // Price badge
            $isFree = $resource->is_free || $resource->price <= 0;
            $priceClass = $isFree ? 'price-free' : 'price-paid';
            $priceText = $isFree ? 'Free' : formatMoney($resource->price);

            // Get file extension
            $fileExtension = strtoupper($resource->file_extension ?? '');
            ?>
            <tr>
              <td>
                <div class="resource-info">
                  <div class="resource-icon {{ $iconType }}">
                    <i class="bi {{ $iconClass }}"></i>
                  </div>
                  <div class="resource-details">
                    <div class="resource-title" title="{{ $resource->title }}">{{ $resource->title }}</div>
                    <div class="resource-meta">
                      <span>{{ $fileSizeFormatted }}</span>
                      <?php if ($fileExtension): ?>
                        <span>•</span>
                        <span>{{ $fileExtension }}</span>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
              </td>
              <td>
                <span class="status-badge {{ $statusClass }}">{{ $statusText }}</span>
              </td>
              <td>
                <span class="price-badge {{ $priceClass }}">{{ $priceText }}</span>
              </td>
              <td>{{ number_format($resource->download_count) }}</td>
              <td>@diffForHumans($resource->created_at)</td>
              <td>
                <div class="action-buttons">
                  <button class="action-btn edit" onclick="window.location.href='<?= route('admin.resources.edit', ['id' => $resource->id]) ?>'">
                    <i class="bi bi-pencil"></i>
                  </button>
                  <form method="POST" action="{{ route('admin.resources.destroy', ['id' => $resource->id ?? 0]) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this resource?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="action-btn delete" title="Delete">
                      <i class="bi bi-trash"></i>
                    </button>
                  </form>
                  <button class="action-btn" onclick="window.location.href='<?= route('admin.resources.download', ['id' => $resource->id]) ?>'">
                    <i class="bi bi-download"></i>
                  </button>
                  <button class="action-btn"
                    onclick="window.location.href='<?= route('admin.resources.show', ['id' => $resource->id]) ?>'">
                    <i class="bi bi-eye"></i>
                  </button>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <?php if (isset($paginator) && $paginator && $paginator->hasPages()): ?>
        {{{ $paginator->render() }}}
      <?php endif; ?>

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