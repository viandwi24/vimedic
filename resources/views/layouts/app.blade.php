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
    <title>{{ env('APP_NAME', 'Laravel') }}</title>
    <!-- stylesheets -->
    @stack('css-lib')
    @stack('css')
    <!-- other -->
    @stack('other')
</head>
<body>
    <!-- app -->
    <div id="app">
        <div class="page">@yield('content')</div>
        <div class="modal">@stack('modal')</div>
    </div>
    <!-- javascript -->
    @stack('js-lib')
    @stack('js')
</body>
</html>