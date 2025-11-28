@extends('layouts.admin')

@section('title', 'Admin Categories Management')

@section('content')
<h1 class="page-title">Event Types Management</h1>
<p class="page-subtitle">Organize your event types with categories and track their performance.</p>

<!-- ========== CATEGORIES GRID ========== -->
<div class="categories-grid">
    <!-- Add New Category Card -->
    <div class="add-category-card" onclick="window.location.href='/admin/event-types/create'">
        <div class="add-category-icon">
            <i class="bi bi-plus-lg"></i>
        </div>
        <div class="add-category-text">Add New Type</div>
        <div class="add-category-subtext">Create a new event type to organize your events</div>
    </div>

    <?php foreach ($types as $type): ?>
        <!-- Category Card -->
        <div class="category-card" data-category-id="{{ $type->id }}">
            <div class="category-header">
                <div class="category-color" style="background-color: <?= $type->color ?? '#6c757d' ?>;"></div>
            </div>
            <div class="category-body">
                <h3 class="category-title">
                    <i class="bi {{ $type->icon ?? 'bi-folder' }}"></i>
                    {{ $type->name }}
                </h3>
                <div class="category-slug">{{ $type->slug }}</div>
                <p class="category-description">{{ $type->description ?? 'No description provided.' }}</p>

                <div class="category-actions row">
                    <button class="col action-btn edit"
                        onclick="window.location.href='/admin/event-types/edit/{{ $type->id }}'">
                        <i class="bi bi-pencil"></i>
                        Edit
                    </button>
                    <form method="POST" action="/admin/event-types/delete/{{ $type->id }}" class="col"
                        style="display: inline;"
                        onsubmit="return confirm('Are you sure you want to delete {{ $type->name }}?')">
                        @csrf
                        @method('DELETE')
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

<?php if (empty($types) || count($types) === 0): ?>
    <!-- Empty State (Hidden by default) -->
    <div class="empty-state" id="emptyState">
        <div class="empty-state-icon">
            <i class="bi bi-folder-x"></i>
        </div>
        <h3 class="empty-state-title">No Event Type Found</h3>
        <p>Get started by creating your first event type to organize your content.</p>
        <button class="btn-custom btn-primary" style="margin-top: 1rem;"
            onclick="window.location.href='/admin/event-types/create'">
            <i class="bi bi-plus-lg"></i>
            Create Event Type
        </button>
    </div>
<?php endif; ?>

<?php if (isset($paginator) && $paginator && $paginator->hasPages()): ?>
    {{{ $paginator->render() }}}
<?php endif; ?>
@endsection