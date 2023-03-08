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
    <link rel="stylesheet" href="https://cdn.bootcss.com/toastr.js/latest/css/toastr.min.css">
    <link media="all" rel="stylesheet" type="text/css" href="{{ asset('admin/css/datatables.min.css') }}" />

</head>

<body>
    <div id="wrapper">
        <header id="header">
            <div class="container">
                <div class="dropdown profile-dropdown">
                    <a class="profile" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="profile-img">
                            {{-- <img src="{{ asset('admin/images/logo-white.png') }}" alt=""> --}}
                        </div>
                        <div class="profile-title">
                            <span>{{ Auth::user()->name }}</span>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                    </a>
                    <ul class="dropdown-menu profile-sub-menu">
                        @if (Auth::user()->hasRole('Retailer'))
                            <li><a href="{{ route('retailers.edit.profile', Auth::user()->userable->id) }}"><i
                                        class="icon-user"></i>Edit
                                    Profile</a></li>
                        @elseif (Auth::user()->hasRole('Super Admin'))
                            <li><a href=""><i class="icon-user"></i>Edit
                                    Profile</a></li>
                        @endif

                        <li><a href="#"><i class="icon-moon"></i>Theme (Light)</a></li>
                        <li><a href="{{ route('logout') }}"><i class="icon-logout"></i>Logout</a></li>
                    </ul>
                </div>
                <div class="logo">
                    <a href="{{ route('dashboard') }}">
                        <img src="{{ asset('admin/images/logo-white.png') }}">
                    </a>
                </div>
                <div class="search-box">
                    <div class="form-group m-0">
                        <input type="text" placeholder="search">
                        <a class="icon-box"><i class="icon-search"></i></a>
                    </div>
                </div>
                <div class="header-btn">
                    <a href="" class="icon-btn search-opener"><i class="icon-search"></i></a>
                    <a href="#" class="icon-btn nav-opener"><i class="fal fa-bars"></i> </a>
                    {{-- <a href="" class="icon-btn"><i class="icon-filter"></i></a> --}}
                </div>
                <nav id="nav">
                    <a href="#" class="nav-opener"><i class="fal fa-close"></i> </a>
                    <ul class="nav nav-list">
                        @hasanyrole($adminRoles)
                            <li class="active"><a href="{{ route('dashboard') }}"><i class="icon-home-grid"></i>Home</a>
                            </li>
                            <li><a href="{{ route('reports.monthly.status') }}"><i class="icon-reports"></i>Reports</a>
                            </li>
                            <li><a href="{{ route('retailers.index') }}"><i class="icon-shop"></i>Retailer</a></li>
                            <li><a href="{{ route('lps.index') }}"><i class="icon-bag"></i>LP</a></li>
                            <li><a href="{{ route('admins.index') }}"><i class="icon-user"></i>User</a>
                            </li>
                            <li><a href="{{ route('roles.index') }}"><i class="icon-roles"></i>Roles</a></li>
                            <li><a href="{{ route('report.submisson.date') }}"><i class="icon-roles"></i>Date</a></li>
                            <li><a href="{{ route('sample.files') }}"><i class="icon-roles"></i>Samples</a></li>
                            <li><a href="{{ route('monthly.report.by.province') }}"><i
                                        class="fa fa-anchor"></i>Comparison</a></li>
                        @endhasanyrole
                        @role('Retailer')
                            <li><a href="{{ route('reports.monthly.status') }}"><i class="icon-reports"></i>Reports</a>
                            </li>
                            <li><a href="{{ route('sample.files') }}"><i class="icon-roles"></i>Samples</a></li>
                        @endrole
                    </ul>
                </nav>
            </div>
        </header>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        {{-- <script src="{{ asset('assets/js/jquery.min.js') }}"></script> --}}
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <script type="text/javascript" src="{{ asset('admin/js/bootstrap.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('admin/js/slick.js') }}"></script>
        <script type="text/javascript" src="{{ asset('admin/js/owl.js') }}"></script>
        <script src="{{ asset('assets/plugins/select2/select2.min.js') }}"></script>
        <script src="https://cdn.bootcss.com/toastr.js/latest/js/toastr.min.js"></script>
        <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
        <script type="text/javascript" src="{{ asset('admin/js/main.js') }}"></script>
        @if (Route::is('dashboard') || Route::is('retailer.dashboard'))
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
    <script type="text/javascript" src="{{ asset('admin/js/datatables.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable();
        });
    </script>
    <script>
        $(document).ready(function() {
            console.log("lo");
            var xyz = sessionStorage.getItem("compareLeftContent");
            console.log(xyz);
            if (xyz == undefined) {
                sessionStorage.setItem("compareLeftContent", "value");
            } else {
                console.log("hello");
            }
        });
    </script>
</body>
@if (Route::is('retailers.index') || Route::is('lps.index') || Route::is('admin.report.index'))
    <script src="{{ asset('admin/js/listing.js') }}"></script>
@endif
<script>
    $('#selectAll').click(function(e) {
        $(this).closest('table').find('td input:checkbox').prop('checked', this.checked);
    });

    $(document).ready(function() {
        var url = window.location;
        $('.nav li').removeClass('active');
        $('ul.nav a[href="' + url + '"]').parent().addClass('active');

        $('ul.nav a').filter(function() {
            return this.href == url;
        }).parent().addClass('active').parent().parent().addClass('active');
    });
</script>

</html>
