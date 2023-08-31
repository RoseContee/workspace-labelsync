<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') | {{ config('app.name') }}</title>

    <link rel="icon" type="image/x-icon" href="{{ getFavicon($settings['favicon'] ?? '') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('assets/admin/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- IonIcons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('assets/admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="{{ asset('assets/admin/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('assets/admin/theme/css/adminlte.min.css') }}">
    <!-- Main style -->
    <link rel="stylesheet" href="{{ asset('assets/admin/css/main.css') }}">

    @stack('styles')
</head>
<body class="sidebar-mini {{ $dark_mode ? 'dark-mode' : '' }}">
<div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand {{ $dark_mode ? 'navbar-dark' : 'navbar-white navbar-light'}}">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <!-- Navbar Search -->
            <li class="nav-item">
                <div class="custom-control custom-switch custom-switch-on-dark">
                    <input type="checkbox" id="dark-mode"
                           @if ($dark_mode) checked @endif
                           class="custom-control-input">
                    <label class="custom-control-label" for="dark-mode">Dark Mode</label>
                </div>
            </li>
        </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar elevation-4 {{ $dark_mode ? 'sidebar-dark-primary' : 'sidebar-light-primary' }}">
        <!-- Logo -->
        <a href="{{ route('admin.home') }}" class="brand-link">
            <img src="{{ asset('assets/admin/img/admin-logo.png') }}" alt="Logo" class="brand-image img-circle elevation-3">
            <span class="brand-text font-weight">Admin</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar Menu -->
            <nav>
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <li class="nav-item">
                        <a href="{{ route('admin.dashboard') }}" class="nav-link @if($menu == 'Dashboard') active @endif">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.licenses.index') }}" class="nav-link @if($menu == 'Licenses') active @endif">
                            <i class="nav-icon fas fa-key"></i>
                            <p>Licenses</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.memberships.index') }}" class="nav-link @if($menu == 'Memberships') active @endif">
                            <i class="nav-icon fas fa-shopping-cart"></i>
                            <p>Memberships</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.transactions.index') }}" class="nav-link @if($menu == 'Transactions') active @endif">
                            <i class="nav-icon fas fa-money-bill-wave"></i>
                            <p>Transactions</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.contacts.index') }}" class="nav-link @if($menu == 'Contacts') active @endif">
                            <i class="nav-icon fas fa-comments"></i>
                            <p>
                                Contacts
                                <span class="badge badge-info right">{{ \App\Models\Contact::unread()->count() }}</span>
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.settings.index') }}" class="nav-link @if($menu == 'Settings') active @endif">
                            <i class="nav-icon fas fa-cogs"></i>
                            <p>Settings</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.profile') }}" class="nav-link @if($menu == 'Profile') active @endif">
                            <i class="nav-icon fas fa-user"></i>
                            <p>Profile</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.logout') }}" class="nav-link">
                            <i class="nav-icon fas fa-sign-out-alt"></i>
                            <p>Logout</p>
                        </a>
                    </li>
                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>

    @yield('content')

    <!-- Main Footer -->
    <footer class="main-footer">
        <strong>
            Copyright &copy; {{ date('Y') }}
            <a href="{{ route('admin.home') }}">{{ config('app.name') }}</a>.
        </strong>
        All rights reserved.
    </footer>
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="{{ asset('assets/admin/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap -->
<script src="{{ asset('assets/admin/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- DataTables  & Plugins -->
<script src="{{ asset('assets/admin/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/admin/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/admin/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/admin/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<!-- Moment -->
<script src="{{ asset('assets/admin/plugins/moment/moment.min.js') }}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{ asset('assets/admin/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
<!-- AdminLTE -->
<script src="{{ asset('assets/admin/theme/js/adminlte.js') }}"></script>
<!-- Main -->
<script src="{{ asset('assets/admin/js/main.js') }}"></script>

<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        },
    });
</script>

<script type="text/javascript">
    $(() => {
        $('#dark-mode').on('change', function() {
            const darkMode = $(this).prop('checked');
            const body = $(document.body);
            const mainHeader = $('.main-header');
            const mainSidebar = $('.main-sidebar');
            if (darkMode) {
                body.addClass('dark-mode');
                mainHeader.addClass('navbar-dark').removeClass('navbar-white navbar-light');
                mainSidebar.addClass('sidebar-dark-primary').removeClass('sidebar-light-primary');
            } else {
                body.removeClass('dark-mode');
                mainHeader.addClass('navbar-white navbar-light').removeClass('navbar-dark');
                mainSidebar.addClass('sidebar-light-primary').removeClass('sidebar-dark-primary');
            }
            $.ajax({
                url: '{{ route('admin.update-theme') }}',
                method: 'POST',
                data: {
                    darkMode: darkMode,
                },
            });
        });
    });
</script>

@stack('scripts')

</body>
</html>
