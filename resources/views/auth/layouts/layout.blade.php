<!doctype html>
<html lang="en-US">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ config('app.name') }}</title>
    <link media="all" rel="stylesheet" type="text/css" href="{{ asset('admin/css/main.css') }}" />
    {{-- Toastr --}}
    <link rel="stylesheet" href="https://cdn.bootcss.com/toastr.js/latest/css/toastr.min.css">
    {{-- End Toastr --}}
</head>

<body>
    @yield('content')

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script type="text/javascript" src="{{ asset('admin/js/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('admin/js/main.js') }}"></script>
    <script type="text/javascript" src="{{ asset('admin/js/slick.js') }}"></script>
    <script type="text/javascript" src="{{ asset('admin/js/owl.js') }}"></script>
    <script src="https://cdn.bootcss.com/toastr.js/latest/js/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>


    @include('layouts.script')
    @if (Route::is('login'))
        <script src="{{ asset('admin/js/login.js') }}"></script>
    @endif
    @if (Route::is('password.request'))
        <script src="{{ asset('admin/js/forget-password.js') }}"></script>
    @endif
    @if (Route::is('password.reset'))
        <script src="{{ asset('admin/js/reset-password.js') }}"></script>
    @endif
</body>

</html>
