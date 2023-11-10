<!DOCTYPE html>
<html  lang="en" data-textdirection="{{ LaravelLocalization::getCurrentLocaleDirection() }}" dir="{{ LaravelLocalization::getCurrentLocaleDirection() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>{{ config('app.name') }} </title>
    <link rel="icon" type="image/x-icon" href="{{ asset('admin') }}/assets/img/favicon.ico"/>
    <link href="{{ asset('admin') }}/assets/{{ LaravelLocalization::getCurrentLocaleDirection() }}/css/loader.css" rel="stylesheet" type="text/css" />
    <script src="{{ asset('admin') }}/assets/js/loader.js"></script>
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
    <link href="{{ asset('admin') }}/bootstrap/{{ LaravelLocalization::getCurrentLocaleDirection() }}/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin') }}/assets/{{ LaravelLocalization::getCurrentLocaleDirection() }}/css/plugins.css" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->

    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM STYLES -->
    <link href="{{ asset('admin') }}/plugins/apex/apexcharts.css" rel="stylesheet" type="text/css">
    <link href="{{ asset('admin') }}/assets/{{ LaravelLocalization::getCurrentLocaleDirection() }}/css/dashboard/dash_1.css" rel="stylesheet" type="text/css" />
    <!-- END PAGE LEVEL PLUGINS/CUSTOM STYLES -->

    <link rel="stylesheet" href="{{asset('admin/assets/css/general.css')}}"/>

				<meta name='language' value='{{app()->getLocale()}}'/>

    @yield('styles')
    @stack('page-styles')

</head>
<body>
    <!-- BEGIN LOADER -->
    <div id="load_screen"> <div class="loader"> <div class="loader-content">
        <div class="spinner-grow align-self-center"></div>
    </div></div></div>
    <!--  END LOADER -->

    <!-- BEGIN HEADER -->
    @include('admin.layouts.header')
    <!-- END HEADER -->

    <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container" id="container">
        <div class="overlay"></div>
        <div class="search-overlay"></div>

         <!-- BEGIN SIDEBAR -->
         @include('admin.layouts.sidebar')
         <!--  END SIDEBAR -->

        <!--  BEGIN CONTENT AREA  -->
        <div id="content" class="main-content">
            <div class="layout-px-spacing">
                @yield('page_header')
                @yield('content')
                <!--  END CONTENT AREA  -->
            </div>
            <div class="footer-wrapper">
                <div class="footer-section f-section-1">
                    <p class="">  <a target="_blank" href="https://designreset.com">Fudex Company</a>, All rights reserved &copy;{{ date("Y") }}</p>
                </div>

            </div>
        </div>
    </div>
    <div id="data-div" data-no-white-spaces='{{__('site.no_whitspaces_allowed')}}'></div>
    <!-- END MAIN CONTAINER -->
    <!-- Scripts -->
    {{-- <script src="{{ asset('public') }}/frontAssets/js/jquery.js"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="{{ asset('admin') }}/bootstrap/js/popper.min.js"></script>
    <script src="{{ asset('admin') }}/bootstrap/js/bootstrap.min.js"></script>
    <script src="{{ asset('admin') }}/plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="{{ asset('admin') }}/assets/js/app.js"></script>


    <script>
        $(document).ready(function() {
            App.init();
        });
        $('#alertMessage').not('.alert-important').delay(5000).fadeOut(350);
    </script>

    <script src="{{ asset('admin') }}/assets/js/custom.js"></script>
    <script src="{{ asset('admin') }}/plugins/apex/apexcharts.min.js"></script>
    <script src="{{ asset('admin') }}/assets/js/dashboard/dash_1.js"></script>
    <!-- include summernote css/js -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>

    <script>
        $(document).ready(()=>{
            let submit_counter = 0;
            $('button[type=submit]').click(()=>{
                submit_counter++;
                if(submit_counter>1){
                    return false;
                }
            });

            $('#editor1').summernote({
               height: 200,
            });
            $('#editor2').summernote({
                height:200
            });
        });
    </script>

    @yield('scripts')

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</body>
</html>
