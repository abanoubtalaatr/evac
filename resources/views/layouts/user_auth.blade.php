<!DOCTYPE html>
<html class="no-js">
<head>
    @include('partial.style')
    @livewireStyles()
</head>
<body class="home-page">
<!-- Main Content-->
<main class="main-content">
    {{ isset($slot)? $slot : ''}}
    @yield('content')
</main>

@include('partial.scripts')

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD5LCZLdS1cq7-buPkwyLDjcARlFjiljYk&callback=initMap&libraries=places" async defer></script>
@livewireScripts()
</body>
</html>
