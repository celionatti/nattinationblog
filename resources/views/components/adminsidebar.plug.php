<!-- ========== SIDEBAR ========== -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <a href="/">
            <i class="bi bi-stack"></i>
            Natti<span>Nation</span>
        </a>
    </div>

    <ul class="sidebar-nav">
        <li class="sidebar-nav-item" id="dashboard">
            <a href="{{ url('admin') }}" class="sidebar-nav-link {{ activeClass('/admin', exact: true) }}">
                <i class="bi bi-speedometer2"></i>
                Dashboard
            </a>
        </li>
        <li class="sidebar-nav-item" id="users">
            <a href="{{ url('admin/users') }}" class="sidebar-nav-link {{ activeClass('/admin/users', exact: true) }}">
                <i class="bi bi-people"></i>
                Users
            </a>
        </li>
        <li class="sidebar-nav-item" id="media">
            <a href="{{ url('admin/media') }}" class="sidebar-nav-link {{ activeClass('/admin/media', exact: true) }}">
                <i class="bi bi-card-image"></i>
                Media
            </a>
        </li>
    </ul>

    <div class="sidebar-section-title">Articles</div>
    <ul class="sidebar-nav">
        <li class="sidebar-nav-item" id="articles">
            <a href="{{ url('admin/articles') }}" class="sidebar-nav-link {{ activeClass('/admin/articles') }}">
                <i class="bi bi-newspaper"></i>
                Articles
            </a>
        </li>
        <li class="sidebar-nav-item" id="categories">
            <a href="{{ url('admin/categories') }}" class="sidebar-nav-link {{ activeClass('/admin/categories') }}">
                <i class="bi bi-folder"></i>
                Categories
            </a>
        </li>
        <li class="sidebar-nav-item" id="comments">
            <a href="{{ url('admin/comments') }}" class="sidebar-nav-link {{ activeClass('/admin/comments', exact: true) }}">
                <i class="bi bi-chat-dots"></i>
                Comments
            </a>
        </li>
    </ul>

    <div class="sidebar-section-title">Events</div>
    <ul class="sidebar-nav">
        <li class="sidebar-nav-item" id="events">
            <a href="{{ url('admin/events') }}" class="sidebar-nav-link {{ activeClass('/admin/events') }}">
                <i class="bi bi-calendar-event"></i>
                Events
            </a>
        </li>
        <li class="sidebar-nav-item" id="event-types">
            <a href="{{ url('admin/event-types') }}" class="sidebar-nav-link {{ activeClass('/admin/event-types', exact: true) }}">
                <i class="bi bi-calendar-range"></i>
                Event Types
            </a>
        </li>
        <li class="sidebar-nav-item" id="tickets">
            <a href="{{ url('admin/events/tickets') }}" class="sidebar-nav-link {{ activeClass('/admin/tickets') }}">
                <i class="bi bi-ticket-detailed"></i>
                Tickets
            </a>
        </li>
    </ul>

    <div class="sidebar-section-title">Resources</div>
    <ul class="sidebar-nav">
        <li class="sidebar-nav-item" id="resources">
            <a href="{{ url('admin/resources') }}" class="sidebar-nav-link {{ activeClass('/admin/resources') }}">
                <i class="bi bi-files"></i>
                Resources
            </a>
        </li>
    </ul>

    <div class="sidebar-section-title">Services</div>
    <ul class="sidebar-nav">
        <li class="sidebar-nav-item" id="escrow">
            <a href="{{ url('admin/services/escrow') }}" class="sidebar-nav-link {{ activeClass('/admin/services/escrow') }}">
                <i class="bi bi-bank"></i>
                Escrow
            </a>
        </li>
    </ul>

    <div class="sidebar-section-title">Analytics</div>
    <ul class="sidebar-nav">
        <li class="sidebar-nav-item">
            <a href="#" class="sidebar-nav-link">
                <i class="bi bi-graph-up"></i>
                Analytics
            </a>
        </li>
        <li class="sidebar-nav-item">
            <a href="#" class="sidebar-nav-link">
                <i class="bi bi-bar-chart"></i>
                Reports
            </a>
        </li>
    </ul>

    <div class="sidebar-section-title">Settings</div>
    <ul class="sidebar-nav">
        <li class="sidebar-nav-item" id="settings">
            <a href="{{ url('admin/settings') }}" class="sidebar-nav-link {{ activeClass('/admin/settings', exact: true) }}">
                <i class="bi bi-gear"></i>
                Settings
            </a>
        </li>
        <!-- <li class="sidebar-nav-item">
            <a href="#" class="sidebar-nav-link">
                <i class="bi bi-palette"></i>
                Appearance
            </a>
        </li> -->
        <li class="sidebar-nav-item">
            <a href="{{ url('logout')}}" class="sidebar-nav-link">
                <i class="bi bi-box-arrow-right"></i>
                Logout
            </a>
        </li>
    </ul>
</aside>

<!-- ========== SIDEBAR OVERLAY ========== -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>