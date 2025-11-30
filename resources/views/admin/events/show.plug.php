<?php

use Plugs\Utils\Str;

?>

@extends('layouts.admin')

@section('title', 'Admin Show Event Details')

@push("styles")
<style>
    /* ========== ENHANCED EVENT HEADER ========== */
    .event-header {
        background: linear-gradient(135deg, var(--bg-primary) 0%, var(--bg-secondary) 100%);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 12px var(--card-shadow);
        position: relative;
        overflow: hidden;
    }

    .event-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: linear-gradient(to bottom, var(--accent), var(--accent-hover));
    }

    .event-info {
        display: flex;
        align-items: flex-start;
        gap: 1.5rem;
        flex: 1;
    }

    .event-thumbnail {
        width: 120px;
        height: 90px;
        border-radius: 8px;
        object-fit: cover;
        flex-shrink: 0;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .event-details {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        flex: 1;
    }

    .event-title {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
        line-height: 1.2;
    }

    .event-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 1.5rem;
        font-size: 0.95rem;
        color: var(--text-secondary);
        margin-bottom: 1rem;
    }

    .event-meta i {
        margin-right: 0.5rem;
        color: var(--accent);
    }

    .event-description {
        color: var(--text-secondary);
        line-height: 1.6;
        max-width: 600px;
    }

    .event-stats {
        display: flex;
        gap: 2rem;
        font-size: 0.9rem;
        background: var(--bg-secondary);
        padding: 1.25rem;
        border-radius: 10px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
    }

    .stat-item {
        text-align: center;
        flex: 1;
    }

    .stat-value {
        font-weight: 700;
        font-size: 1.3rem;
        color: var(--text-primary);
        display: block;
    }

    .stat-label {
        font-size: 0.8rem;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .event-actions {
        display: flex;
        gap: 1rem;
        flex-shrink: 0;
        margin-top: 1rem;
    }

    /* ========== ENHANCED TABS NAVIGATION ========== */
    .tabs-navigation {
        background-color: var(--bg-primary);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 0;
        margin-bottom: 2rem;
        overflow: hidden;
        box-shadow: 0 2px 8px var(--card-shadow);
    }

    .tabs-list {
        display: flex;
        list-style: none;
        margin: 0;
        padding: 0;
        overflow-x: auto;
    }

    .tab-item {
        flex: 1;
        min-width: 120px;
    }

    .tab-link {
        display: block;
        padding: 1rem 1.5rem;
        text-align: center;
        text-decoration: none;
        color: var(--text-secondary);
        font-weight: 500;
        border-bottom: 3px solid transparent;
        transition: all 0.3s ease;
        white-space: nowrap;
        position: relative;
    }

    .tab-link:hover {
        background-color: var(--accent-light);
        color: var(--accent);
    }

    .tab-link.active {
        color: var(--accent);
        border-bottom-color: var(--accent);
        background-color: var(--accent-light);
    }

    .tab-link.active::after {
        content: '';
        position: absolute;
        bottom: -3px;
        left: 0;
        width: 100%;
        height: 3px;
        background: var(--accent);
    }

    /* ========== ENHANCED CONTENT CARDS ========== */
    .content-card {
        background-color: var(--bg-primary);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 2px 8px var(--card-shadow);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .content-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px var(--card-shadow);
    }

    .card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        gap: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--border-color);
    }

    .card-title {
        font-size: 1.4rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .card-title::before {
        content: '';
        display: block;
        width: 4px;
        height: 20px;
        background: var(--accent);
        border-radius: 2px;
    }

    /* ========== ENHANCED TICKET TYPES ========== */
    .ticket-types {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .ticket-type-card {
        background-color: var(--bg-secondary);
        border: 1px solid var(--border-color);
        border-radius: 10px;
        padding: 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .ticket-type-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--accent);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .ticket-type-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .ticket-type-card:hover::before {
        opacity: 1;
    }

    .ticket-type-info {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex: 1;
    }

    .ticket-type-badge {
        width: 60px;
        height: 45px;
        border-radius: 6px;
        background: linear-gradient(135deg, var(--accent), var(--accent-hover));
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 0.9rem;
        flex-shrink: 0;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .ticket-type-details {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .ticket-type-name {
        font-weight: 600;
        color: var(--text-primary);
        font-size: 1.1rem;
    }

    .ticket-type-meta {
        display: flex;
        gap: 1rem;
        font-size: 0.9rem;
        color: var(--text-secondary);
        flex-wrap: wrap;
    }

    .ticket-type-actions {
        display: flex;
        gap: 0.5rem;
    }

    /* ========== PROGRESS BARS ========== */
    .ticket-progress {
        margin-top: 0.75rem;
        width: 100%;
    }

    .progress {
        height: 6px;
        background-color: var(--bg-tertiary);
        border-radius: 3px;
        overflow: hidden;
        margin-bottom: 0.5rem;
    }

    .progress-bar {
        height: 100%;
        background: linear-gradient(to right, var(--accent), var(--accent-hover));
        border-radius: 3px;
    }

    .progress-text {
        font-size: 0.8rem;
        color: var(--text-secondary);
        display: flex;
        justify-content: space-between;
    }

    /* ========== ANALYTICS STYLES ========== */
    .analytics-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .analytics-card {
        background: var(--bg-secondary);
        border-radius: 10px;
        padding: 1.5rem;
        border: 1px solid var(--border-color);
        text-align: center;
    }

    .analytics-value {
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .analytics-label {
        font-size: 0.9rem;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .chart-container {
        background: var(--bg-secondary);
        border-radius: 10px;
        padding: 1.5rem;
        border: 1px solid var(--border-color);
        margin-bottom: 1.5rem;
    }

    .chart-title {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 1rem;
        color: var(--text-primary);
    }

    .simple-chart {
        height: 200px;
        display: flex;
        align-items: end;
        gap: 0.5rem;
        padding: 1rem 0;
    }

    .chart-bar {
        flex: 1;
        background: linear-gradient(to top, var(--accent), var(--accent-hover));
        border-radius: 4px 4px 0 0;
        position: relative;
        transition: all 0.3s ease;
    }

    .chart-bar:hover {
        opacity: 0.8;
        transform: translateY(-2px);
    }

    .chart-label {
        position: absolute;
        bottom: -25px;
        left: 0;
        right: 0;
        text-align: center;
        font-size: 0.8rem;
        color: var(--text-secondary);
    }

    .chart-value {
        position: absolute;
        top: -25px;
        left: 0;
        right: 0;
        text-align: center;
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--text-primary);
    }

    /* ========== MONEY CONFIGURATION ========== */
    .money-value {
        font-family: 'Courier New', monospace;
        font-weight: 600;
    }

    /* ========== FORM ENHANCEMENTS ========== */
    .form-card {
        background-color: var(--bg-primary);
        border-radius: 12px;
        box-shadow: 0 4px 12px var(--card-shadow);
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .form-card-header {
        padding: 1.5rem 2rem;
        border-bottom: 1px solid var(--border-color);
        background: linear-gradient(to right, var(--bg-primary), var(--bg-secondary));
    }

    .form-card-title {
        font-size: 1.3rem;
        font-weight: 700;
        margin: 0;
        color: var(--text-primary);
    }

    .form-card-body {
        padding: 2rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: var(--text-primary);
    }

    .form-control {
        border-radius: 8px;
        border: 1px solid var(--border-color);
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: var(--accent);
        box-shadow: 0 0 0 3px var(--accent-light);
    }

    /* ========== RESPONSIVE IMPROVEMENTS ========== */
    @media (max-width: 768px) {
        .event-info {
            flex-direction: column;
        }

        .event-stats {
            flex-wrap: wrap;
            justify-content: space-around;
        }

        .stat-item {
            min-width: 45%;
            margin-bottom: 1rem;
        }

        .ticket-type-card {
            flex-direction: column;
            align-items: flex-start;
        }

        .ticket-type-actions {
            width: 100%;
            justify-content: flex-end;
        }

        .analytics-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-start mb-4">
    <div>
        <h1 class="page-title">Manage Event</h1>
        <p class="page-subtitle">Edit event details, manage tickets, and track performance.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.events.index') }}" class="btn-custom btn-secondary text-decoration-none">
            <i class="bi bi-arrow-left"></i>
            Back to Events
        </a>
    </div>
</div>

<!-- ========== ENHANCED EVENT HEADER ========== -->
<div class="event-header">
    <div class="event-info">
        <img src="{{ $event->event_image ?: 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=240&h=180&fit=crop' }}" alt="{{ $event->title }}" class="event-thumbnail">
        <div class="event-details">
            <h2 class="event-title">{{ $event->title }}</h2>
            <div class="event-meta">
                <span><i class="bi bi-calendar-event"></i> {{ $event->event_date }} at {{ $event->event_time }}</span>
                <span><i class="bi bi-geo-alt"></i> {{ $event->location }}</span>
                <span><i class="bi bi-people"></i> {{ $totalAttendees ?? 0 }}+ Attendees</span>
                <span class="badge status-{{ $event->status === 'launched' ? 'published' : ($event->status === 'pending' ? 'warning' : 'danger') }}">{{ ucfirst($event->status) }}</span>
            </div>
            <p class="event-description">
                {{ Str::limit(strip_tags($event->content), 200) }}
            </p>
        </div>
    </div>
    <div class="event-stats">
        <div class="stat-item">
            <span class="stat-value">{{ $totalTickets ?? 0 }}</span>
            <span class="stat-label">Total Tickets</span>
        </div>
        <div class="stat-item">
            <span class="stat-value">{{ $soldTickets ?? 0 }}</span>
            <span class="stat-label">Sold</span>
        </div>
        <div class="stat-item">
            <span class="stat-value">{{ $availableTickets ?? 0 }}</span>
            <span class="stat-label">Available</span>
        </div>
        <div class="stat-item">
            <span class="stat-value">{{ formatMoney($totalRevenue ?? 0) }}</span>
            <span class="stat-label">Revenue</span>
        </div>
    </div>
</div>

<!-- ========== ENHANCED TABS NAVIGATION ========== -->
<div class="tabs-navigation">
    <ul class="tabs-list">
        <li class="tab-item">
            <a href="#event-details" class="tab-link active">Event Details</a>
        </li>
        <li class="tab-item">
            <a href="#event-tickets" class="tab-link">Tickets</a>
        </li>
        <li class="tab-item">
            <a href="#event-attendees" class="tab-link">Attendees</a>
        </li>
        <li class="tab-item">
            <a href="#event-analytics" class="tab-link">Analytics</a>
        </li>
    </ul>
</div>

<!-- ========== EVENT DETAILS TAB ========== -->
<div id="event-details" class="tab-content">
    <div class="content-card">
        <div class="card-header">
            <h3 class="card-title">Basic Information</h3>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label">Event Title</label>
                    <p class="form-control">{{ $event->title }}</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label">Event Status</label>
                    <p class="form-control">
                        <span class="badge status-{{ $event->status === 'launched' ? 'published' : ($event->status === 'pending' ? 'warning' : 'danger') }}">
                            {{ ucfirst($event->status) }}
                        </span>
                    </p>
                </div>
            </div>
        </div>

        <div class="form-group mb-3">
            <label class="form-label">Event Description</label>
            <div class="form-control" style="min-height: 100px; border: 1px solid var(--border-color); padding: 1rem; border-radius: 8px; background: var(--bg-secondary);">
                {{{ $event->content }}}
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label">Start Date & Time</label>
                    <p class="form-control">{{ $event->event_date }} at {{ $event->event_time }}</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label"><strong>Venue Location</strong></label>
                    <p class="form-control">{{ $event->location }}</p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label"><strong>Event Type</strong></label>
                    <p class="form-control text-capitalize">{{ $event->event_type }}</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label"><strong>Published Date</strong></label>
                    <p class="form-control">@diffForHumans($event->published_at)</p>
                </div>
            </div>
        </div>

        @if($event->discount)
        <div class="row">
            <div class="col-12">
                <div class="mb-3">
                    <label class="form-label"><strong>Promo Code</strong></label>
                    <div class="d-flex align-items-center gap-2">
                        <code class="bg-primary text-white px-2 py-1 rounded">{{ $event->discount->promo_code }}</code>
                        <span class="badge bg-info">
                            {{ $event->discount->discount_type === 'percentage' ? $event->discount->discount_value . '%' : formatMoney($event->discount->discount_value) }} OFF
                        </span>
                        @if($event->discount->promo_valid_until)
                        <small class="text-muted">Valid until: {{ $event->discount->promo_valid_until }}</small>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- ========== TICKETS TAB ========== -->
<div id="event-tickets" class="tab-content" style="display: none;">
    <div class="content-card">
        <div class="card-header">
            <h3 class="card-title">Ticket Types</h3>
            <a href="{{ route('admin.events.edit', ['id' => $event->id]) }}" class="btn-custom btn-primary text-decoration-none">
                <i class="bi bi-pencil-square"></i>
                Manage Tickets
            </a>
        </div>

        @if($event->tickets)
        <div class="ticket-types">
            @foreach($event->tickets as $ticket)
            @php
            $sold = $ticketSales[$ticket->id]['sold'] ?? 0;
            $available = $ticket->quantity - $sold;
            $percentage = $ticket->quantity > 0 ? ($sold / $ticket->quantity) * 100 : 0;
            @endphp
            <div class="ticket-type-card">
                <div class="ticket-type-info">
                    <div class="ticket-type-badge text-uppercase">{{ substr($ticket->name, 0, 2) }}</div>
                    <div class="ticket-type-details">
                        <div class="ticket-type-name">{{ $ticket->name }}</div>
                        <div class="ticket-type-meta">
                            <span><strong>{{ formatMoney($ticket->price) }}</strong></span>
                            <span>{{ $ticket->quantity }} tickets</span>
                            <span>{{ $sold }} sold</span>
                            <span class="badge bg-{{ $available > 0 ? 'success' : 'danger' }}">
                                {{ $available > 0 ? 'Available' : 'Sold Out' }}
                            </span>
                        </div>
                        @if($ticket->description)
                        <div class="ticket-description text-muted mt-1">
                            <small>{{ $ticket->description }}</small>
                        </div>
                        @endif
                        <div class="ticket-progress">
                            <div class="progress">
                                <div class="progress-bar" style="width: <?= $percentage ?>%"></div>
                            </div>
                            <div class="progress-text">
                                <span>{{ $sold }} sold</span>
                                <span>{{ $available }} available</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="ticket-type-actions">
                    <span class="text-muted">
                        Revenue: {{ formatMoney($ticketSales[$ticket->id]['revenue'] ?? 0) }}
                    </span>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-4">
            <i class="bi bi-ticket-perforated display-1 text-muted"></i>
            <h4 class="text-muted mt-3">No Tickets Available</h4>
            <p class="text-muted">This event doesn't have any tickets configured yet.</p>
            <a href="{{ route('admin.events.edit', ['id' => $event->id]) }}#tickets" class="btn-custom btn-primary">
                <i class="bi bi-plus-lg"></i>
                Add Tickets
            </a>
        </div>
        @endif

        @if($event->event_type === 'free')
        <div class="alert alert-info mt-3">
            <i class="bi bi-info-circle"></i> This is a free event. No ticket purchase is required for attendance.
        </div>
        @endif
    </div>
</div>

<!-- ========== ATTENDEES TAB ========== -->
<div id="event-attendees" class="tab-content" style="display: none;">
    <div class="content-card">
        <div class="card-header">
            <h3 class="card-title">Attendee List</h3>
            <button class="btn-custom btn-secondary">
                <i class="bi bi-download"></i>
                Export List
            </button>
        </div>
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> Attendee management interface would go here with search, filters, and attendee details.
        </div>
    </div>
</div>

<!-- ========== ANALYTICS TAB ========== -->
<div id="event-analytics" class="tab-content" style="display: none;">
    <div class="content-card">
        <div class="card-header">
            <h3 class="card-title">Event Analytics</h3>
            <div class="d-flex gap-2">
                <select class="form-control form-control-sm" style="width: auto;" id="analytics-period">
                    <option value="7d">Last 7 Days</option>
                    <option value="30d" selected>Last 30 Days</option>
                    <option value="90d">Last 90 Days</option>
                    <option value="all">All Time</option>
                </select>
            </div>
        </div>

        <!-- Analytics Overview -->
        <div class="analytics-grid">
            <div class="analytics-card">
                <div class="analytics-value">{{ $totalTickets ?? 0 }}</div>
                <div class="analytics-label">Total Capacity</div>
            </div>
            <div class="analytics-card">
                <div class="analytics-value">{{ $soldTickets ?? 0 }}</div>
                <div class="analytics-label">Tickets Sold</div>
            </div>
            <div class="analytics-card">
                <div class="analytics-value">{{ $attendanceRate ?? 0 }}%</div>
                <div class="analytics-label">Attendance Rate</div>
            </div>
            <div class="analytics-card">
                <div class="analytics-value money-value">{{ formatMoney($totalRevenue ?? 0) }}</div>
                <div class="analytics-label">Total Revenue</div>
            </div>
        </div>
        <!-- Here -->
    </div>
</div>
@endsection

@push("scripts")
<script>
    // ========== TAB FUNCTIONALITY ==========
    document.addEventListener('DOMContentLoaded', function() {
        const tabLinks = document.querySelectorAll('.tab-link');
        const tabContents = document.querySelectorAll('.tab-content');

        // Function to show a specific tab
        function showTab(tabId) {
            // Hide all tab contents
            tabContents.forEach(content => {
                content.style.display = 'none';
            });

            // Remove active class from all tabs
            tabLinks.forEach(tab => {
                tab.classList.remove('active');
            });

            // Show the selected tab content
            const targetContent = document.getElementById(tabId);
            if (targetContent) {
                targetContent.style.display = 'block';
            }

            // Add active class to the clicked tab
            const activeTab = document.querySelector(`.tab-link[href="#${tabId}"]`);
            if (activeTab) {
                activeTab.classList.add('active');
            }
        }

        // Add click event listeners to all tabs
        tabLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('href').substring(1);
                showTab(targetId);
            });
        });

        // Show the first tab by default
        if (tabLinks.length > 0) {
            const firstTabId = tabLinks[0].getAttribute('href').substring(1);
            showTab(firstTabId);
        }
    });
</script>
@endpush