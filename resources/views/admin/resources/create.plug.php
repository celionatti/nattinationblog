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
@endsection