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
        <div class="shape-4 bg-shape"></div>
    </div>

    <!-- ========== FLOATING PARTICLES ========== -->
    <div class="particles" id="particles"></div>

    <!-- ========== THEME TOGGLE ========== -->
    <div class="theme-toggle-container">
        <button class="theme-toggle" id="themeToggle">
            <i class="bi bi-sun-fill" id="themeIcon"></i>
        </button>
    </div>

    @yield('content')

    <script src="{{ asset('assets/packages/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/auth.js') }}"></script>
    @stack('scripts')
</body>

</html>
