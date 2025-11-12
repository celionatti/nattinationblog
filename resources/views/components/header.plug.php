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
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse"
            data-bs-target="#mainNav">
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