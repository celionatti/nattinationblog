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
            <a href="{{ url('admin/articles') }}" class="sidebar-nav-link">
                <i class="bi bi-file-earmark-text"></i>
                Posts
            </a>
        </li>
        <li class="sidebar-nav-item">
            <a href="#" class="sidebar-nav-link">
                <i class="bi bi-folder"></i>
                Categories
            </a>
        </li>
        <li class="sidebar-nav-item">
            <a href="#" class="sidebar-nav-link">
                <i class="bi bi-chat-dots"></i>
                Comments
            </a>
        </li>
        <li class="sidebar-nav-item">
            <a href="#" class="sidebar-nav-link">
                <i class="bi bi-people"></i>
                Users
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
        <li class="sidebar-nav-item">
            <a href="#" class="sidebar-nav-link">
                <i class="bi bi-palette"></i>
                Appearance
            </a>
        </li>
        <li class="sidebar-nav-item">
            <a href="#" class="sidebar-nav-link">
                <i class="bi bi-box-arrow-right"></i>
                Logout
            </a>
        </li>
    </ul>
</aside>

<!-- ========== SIDEBAR OVERLAY ========== -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>