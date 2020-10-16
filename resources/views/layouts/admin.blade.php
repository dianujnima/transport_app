<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8" />
        <title>{{ $title ?? 'Dashboard' }} - {{ config('app.name') }}</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />

        @yield('before-css')
        <!-- App favicon -->
        <link href="{{ asset('frontend_assets') }}/images/favicon.png" sizes="128x128" rel="shortcut icon" />
        
        <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700,900&display=swap" rel="stylesheet">

        <!-- Plugins css -->
        <link href="{{ asset('admin_assets') }}/libs/select2/select2.min.css" rel="stylesheet" type="text/css" />
        <link href="{{ asset('admin_assets') }}/css/bundled.min.css" rel="stylesheet" type="text/css" />
        <link href="{{ asset('admin_assets') }}/css/dianujStyles.css" rel="stylesheet" type="text/css" />
        <style>
            .select2-container .select2-selection--single .select2-selection__rendered {
                line-height: 1.9;
            }
            .bootstrap-select .inner{
                overflow-y: auto !important;
            }
        </style>
        <script>
            var __site_url__ = '{{route("admin.home")}}';
        </script>
    </head>

    <body class="left-side-menu-dark">

        <div id="preloader" class="preloader">
            <div id="status">
                <div class="spinner">Loading...</div>
            </div>
        </div>

        <!-- Begin page -->
        <div id="wrapper">

            <!-- Topbar Start -->
            <div class="navbar-custom">
                <ul class="list-unstyled topnav-menu float-right mb-0">

                    @include('admin.partials.notifications')

                    <li class="dropdown notification-list">
                        <a class="nav-link dropdown-toggle nav-user mr-0 waves-effect waves-light" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                            <img src="{{ check_file(auth()->user()->image, 'user') }}" alt="{{ auth()->user()->full_name }}" class="rounded-circle fit-image">
                            <span class="pro-user-name ml-1">
                                {{ auth('admin')->user()->fullname }} <i class="mdi mdi-chevron-down"></i>
                            </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                            <!-- item-->
                            <div class="dropdown-header noti-title">
                                <h6 class="text-overflow m-0">Welcome {{ auth('admin')->user()->fullname }}!</h6>
                            </div>
                            
                            <!-- item-->
                            <a href="{{ route('admin.update_profile') }}" class="dropdown-item notify-item">
                                <i class="fe-star"></i>
                                <span>Update Profile</span>
                            </a>


                            <div class="dropdown-divider"></div>

                            <!-- item-->
                            <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                            <a href="{{ route('admin.logout') }}" onclick="logout(event)" class="dropdown-item notify-item">
                                <i class="fe-log-out"></i>
                                <span>Logout</span>
                            </a>

                        </div>
                    </li>
                </ul>

                <!-- LOGO -->
                <div class="logo-box">
                    <a href="{{ route('admin.home') }}" class="logo text-center">
                        <span class="logo-lg">
                            <img src="{{ asset('mix_assets') }}/images/white_header_logo.png" alt="{{ config('app.name') }}" width="120">
                        </span>
                        <span class="logo-sm">
                            <img src="{{ asset('mix_assets') }}/images/white_header_logo.png" alt="{{ config('app.name') }}" width="43">
                        </span>
                    </a>
                </div>

                <ul class="list-unstyled topnav-menu topnav-menu-left m-0">
                    <li>
                        <button class="button-menu-mobile waves-effect waves-light">
                            <i class="fe-menu"></i>
                        </button>
                    </li>
                </ul>
            </div>
            <!-- end Topbar -->

            <!-- ========== Left Sidebar Start ========== -->
            <div class="left-side-menu">

                <div class="slimscroll-menu">

                    <!--- Sidemenu -->
                    <div id="sidebar-menu">
                        @php
                            $admin = auth('admin')->user();
                        @endphp
                        <ul class="metismenu" id="side-menu">
                            <li class="menu-title">Navigation</li>

                            <li>
                                <a href="{{ route('admin.home') }}">
                                    <i class="fe-airplay"></i>
                                    <!-- <span class="badge badge-success badge-pill float-right">4</span> -->
                                    <span> Dashboards </span>
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('admin.categories') }}">
                                    <i class="fe-users"></i>
                                    <span> Categories </span>
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('admin.cities') }}">
                                    <i class="fe-users"></i>
                                    <span> Cities </span>
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('admin.providers') }}">
                                    <i class="fe-users"></i>
                                    <span> Providers </span>
                                </a>
                            </li>
                            
                            <li>
                                <a href="{{ route('admin.users') }}">
                                    <i class="fe-users"></i>
                                    <span> Frontend Users </span>
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('admin.schedules') }}">
                                    <i class="fe-users"></i>
                                    <span> Provider Schedules </span>
                                </a>
                            </li>

                            @if($admin->is_admin)
                            <li>
                                <a href="{{ route('admin.staffs') }}">
                                    <i class="fe-users"></i>
                                    <span> Staff Members </span>
                                </a>
                            </li>
                            @endif
                        </ul>

                    </div>
                    <!-- End Sidebar -->

                    <div class="clearfix"></div>

                </div>
                <!-- Sidebar -left -->

            </div>
            <!-- Left Sidebar End -->

            <!-- ============================================================== -->
            <!-- Start Page Content here -->
            <!-- ============================================================== -->
            <div class="content-page">
                <div class="content">
                    <div class="container-fluid">
                        @yield('content')
                    </div>
                </div>
                <!-- Footer Start -->
                <footer class="footer">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                {{ date('Y') }} &copy; All rights reserved by {{ config('app.name') }}. Design &amp; Developed by US.
                            </div>
                        </div>
                    </div>
                </footer>
                <!-- end Footer -->
            </div>

            <!-- ============================================================== -->
            <!-- End Page content -->
            <!-- ============================================================== -->


        </div>
        <!-- END wrapper -->

        <!-- Right bar overlay-->
        <div class="rightbar-overlay"></div>

        <script src="{{ asset('admin_assets') }}/js/bundled.min.js"></script>
        <script>
            function pageloader(status) {
                if (status == 'hide') {
                    $('.preloader').hide();
                    $('#status').hide();
                    return;
                }
                $('.preloader').show();
                $('#status').show();
                return;
            }
        </script>

        @yield('page-scripts')

        <script src="{{ asset('admin_assets') }}/js/app.min.js"></script>
        <script src="{{ asset('mix_assets') }}/js/custom.js"></script>

    </body>

</html>