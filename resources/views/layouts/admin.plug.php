<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', '') - {{ $app_name }}</title>
    <link rel="shortcut icon" href="{{ asset('logo.png') }}" type="image/png">
    <link rel="stylesheet" href="{{ asset('assets/packages/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/packages/bootstrap/css/bootstrap-icons.min.css') }}">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
    @stack('styles')
</head>

<body>
    @yield('content')

    <script src="{{ asset('assets/js/main.js') }}"></script>
    @stack('scripts')
</body>

</html>