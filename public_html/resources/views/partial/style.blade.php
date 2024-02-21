
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@lang('site.site_title') </title>
    <link rel="stylesheet" href="{{asset('css/bootstrap.rtl.min.css')}}">
    <link rel="stylesheet" href="{{asset('website/assets/css/slick-theme.css')}}">
    <link rel="stylesheet" href="{{asset('website/assets/css/slick.css')}}">
    <link rel="stylesheet" href="{{asset('website/assets/css/jquery-steps.css')}}">

    <link rel="stylesheet" href="{{asset('website/assets/css/all.min.css')}}">
    <!-- start css file -->
    <link rel="stylesheet" href="{{asset("website/".app()->getLocale().  "/assets/css/style.css")}}">
    <!-- start responsive -->
    <link rel="stylesheet" href="{{asset("website/".app()->getLocale().  "/assets/css/responsive.css")}}">

    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
