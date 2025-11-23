@extends('layouts.admin')

@section('title', 'Admin Events Management')

@section('content')
<h1 class="page-title">Events Management</h1>
<p class="page-subtitle">Manage all your events, create new event, and track performance.</p>

<!-- ========== POSTS CONTROLS ========== -->
<div class="posts-controls">
    <button type="button" onclick="window.location.href='<?= route('admin.events.create') ?>'" class="btn-custom btn-primary text-decoration-none" id="newEventBtn">
        <i class="bi bi-plus-lg"></i>
        New Event
    </button>
    <button type="button" onclick="window.location.href='<?= route('admin.events.create') ?>'" class="btn-custom btn-success text-decoration-none" id="upcomingEventBtn">
        <i class="bi bi-sort-up"></i>
        Upcoming Events
    </button>
    <button type="button" onclick="window.location.href='<?= route('admin.events.create') ?>'" class="btn-custom btn-info" id="concludedEventBtn">
        <i class="bi bi-sort-down"></i>
        Concluded Events
    </button>
</div>

<div class="table-card">
    <div class="table-header">
        <h2 class="table-title">Recent Events</h2>
    </div>

    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Post</th>
                    <th>User</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div class="post-info">
                            <img src="https://images.unsplash.com/photo-1504711434969-e33886168f5c?w=120&h=90&fit=crop"
                                alt="Post" class="post-thumbnail">
                            <div class="post-details">
                                <div class="post-title-text">Breaking: Major Development Reshapes Global
                                    Economy</div>
                                <div class="post-category">World News</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="author-info">
                            <div class="author-avatar text-uppercase">AM</div>
                            <span>Amisu Usman</span>
                        </div>
                    </td>
                    <td>
                        <span class="status-badge status-published">Published</span>
                    </td>
                    <td>2 hours ago</td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('admin.events.show', ['id' => $event->id ?? 0, 'slug' => $event->slug ?? '']) }}" class="action-btn view" title="View">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('admin.events.edit', ['id' => $event->id ?? 0]) }}" class="action-btn edit" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.events.destroy', ['id' => $event->id ?? 0]) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this event?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn delete" title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection