<!DOCTYPE html>
<html class="no-js">
@include('partial.style')
<body >
<div>
    @php $settings = \App\Models\Setting::query()->first(); @endphp

    @include('partial.header')

    @yield('content')
    @isset($slot) {{$slot}} @endisset

    @include('partial.support')
    @include('partial.footer')
</div>

@include('partial.scripts')


@livewireScripts()
</body>

</html>
