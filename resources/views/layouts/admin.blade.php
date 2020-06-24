<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- meta -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @stack('meta')
    <!-- title -->
    <title>{{ (isset($title) ? $title . ' - ' : '') }}{{ env('APP_NAME', 'Laravel') }}</title>
    <!-- stylesheets -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="{{ asset('assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/jqvmap/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/toastr/toastr.min.css') }}">
    @stack('css-lib')
    <link rel="stylesheet" href="{{ asset('assets/dist/css/adminlte.min.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <style>
        .card-header .card-right-button { float: right; }
        .card-header .card-right-button button {
            padding: .25rem .5rem;
            font-size: .800rem;
            line-height: 1.5;
            border-radius: .2rem;
        }
        .card-title {
            float: left;
            font-size: 1.5rem;
            font-weight: 400;
            margin: 0;
        }
        .table.dataTable { width: 100% !important; }
    </style>
    @stack('css')
    <!-- other -->
    @stack('other')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <!-- app -->
    <div id="app">
        <div class="wrapper">
            <x-admin-navbar />
            <x-admin-sidebar />
            <div class="content-wrapper">
                @hasSection ('content-header') @yield('content-header') @else <x-admin-content-header :title="@$title" /> @endif
            
                <!-- Main content -->
                <section class="content">
                    @yield('content')
                </section>
            </div>
            <x-admin-footer />
        </div>
    </div>
    <!-- javascript -->
    <script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <script>$.widget.bridge('uibutton', $.ui.button)</script>
    <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/chart.js/Chart.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/sparklines/sparkline.js') }}"></script>
    <script src="{{ asset('assets/plugins/jqvmap/jquery.vmap.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/jqvmap/maps/jquery.vmap.usa.js') }}"></script>
    <script src="{{ asset('assets/plugins/jquery-knob/jquery.knob.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/summernote/summernote-bs4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/vue/vue.js') }}"></script>
    <script src="{{ asset('assets/dist/js/adminlte.js') }}"></script>
    @stack('js-lib')
    @stack('js')
    @include('components.admin-javascript')
</body>
</html>