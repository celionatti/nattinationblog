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
        <a href="{{ route('admin.events.edit', ['id' => $event->id ?? 0]) }}" class="btn-custom btn-primary text-decoration-none">
            <i class="bi bi-pencil-square"></i>
            Edit Event
        </a>
    </div>
</div>

<!-- ========== ENHANCED EVENT HEADER ========== -->
<div class="event-header">
    <div class="event-info">
        <img src="https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=240&h=180&fit=crop" alt="Tech Conference" class="event-thumbnail">
        <div class="event-details">
            <h2 class="event-title">Tech Conference 2024</h2>
            <div class="event-meta">
                <span><i class="bi bi-calendar-event"></i> June 15-17, 2024</span>
                <span><i class="bi bi-geo-alt"></i> San Francisco Convention Center</span>
                <span><i class="bi bi-people"></i> 1,200+ Attendees</span>
                <span class="badge bg-success">Published</span>
            </div>
            <p class="event-description">
                Join us for the biggest technology conference of the year featuring industry leaders,
                cutting-edge workshops, and networking opportunities with professionals from around the globe.
            </p>
        </div>
    </div>
    <div class="event-stats">
        <div class="stat-item">
            <span class="stat-value">1,248</span>
            <span class="stat-label">Total Tickets</span>
        </div>
        <div class="stat-item">
            <span class="stat-value">842</span>
            <span class="stat-label">Sold</span>
        </div>
        <div class="stat-item">
            <span class="stat-value">406</span>
            <span class="stat-label">Available</span>
        </div>
        <div class="stat-item">
            <span class="stat-value">$42,580</span>
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
        <form>
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Event Title</label>
                        <input type="text" class="form-control" value="Tech Conference 2024">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Event Status</label>
                        <select class="form-control">
                            <option value="published" selected>Published</option>
                            <option value="draft">Draft</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group mb-3">
                <label class="form-label">Event Description</label>
                <textarea class="form-control" rows="4">Join us for the biggest technology conference of the year featuring industry leaders, cutting-edge workshops, and networking opportunities with professionals from around the globe.</textarea>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Start Date & Time</label>
                        <input type="datetime-local" class="form-control" value="2024-06-15T09:00">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">End Date & Time</label>
                        <input type="datetime-local" class="form-control" value="2024-06-17T18:00">
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Venue Name</label>
                        <input type="text" class="form-control" value="San Francisco Convention Center">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Venue Address</label>
                        <input type="text" class="form-control" value="747 Howard St, San Francisco, CA 94103">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Featured Image</label>
                <div class="d-flex align-items-center gap-3">
                    <img src="https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=120&h=90&fit=crop" alt="Event" class="event-thumbnail">
                    <div>
                        <button type="button" class="btn-custom btn-secondary">
                            <i class="bi bi-upload"></i>
                            Change Image
                        </button>
                        <div class="form-text">Recommended size: 1200x800 pixels</div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- ========== TICKETS TAB ========== -->
<div id="event-tickets" class="tab-content" style="display: none;">
    <div class="content-card">
        <div class="card-header">
            <h3 class="card-title">Ticket Types</h3>
            <button class="btn-custom btn-primary">
                <i class="bi bi-plus-lg"></i>
                Add Ticket Type
            </button>
        </div>

        <div class="ticket-types">
            <div class="ticket-type-card">
                <div class="ticket-type-info">
                    <div class="ticket-type-badge">VIP</div>
                    <div class="ticket-type-details">
                        <div class="ticket-type-name">VIP All-Access Pass</div>
                        <div class="ticket-type-meta">
                            <span><strong>$299</strong></span>
                            <span>250 tickets</span>
                            <span>84 sold</span>
                            <span class="badge bg-success">Available</span>
                        </div>
                        <div class="ticket-progress">
                            <div class="progress">
                                <div class="progress-bar" style="width: 33.6%"></div>
                            </div>
                            <div class="progress-text">
                                <span>84 sold</span>
                                <span>166 available</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="ticket-type-actions">
                    <button class="btn-custom btn-secondary btn-sm">
                        <i class="bi bi-pencil"></i>
                        Edit
                    </button>
                    <button class="btn-custom btn-danger btn-sm">
                        <i class="bi bi-trash"></i>
                        Delete
                    </button>
                </div>
            </div>

            <div class="ticket-type-card">
                <div class="ticket-type-info">
                    <div class="ticket-type-badge">GA</div>
                    <div class="ticket-type-details">
                        <div class="ticket-type-name">General Admission</div>
                        <div class="ticket-type-meta">
                            <span><strong>$149</strong></span>
                            <span>800 tickets</span>
                            <span>642 sold</span>
                            <span class="badge bg-success">Available</span>
                        </div>
                        <div class="ticket-progress">
                            <div class="progress">
                                <div class="progress-bar" style="width: 80.25%"></div>
                            </div>
                            <div class="progress-text">
                                <span>642 sold</span>
                                <span>158 available</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="ticket-type-actions">
                    <button class="btn-custom btn-secondary btn-sm">
                        <i class="bi bi-pencil"></i>
                        Edit
                    </button>
                    <button class="btn-custom btn-danger btn-sm">
                        <i class="bi bi-trash"></i>
                        Delete
                    </button>
                </div>
            </div>

            <div class="ticket-type-card">
                <div class="ticket-type-info">
                    <div class="ticket-type-badge">ST</div>
                    <div class="ticket-type-details">
                        <div class="ticket-type-name">Student Pass</div>
                        <div class="ticket-type-meta">
                            <span><strong>$79</strong></span>
                            <span>200 tickets</span>
                            <span>116 sold</span>
                            <span class="badge bg-success">Available</span>
                        </div>
                        <div class="ticket-progress">
                            <div class="progress">
                                <div class="progress-bar" style="width: 58%"></div>
                            </div>
                            <div class="progress-text">
                                <span>116 sold</span>
                                <span>84 available</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="ticket-type-actions">
                    <button class="btn-custom btn-secondary btn-sm">
                        <i class="bi bi-pencil"></i>
                        Edit
                    </button>
                    <button class="btn-custom btn-danger btn-sm">
                        <i class="bi bi-trash"></i>
                        Delete
                    </button>
                </div>
            </div>
        </div>
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
        </div>
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> Analytics dashboard with charts and metrics for ticket sales, revenue, and attendance would go here.
        </div>
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