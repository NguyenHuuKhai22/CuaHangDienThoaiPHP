<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'KAIRA - Cửa hàng Điện thoại')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Marcellus&family=Mulish:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <style>
        body {
            font-family: 'Mulish', sans-serif;
            color: #333;
            padding-top: 80px; /* Add padding to prevent content from being hidden under fixed header */
        }
        header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030;
            background: #fff;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Marcellus', serif;
        }
        .navbar-nav .nav-link {
            font-size: 14px;
            font-weight: 500;
            letter-spacing: 0.5px;
            padding: 0 15px;
        }
        .navbar-brand {
            font-family: 'Marcellus', serif;
            font-size: 24px;
        }
        .hero-title {
            font-size: 60px;
            margin-bottom: 20px;
        }
        .hero-text {
            color: #666;
            font-size: 16px;
            line-height: 1.8;
            max-width: 600px;
            margin: 0 auto 40px;
        }
        .collection-title {
            font-size: 20px;
            margin: 20px 0 10px;
        }
        .collection-text {
            color: #666;
            font-size: 14px;
            margin-bottom: 15px;
        }
        .discover-link {
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
            text-decoration: none;
            color: #333;
            border-bottom: 1px solid #333;
            padding-bottom: 2px;
        }
        .discover-link:hover {
            color: #666;
            border-color: #666;
        }
        main {
            position: relative;
            z-index: 1;
        }
    </style>

    @stack('styles')
</head>
<body>
    @include('layouts.header')

    <main>
        @yield('content')
    </main>

    @include('layouts.footer')

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>

    @stack('scripts')
</body>
</html> 