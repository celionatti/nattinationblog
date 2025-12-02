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

<div class="form-card">
    <div class="form-card-header">
        <h3 class="form-card-title">Resource Detail</h3>
    </div>
    <div class="form-card-body">
        <form action="{{ route('admin.resources.store') }}" method="post" enctype="multipart/form-data" id="resourceForm">
            @csrf

            <div class="form-group">
                <label for="resourceTitle" class="form-label">Resource Title *</label>
                <input type="text" class="form-control" id="resourceTitle" name="title"
                    placeholder="Enter resource title" value="{{ old('title') }}" required>
                <div class="form-text">The title is how it appears on your site.</div>
                @error('title')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="resourceDescription" class="form-label">Description</label>
                <textarea class="form-control" id="resourceDescription" name="description"
                    rows="3" placeholder="Enter a description (optional)" required>{{ old('description') }}</textarea>
                <div class="form-text">The description is not prominent by default; however, some themes may show it.</div>
                @error('description')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="row gap-2">
                <div class="col form-group">
                    <label for="resourceType" class="form-label">Resource Type</label>
                    <select class="form-control" id="resourceType" name="file_type">
                        <option value="image" {{ old('file_type') == 'image' ? 'selected' : '' }}>Image</option>
                        <option value="document" {{ old('file_type') == 'document' ? 'selected' : '' }}>Document</option>
                        <option value="video" {{ old('file_type') == 'video' ? 'selected' : '' }}>Video</option>
                        <option value="audio" {{ old('file_type') == 'audio' ? 'selected' : '' }}>Audio</option>
                        <option value="other" {{ old('file_type') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    <div class="icon-preview">
                        <div class="icon-preview-box">
                            <i class="bi bi-image" id="iconPreview"></i>
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
                    <div class="form-text">The file you want to upload on your site.</div>
                    @error('file')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row gap-2">
                <div class="col form-group">
                    <label for="resourcePrice" class="form-label">Resource Price *</label>
                    <input type="number" class="form-control" id="resourcePrice" name="price"
                        placeholder="Pick resource price" value="{{ old('price') }}" required>
                    <div class="form-text">The file price you want to upload.</div>
                    @error('price')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col form-group">
                    <label for="resourceStatus" class="form-label">Resource Status</label>
                    <select class="form-control" id="resourceStatus" name="status">
                        <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
                        <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    </select>
                    <div class="form-text">The Resource status.</div>
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
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
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
</script>
@endsection