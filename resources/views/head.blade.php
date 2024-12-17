<?php
 session_start();
 session_regenerate_id();
 if(!(isset($_SESSION["login"]))){

  header("location:Login.php?red_us_se_id=1");
 }
?>

<head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <title>NanoNet Management System</title>
    <meta name="description" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!-- favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('img/favicon.ico') }}" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,700,900" rel="stylesheet"/>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}" />
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}" />
    <!-- owl.carousel CSS -->
    <link rel="stylesheet" href="{{ asset('css/owl.transitions.css') }}" />
    <!-- normalize CSS -->
    <link rel="stylesheet" href="{{ asset('css/normalize.css') }}" />
    <!-- meanmenu icon CSS -->
    <link rel="stylesheet" href="{{ asset('css/meanmenu.min.css') }}" />
    <!-- main CSS -->
    <link rel="stylesheet" href="{{ asset('css/main.css') }}" />
    <!-- educate icon CSS -->
    <link rel="stylesheet" href="{{ asset('css/educate-custon-icon.css') }}" />
    <!-- mCustomScrollbar CSS -->
    <link rel="stylesheet" href="{{ asset('css/scrollbar/jquery.mCustomScrollbar.min.css') }}"/>
    <!-- metisMenu CSS -->
    <link rel="stylesheet" href="{{ asset('css/metisMenu/metisMenu.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/metisMenu/metisMenu-vertical.css') }}" />
    <!-- style CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
    <!-- responsive CSS -->
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}" />
    <!-- modernizr JS -->
    <script src="{{ asset('js/vendor/modernizr-2.8.3.min.js') }}"></script>
</head>