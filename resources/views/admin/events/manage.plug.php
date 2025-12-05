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
                @foreach($events as $event)
                <tr>
                    <td>
                        <div class="post-info">
                            <img src="{{ $event->event_image }}"
                                alt="{{ $event->title }}" class="post-thumbnail">
                            <div class="post-details">
                                <div class="post-title-text">@titleTruncate($event->title, 50)</div>
                                <!-- <div class="post-category">World News</div> -->
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="author-info">
                            @php
                            $authorName = $event->author ? $event->author->name : 'Unknown';
                            $initials = substr($authorName, 0, 2);
                            @endphp
                            <div class="author-avatar text-uppercase">{{ $initials }}</div>
                            <span>{{ $authorName }}</span>
                        </div>
                    </td>
                    <td>
                        @php
                        $statusClass = [
                        'launched' => 'status-published',
                        'pending' => 'status-draft',
                        'cancelled' => 'status-archived'
                        ][$event->status] ?? 'status-draft';
                        @endphp
                        <span class="status-badge {{ $statusClass }}">{{ ucfirst($event->status) }}</span>
                    </td>
                    <td>
                        @if($event->published_at)
                        @diffForHumans($event->published_at)
                        @else
                        @diffForHumans($event->created_at)
                        @endif
                    </td>
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
                @endforeach
            </tbody>
        </table>

        <?php if (isset($paginator) && $paginator && $paginator->hasPages()): ?>
            {{{ $paginator->render() }}}
        <?php endif; ?>
    </div>
</div>
@endsection