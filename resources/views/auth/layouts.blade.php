<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') | {{ getSiteName($settings['site_name'] ?? null) }}</title>

    <link rel="icon" type="image/x-icon" href="{{ getFavicon($settings['favicon'] ?? '') }}">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('assets/admin/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('assets/admin/theme/css/adminlte.min.css') }}">

    @stack('styles')
</head>
<body class="login-page {{ $dark_mode ? 'dark-mode' : '' }}">
<div class="login-box">
    <!-- /.login-logo -->
    <div class="card card-outline card-primary">
        <div class="card-header text-center">
            <a href="{{ route('admin.login') }}" class="h3">
                <b>{{ getSiteName($settings['site_name'] ?? null) }}</b>
            </a>
        </div>
        <div class="card-body">

            @yield('content')

        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
</div>
<!-- /.login-box -->

@stack('scripts')
</body>
</html>
