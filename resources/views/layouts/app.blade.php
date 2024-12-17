<!DOCTYPE html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <title>NanoNet Management System</title>
    <meta name="description" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    
    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/educate-custon-icon.css') }}">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
    
    <!-- JavaScript -->
    <script src="{{ asset('js/vendor/modernizr-2.8.3.min.js') }}"></script>
    <script src="{{ asset('js/vendor/jquery-1.12.4.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/wow.min.js') }}"></script>
    
    @include('head')
    <style>
        body {
            min-height: 100vh;
            position: relative;
            margin: 0;
            padding-bottom: 60px;
        }
        .all-content-wrapper {
            min-height: calc(100vh - 60px); 
        }
        .footer-wrapper {
            position: absolute;
            bottom: 0;
            width: 100%;
            height: 60px; 
        }
    </style>
</head>

<body>
    @include('sidebar')

    <div class="all-content-wrapper">
        @include('header')
        @include('Mobile_menu')
        
        @yield('content')
    </div>

    @include('components.notification')
    
    @include('Admin_panel')
    @include('Reports')
    
    <div class="footer-wrapper">
        @include('Footer')
    </div>

    <!-- Additional Scripts -->
    <script>
        new WOW().init();
    </script>
    <script src="{{ asset('js/main.js') }}"></script>
</body>
</html>