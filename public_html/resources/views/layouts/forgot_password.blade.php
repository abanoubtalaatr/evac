<!DOCTYPE html>
<html class="no-js">
<head>
    <title>@lang('messages.reset_your_password')</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="description" content="description">
    <meta name="Sard" content="sard">
    <meta name="robots" content="index">
    <!-- ******* FavIcon ******* //-->
    <link rel="icon" href="{{asset('favicon.ico')}}">
    @if(app()->getLocale()=='ar')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.rtl.min.css"
              integrity="sha384-gXt9imSW0VcJVHezoNQsP+TNrjYXoGcrqBZJpry9zJt8PCQjobwmhMGaDHTASo9N"
              crossorigin="anonymous">
        <link rel="stylesheet" href="{{asset('frontAssets/assets_ar/css/arStyle.css')}}">
        <link rel="stylesheet" href="{{asset('css/bootstrap.rtl.min.css')}}">
    @endif
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <link rel="stylesheet" href="{{asset('assets_'.app()->getLocale())}}/css/style.css">
    @livewireStyles()
</head>
<body class="home-page">
<!-- Main Content-->

{{ isset($slot)? $slot : ''}}
@yield('content')
</body>
<!-- End Main Content-->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
{{-- <script src="{{asset('frontAssets/js/functions.js')}}"></script> --}}
@livewireScripts()
</body>
</html>
