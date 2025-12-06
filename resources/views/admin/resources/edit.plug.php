@extends("layouts.admin")

@section('title', 'Admin Create Resource')

@section('content')
<div class="d-flex justify-content-between align-items-start mb-2">
    <div>
        <h1 class="page-title">{{ $page_title }}</h1>
        <p class="page-subtitle">{{ $page_subtitle }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.resources.index') }}" class="btn-custom btn-secondary text-decoration-none">
            <i class="bi bi-arrow-left"></i>
            Back to Resources
        </a>
    </div>
</div>

<!-- Resource Statistics (Read-only) -->
<div class="row gap-2 my-3">
    <div class="col">
        <div class="card bg-light">
            <div class="card-body">
                <h6 class="card-title">Resource Statistics</h6>
                <div class="row text-center">
                    <div class="col">
                        <div class="stat-value">{{ $resource->download_count }}</div>
                        <div class="stat-label">Total Downloads</div>
                    </div>
                    <div class="col">
                        <div class="stat-value">{{ $resource->paid_download_count }}</div>
                        <div class="stat-label">Paid Downloads</div>
                    </div>
                    <div class="col">
                        <div class="stat-value">${{ number_format($resource->revenue_generated, 2) }}</div>
                        <div class="stat-label">Revenue Generated</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="form-card">
    <div class="form-card-header">
        <h3 class="form-card-title">Resource Detail</h3>
    </div>
    <div class="form-card-body">
        <form action="{{ route('admin.resources.update', ['id' => $resource->id]) }}" method="post" enctype="multipart/form-data" id="resourceForm">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="resourceTitle" class="form-label">Resource Title *</label>
                <input type="text" class="form-control" id="resourceTitle" name="title"
                    placeholder="Enter resource title" value="{{ old('title', $resource->title) }}" required>
                <div class="form-text">The title is how it appears on your site.</div>
                @error('title')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="resourceDescription" class="form-label">Description</label>
                <textarea class="form-control" id="resourceDescription" name="description"
                    rows="3" placeholder="Enter a description (optional)" required>{{ old('description', $resource->description) }}</textarea>
                <div class="form-text">The description is not prominent by default; however, some themes may show it.</div>
                @error('description')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="row gap-2">
                <div class="col form-group">
                    <label for="resourceType" class="form-label">Resource Type</label>
                    <select class="form-control" id="resourceType" name="file_type">
                        <option value="image" {{ old('file_type', $resource->file_type) == 'image' ? 'selected' : '' }}>Image</option>
                        <option value="document" {{ old('file_type', $resource->file_type) == 'document' ? 'selected' : '' }}>Document</option>
                        <option value="video" {{ old('file_type', $resource->file_type) == 'video' ? 'selected' : '' }}>Video</option>
                        <option value="audio" {{ old('file_type', $resource->file_type) == 'audio' ? 'selected' : '' }}>Audio</option>
                        <option value="other" {{ old('file_type', $resource->file_type) == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    <div class="icon-preview">
                        <div class="icon-preview-box">
                            <i class="bi {{ $resource->file_type == 'image' ? 'bi-image' : ($resource->file_type == 'document' ? 'bi-file-earmark-text' : ($resource->file_type == 'video' ? 'bi-camera-video' : ($resource->file_type == 'audio' ? 'bi-music-note-beamed' : 'bi-file-earmark'))) }}" id="iconPreview"></i>
                        </div>
                        <span>This is how the icon will appear in the category list</span>
                    </div>
                    @error('icon')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col form-group">
                    <label for="resourceFile" class="form-label">Upload File *</label>
                    <input type="file" class="form-control" id="resourceFile" name="file"
                        placeholder="Pick resource file" value="{{ old('file') }}" required>
                    <div class="form-text">
                        <div> Current File:
                            <?php if ($resource->file_path): ?>
                                <a href="{{ $resource->file_path }}" target="_blank" class="text-decoration-none border-danger form-control">{{ $resource->file_name }}</a>
                            <?php else: ?>
                                <span class="text-danger">No file uploaded</span>
                            <?php endif; ?>
                        </div>
                        Leave empty to keep existing file. Uploading a new file will replace the current one.
                    </div>
                    @error('file')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="featured_image" class="form-label">Featured Image (Optional)</label>
                <input type="file" class="form-control" id="featured_image" name="featured_image"
                    accept="image/*">

                <!-- Current featured image preview -->
                @if($resource->featured_image)
                <div class="mt-2">
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <span>Current featured image:</span>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remove_featured_image" value="1" id="removeFeaturedImage">
                            <label class="form-check-label text-danger" for="removeFeaturedImage">
                                Remove image
                            </label>
                        </div>
                    </div>
                    <div class="featured-image-preview">
                        <img src="{{ $resource->featured_image }}" alt="Featured image" class="img-thumbnail" style="max-height: 200px;">
                    </div>
                </div>
                @endif

                <div class="form-text">
                    Upload a featured image for this resource (optional).
                    @if($resource->featured_image)
                    Check "Remove image" to delete the current featured image.
                    @endif
                </div>
                @error('featured_image')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="row gap-2">
                <div class="col form-group">
                    <label for="resourcePrice" class="form-label">Resource Price *</label>
                    <input type="number" step="0.01" class="form-control" id="resourcePrice" name="price"
                        placeholder="Pick resource price" value="{{ old('price', $resource->price) }}" required>
                    <div class="form-text">
                        The file price you want to upload.
                        @if($resource->is_free)
                        <span class="text-success">Currently set as FREE</span>
                        @else
                        <span class="text-info">Currently set as PAID: {{ formatMoney($resource->price) }}</span>
                        @endif
                    </div>
                    @error('price')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col form-group">
                    <label for="resourceStatus" class="form-label">Resource Status</label>
                    <select class="form-control" id="resourceStatus" name="status">
                        <option value="published" {{ old('status', $resource->status) == 'published' ? 'selected' : '' }}>Published</option>
                        <option value="draft" {{ old('status', $resource->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="pending" {{ old('status', $resource->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                    </select>
                    <div class="form-text">
                        Current status: <span class="badge bg-{{ $resource->status == 'published' ? 'success' : ($resource->status == 'draft' ? 'warning' : 'info') }}">
                            {{ ucfirst($resource->status) }}
                        </span>
                        @if($resource->published_at)
                        <br>Published on: {{ date('M d, Y H:i', strtotime($resource->published_at)) }}
                        @endif
                    </div>
                    @error('status')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <input type="hidden" name="action" id="formAction" value="published">

            <div class="form-actions p-3 gap-3 d-flex justify-content-between">
                <div>
                    <a href="{{ route('admin.resources.index') }}" class="btn-custom btn-secondary text-decoration-none" id="cancelButton">
                        <i class="bi bi-x-lg"></i>
                        Cancel
                    </a>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn-custom btn-outline-secondary" id="saveDraftButton" onclick="setAction('draft')">
                        <i class="bi bi-save"></i>
                        Save as Draft
                    </button>
                    <button type="submit" form="resourceForm" class="btn-custom btn-success" id="saveButton">
                        <i class="bi bi-check-lg"></i>
                        Publish Resource
                    </button>
                    <!-- Delete button -->
                    <button type="button" class="btn-custom btn bg-danger text-white" id="deleteButton" onclick="confirmDelete()">
                        <i class="bi bi-trash"></i>
                        Delete
                    </button>
                </div>
            </div>
        </form>

        <!-- Delete Form -->
        <form id="deleteForm" action="{{ route('admin.resources.destroy', ['id' => $resource->id]) }}" method="POST" class="d-none">
            @csrf
            @method('DELETE')
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        function setAction(action) {
            document.getElementById('formAction').value = action;
            document.getElementById('resourceForm').submit();
        }

        // Update icon preview based on file type
        document.getElementById('resourceType').addEventListener('change', function() {
            const iconPreview = document.getElementById('iconPreview');
            const type = this.value;

            const icons = {
                'image': 'bi-image',
                'video': 'bi-camera-video',
                'audio': 'bi-music-note-beamed',
                'document': 'bi-file-earmark-text',
                'other': 'bi-file-earmark'
            };

            iconPreview.className = 'bi ' + (icons[type] || 'bi-file-earmark');
        });

        // File size validation
        document.getElementById('resourceFile').addEventListener('change', function() {
            const file = this.files[0];
            if (!file) return;

            const fileType = document.getElementById('resourceType').value;
            const maxSizes = {
                'image': 10 * 1024 * 1024, // 10MB
                'video': 100 * 1024 * 1024, // 100MB
                'audio': 50 * 1024 * 1024, // 50MB
                'document': 20 * 1024 * 1024, // 20MB
                'other': 50 * 1024 * 1024 // 50MB
            };

            if (file && file.size > maxSizes[fileType]) {
                alert(`File is too large. Maximum size for ${fileType} files is ${maxSizes[fileType] / (1024*1024)}MB.`);
                this.value = '';
            }
        });

        // Featured image validation
        document.getElementById('featured_image').addEventListener('change', function() {
            const file = this.files[0];
            if (!file) return;

            // Check if it's an image
            if (!file.type.startsWith('image/')) {
                alert('Please select an image file for the featured image.');
                this.value = '';
                return;
            }

            // Check file size (max 5MB for featured images)
            const maxSize = 5 * 1024 * 1024; // 5MB
            if (file.size > maxSize) {
                alert('Featured image is too large. Maximum size is 5MB.');
                this.value = '';
            }
        });

        // Form validation before submission
        document.getElementById('resourceForm').addEventListener('submit', function(e) {
            const price = document.getElementById('resourcePrice').value;
            if (price < 0) {
                e.preventDefault();
                alert('Price cannot be negative.');
                return false;
            }

            const fileInput = document.getElementById('resourceFile');
            const fileType = document.getElementById('resourceType').value;

            if (fileInput.files.length > 0) {
                const file = fileInput.files[0];
                const maxSizes = {
                    'image': 10 * 1024 * 1024,
                    'video': 100 * 1024 * 1024,
                    'audio': 50 * 1024 * 1024,
                    'document': 20 * 1024 * 1024,
                    'other': 50 * 1024 * 1024
                };

                if (file.size > maxSizes[fileType]) {
                    e.preventDefault();
                    alert(`File is too large. Maximum size for ${fileType} files is ${maxSizes[fileType] / (1024*1024)}MB.`);
                    return false;
                }
            }

            return true;
        });

        // Delete confirmation
        window.confirmDelete = function() {
            if (confirm('Are you sure you want to delete this resource? This action cannot be undone.')) {
                document.getElementById('deleteForm').submit();
            }
        };
    });
</script>
@endpush