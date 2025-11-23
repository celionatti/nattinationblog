<!-- ========== SIDEBAR ========== -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <a href="/">
            <i class="bi bi-stack"></i>
            Natti<span>Nation</span>
        </a>
    </div>

    <ul class="sidebar-nav">
        <li class="sidebar-nav-item">
            <a href="{{ url('admin') }}" class="sidebar-nav-link {{ activeClass('/admin', exact: true) }}">
                <i class="bi bi-speedometer2"></i>
                Dashboard
            </a>
        </li>
        <li class="sidebar-nav-item">
            <a href="{{ url('admin/users') }}" class="sidebar-nav-link {{ activeClass('/admin/users', exact: true) }}">
                <i class="bi bi-people"></i>
                Users
            </a>
        </li>
        <li class="sidebar-nav-item">
            <a href="{{ url('admin/media') }}" class="sidebar-nav-link {{ activeClass('/admin/media', exact: true) }}">
                <i class="bi bi-card-image"></i>
                Media
            </a>
        </li>
    </ul>

    <div class="sidebar-section-title">Articles</div>
    <ul class="sidebar-nav">
        <li class="sidebar-nav-item">
            <a href="{{ url('admin/articles') }}" class="sidebar-nav-link {{ activeClass('/admin/articles') }}">
                <i class="bi bi-newspaper"></i>
                Articles
            </a>
        </li>
        <li class="sidebar-nav-item">
            <a href="{{ url('admin/categories') }}" class="sidebar-nav-link {{ activeClass('/admin/categories') }}">
                <i class="bi bi-folder"></i>
                Categories
            </a>
        </li>
        <li class="sidebar-nav-item">
            <a href="{{ url('admin/comments') }}" class="sidebar-nav-link {{ activeClass('/admin/comments', exact: true) }}">
                <i class="bi bi-chat-dots"></i>
                Comments
            </a>
        </li>
    </ul>

    <div class="sidebar-section-title">Events</div>
    <ul class="sidebar-nav">
        <li class="sidebar-nav-item">
            <a href="{{ url('admin/events') }}" class="sidebar-nav-link {{ activeClass('/admin/events', exact: true) }}">
                <i class="bi bi-calendar-event"></i>
                Events
            </a>
        </li>
        <li class="sidebar-nav-item">
            <a href="{{ url('admin/events/create') }}" class="sidebar-nav-link {{ activeClass('/admin/events/create', exact: true) }}">
                <i class="bi bi-calendar2-plus"></i>
                Create Event
            </a>
        </li>
        <li class="sidebar-nav-item">
            <a href="{{ url('admin/events/tickets') }}" class="sidebar-nav-link {{ activeClass('/admin/tickets') }}">
                <i class="bi bi-ticket-detailed"></i>
                Tickets
            </a>
        </li>
    </ul>

    <div class="sidebar-section-title">Resources</div>
    <ul class="sidebar-nav">
        <li class="sidebar-nav-item">
            <a href="{{ url('admin/resources') }}" class="sidebar-nav-link {{ activeClass('/admin/resources') }}">
                <i class="bi bi-files"></i>
                Resources
            </a>
        </li>
    </ul>

    <div class="sidebar-section-title">Services</div>
    <ul class="sidebar-nav">
        <li class="sidebar-nav-item">
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
        <li class="sidebar-nav-item">
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