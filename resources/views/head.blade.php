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
 
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('img/favicon.ico') }}" />
   
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,700,900" rel="stylesheet"/>
 
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}" />
 
    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}" />
  
    <link rel="stylesheet" href="{{ asset('css/owl.transitions.css') }}" />
   
    <link rel="stylesheet" href="{{ asset('css/normalize.css') }}" />

    <link rel="stylesheet" href="{{ asset('css/meanmenu.min.css') }}" />

    <link rel="stylesheet" href="{{ asset('css/main.css') }}" />

    <link rel="stylesheet" href="{{ asset('css/educate-custon-icon.css') }}" />
   
    <link rel="stylesheet" href="{{ asset('css/scrollbar/jquery.mCustomScrollbar.min.css') }}"/>
  
    <link rel="stylesheet" href="{{ asset('css/metisMenu/metisMenu.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/metisMenu/metisMenu-vertical.css') }}" />
  
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />

    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}" />
 
    <script src="{{ asset('js/vendor/modernizr-2.8.3.min.js') }}"></script>
</head>