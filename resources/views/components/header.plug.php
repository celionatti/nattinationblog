<!-- ========== TOP HEADER ========== -->
<header class="top-header">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <button class="btn btn-subscribe rounded-pill">
                <i class="bi bi-envelope-fill me-1"></i> Subscribe
            </button>
            <a href="/" class="brand">Natti<span>Nation</span></a>
            <div class="auth-links">
                <a href="{{ url('login') }}"><i class="bi bi-person-circle me-1"></i> Login</a>
                <a href="{{ url('signup') }}"><i class="bi bi-person-plus-fill me-1"></i> Sign Up</a>
            </div>
        </div>
    </div>
</header>

<!-- ========== MAIN NAVBAR ========== -->
<nav class="navbar navbar-expand-lg main-navbar sticky-top">
    <div class="container">
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <i class="bi bi-list" style="font-size: 1.5rem; color: var(--text-primary);"></i>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link active" href="{{ url('/') }}">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('articles') }}">Articles</a></li>
                <li class="nav-item"><a class="nav-link" href="#">World</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Sports</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Politics</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Entertainment</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Nigeria</a></li>
                <!-- Resources Menu -->
                <li class="nav-item"><a class="nav-link" href="#resources">
                        <i class="bi bi-folder-fill me-1"></i> Resources
                    </a></li>

                <!-- Services Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="servicesDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-briefcase-fill me-1"></i> Services
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="servicesDropdown">
                        <li>
                            <a class="dropdown-item" href="#escrow-service">
                                <i class="bi bi-shield-check"></i>
                                <span>Escrow Service</span>
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item" href="#content-writing">
                                <i class="bi bi-pencil-square"></i>
                                <span>Content Writing</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#consultation">
                                <i class="bi bi-chat-dots"></i>
                                <span>Consultation</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#web-design">
                                <i class="bi bi-code-slash"></i>
                                <span>Web Design & Development</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#seo-services">
                                <i class="bi bi-graph-up-arrow"></i>
                                <span>SEO Services</span>
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item" href="#all-services">
                                <i class="bi bi-grid-3x3-gap"></i>
                                <span>View All Services</span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
        <div class="d-flex align-items-center gap-3">
            <div class="search-container">
                <div class="search-input-container" id="searchInputContainer">
                    <input type="text" class="search-input" placeholder="Search articles...">
                    <button class="search-close" id="searchClose">
                        <i class="bi bi-x"></i>
                    </button>
                </div>
                <button class="search-toggle" id="searchToggle">
                    <i class="bi bi-search"></i>
                </button>
            </div>
            <button class="theme-toggle" id="themeToggle" aria-label="Toggle dark mode">
                <i class="bi bi-sun-fill" id="themeIcon"></i>
            </button>
        </div>
    </div>
</nav>