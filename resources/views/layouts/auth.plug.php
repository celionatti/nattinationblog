<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', '') - {{ $app_name }}</title>
    <meta name="description" content="@yield('meta_description', 'Welcome to ' . $app_name)">
    <meta name="author" content="{{ $app_name }}">
    <meta name="keywords" content="@yield('meta_keywords', 'blog, articles, posts, ' . strtolower($app_name))">
    <link rel="shortcut icon" href="{{ asset('logo.png') }}" type="image/png">
    <link rel="stylesheet" href="{{ asset('assets/packages/bootstrap/css/bootstrap.min.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('assets/packages/bootstrap/css/bootstrap-icons.min.css') }}">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/auth.css') }}">
    @stack('styles')
</head>

<body class="dark-mode">
    <!-- ========== 3D BACKGROUND ========== -->
    <div class="bg-3d-container">
        <div class="shape-1 bg-shape"></div>
        <div class="shape-2 bg-shape"></div>
        <div class="shape-3 bg-shape"></div>
    </div>

    <!-- ========== THEME TOGGLE ========== -->
    <div class="theme-toggle-container">
        <button class="theme-toggle" id="themeToggle" aria-label="Toggle theme">
            <i class="bi bi-sun-fill" id="themeIcon"></i>
        </button>
    </div>

    @yield('content')

    <script src="{{ asset('assets/packages/bootstrap/js/bootstrap.min.js') }}"></script>
    <script>
        // Initialize when page loads
        document.addEventListener("DOMContentLoaded", function () {
            // ========== THEME TOGGLE ==========
            const themeToggle = document.getElementById("themeToggle");
            const themeIcon = document.getElementById("themeIcon");
            const body = document.body;

            // Check for saved theme preference
            const savedTheme = localStorage.getItem("site_theme") || "dark";

            function setTheme(theme) {
                if (theme === "light") {
                    body.classList.remove("dark-mode");
                    themeIcon.classList.remove("bi-sun-fill");
                    themeIcon.classList.add("bi-moon-stars-fill");
                } else {
                    body.classList.add("dark-mode");
                    themeIcon.classList.remove("bi-moon-stars-fill");
                    themeIcon.classList.add("bi-sun-fill");
                }
                localStorage.setItem("site_theme", theme);
            }

            // Initialize theme
            setTheme(savedTheme);

            // Toggle theme on button click
            themeToggle.addEventListener("click", () => {
                const isDark = body.classList.contains("dark-mode");
                setTheme(isDark ? "light" : "dark");
            });

            // ========== 3D CARD TILT EFFECT (Desktop only) ==========
            const isTouchDevice = "ontouchstart" in window || navigator.maxTouchPoints > 0;
            const isDesktop = !isTouchDevice && window.innerWidth > 768;

            if (isDesktop) {
                // Check for registration card
                const regCard = document.querySelector(".registration-card");
                if (regCard) {
                    regCard.addEventListener("mousemove", (e) => {
                        const cardRect = regCard.getBoundingClientRect();
                        const cardCenterX = cardRect.left + cardRect.width / 2;
                        const cardCenterY = cardRect.top + cardRect.height / 2;

                        const mouseX = e.clientX - cardCenterX;
                        const mouseY = e.clientY - cardCenterY;

                        const rotateX = (mouseY / cardRect.height) * 5;
                        const rotateY = (mouseX / cardRect.width) * -5;

                        regCard.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateY(-5px)`;
                    });

                    regCard.addEventListener("mouseleave", () => {
                        regCard.style.transform = "perspective(1000px) rotateX(0) rotateY(0) translateY(0)";
                    });
                }

                // Check for login card
                const loginCard = document.querySelector('.login-card');
                if (loginCard) {
                    loginCard.addEventListener('mousemove', (e) => {
                        const cardRect = loginCard.getBoundingClientRect();
                        const cardCenterX = cardRect.left + cardRect.width / 2;
                        const cardCenterY = cardRect.top + cardRect.height / 2;

                        const mouseX = e.clientX - cardCenterX;
                        const mouseY = e.clientY - cardCenterY;

                        const rotateX = (mouseY / cardRect.height) * 5;
                        const rotateY = (mouseX / cardRect.width) * -5;

                        loginCard.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateY(-5px)`;
                    });

                    loginCard.addEventListener('mouseleave', () => {
                        loginCard.style.transform = 'perspective(1000px) rotateX(0) rotateY(0) translateY(0)';
                    });
                }
            }
        });
    </script>
    @stack('scripts')
</body>

</html>