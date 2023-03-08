<!doctype html>
<html lang="en-US">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ config('app.name') }}</title>
    {{-- Toastr --}}
    {{-- End Toastr --}}
    <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />

    <link media="all" rel="stylesheet" type="text/css" href="{{ asset('admin/css/main.css') }}" />
    <link media="all" rel="stylesheet" type="text/css" href="{{ asset('admin/css/style.css') }}" />

    <link rel="stylesheet" href="http://cdn.bootcss.com/toastr.js/latest/css/toastr.min.css">

</head>

<body>
    <div id="wrapper">

        {{-- <script src="{{ asset('assets/js/jquery.min.js') }}"></script> --}}
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <script type="text/javascript" src="{{ asset('admin/js/bootstrap.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('admin/js/main.js') }}"></script>
        <script type="text/javascript" src="{{ asset('admin/js/slick.js') }}"></script>
        <script type="text/javascript" src="{{ asset('admin/js/owl.js') }}"></script>
        <script src="{{ asset('assets/plugins/select2/select2.min.js') }}"></script>
        <script src="http://cdn.bootcss.com/toastr.js/latest/js/toastr.min.js"></script>
        <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/easy-pie-chart/2.1.6/jquery.easypiechart.min.js"></script>
        @if (Route::is('dashboard'))
            <script src="{{ asset('admin/js/dashboard.js') }}"></script>
        @endif
        @if (Route::is('retailers.create'))
            <script src="{{ asset('admin/js/retailer-create.js') }}"></script>
        @endif


        @yield('content')

        <footer id="footer">
            <div class="container">
            </div>
        </footer>
    </div>

    @include('layouts.script')
</body>

</html>
