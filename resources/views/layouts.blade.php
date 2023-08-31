<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>@yield('title') | {{ config('app.name') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @yield('metadata')

    <!-- Favicons -->
    <link href="{{ getFavicon($settings['favicon'] ?? null) }}" rel="icon">
    <link href="{{ asset('assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Jost:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
    <!-- Vendor CSS Files -->
    <link href="{{ asset('assets/vendor/aos/aos.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
    <!-- Template Main CSS File -->
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">

    @stack('styles')

</head>

<body>
<!-- ======= Header ======= -->
<header id="header" class="fixed-top ">
    <div class="container d-flex align-items-center">
        {{--<h1 class="logo me-auto">
            <a href="{{ route('home') }}">{{ config('app.name') }}</a>
        </h1>--}}
        <a href="{{ route('home') }}" class="logo me-auto">
            <img src="{{ getLogo($settings['logo'] ?? null) }}" alt="Logo" class="img-fluid">
        </a>

        <nav id="navbar" class="navbar">
            <ul>
                <li><a href="{{ route('home') }}#hero" class="nav-link scrollto active">{{ __('Home') }}</a></li>
                <li><a href="{{ route('home') }}#about" class="nav-link scrollto">{{ __('About') }}</a></li>
                <li><a href="{{ route('home') }}#services" class="nav-link scrollto">{{ __('Services') }}</a></li>
                <li><a href="{{ route('home') }}#team" class="nav-link scrollto">{{ __('Team') }}</a></li>
                <li><a href="{{ route('home') }}#pricing" class="nav-link scrollto">{{ __('Pricing') }}</a></li>
                <li><a href="{{ route('home') }}#contact" class="nav-link scrollto">{{ __('Contact') }}</a></li>
                @php $locale = session('locale', 'en'); @endphp
                @if ($locale === 'en')
                    <li class="dropdown">
                        <a href="javascript:void(0);">
                            <span>{{ __('English') }}</span> <i class="bi bi-chevron-down"></i>
                        </a>
                        <ul>
                            <li><a href="{{ route('language', ['locale' => 'it']) }}">{{ __('Italian') }}</a></li>
                        </ul>
                    </li>
                @else
                    <li class="dropdown">
                        <a href="javascript:void(0);">
                            <span>{{ __('Italian') }}</span> <i class="bi bi-chevron-down"></i>
                        </a>
                        <ul>
                            <li><a href="{{ route('language', ['locale' => 'en']) }}">{{ __('English') }}</a></li>
                        </ul>
                    </li>
                @endif
            </ul>
            <i class="bi bi-list mobile-nav-toggle"></i>
        </nav><!-- .navbar -->
    </div>
</header><!-- End Header -->

<main id="main">

    @yield('content')

</main><!-- End #main -->

<!-- ======= Footer ======= -->
<footer id="footer">
    <div class="footer-top">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6 footer-contact">
                    <h3>{{ __('Garofalo & Partners') }}</h3>
                    <p class="mb-4 text-pre-line">{!! $settings['contact_address'] ?? '' !!}</p>
                    <p><strong>{{ __('Phone') }}:</strong> {{ $settings['contact_phone'] ?? '' }}</p>
                    <p><strong>{{ __('Email') }}:</strong> {{ $settings['contact_email'] ?? '' }}</p>
                </div>
                <div class="col-lg-3 col-md-6 footer-links">
                    <h4>{{ __('Useful Links') }}</h4>
                    <ul>
                        <li>
                            <i class="bx bx-chevron-right"></i>
                            <a href="{{ route('home') }}" class="scrollto">{{ __('Home') }}</a>
                        </li>
                        <li>
                            <i class="bx bx-chevron-right"></i>
                            <a href="{{ route('home') }}#about" class="scrollto">{{ __('About us') }}</a>
                        </li>
                        <li>
                            <i class="bx bx-chevron-right"></i>
                            <a href="{{ route('home') }}#services" class="scrollto">{{ __('Services')}}</a>
                        </li>
                        <li>
                            <i class="bx bx-chevron-right"></i>
                            <a href="{{ route('terms') }}">{{ __('Terms of service') }}</a>
                        </li>
                        <li>
                            <i class="bx bx-chevron-right"></i>
                            <a href="{{ route('privacy') }}">{{ __('Privacy policy') }}</a>
                        </li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 footer-links">
                    <h4>{{ __('Our Services') }}</h4>
                    <ul>
                        <li>
                            <i class="bx bx-chevron-right"></i>
                            <a href="{{ route('home') }}#services" class="scrollto">{{ __('Custom Software Development') }}</a>
                        </li>
                        <li>
                            <i class="bx bx-chevron-right"></i>
                            <a href="{{ route('home') }}#services" class="scrollto">{{ __('Web and Mobile App Expertise') }}</a>
                        </li>
                        <li>
                            <i class="bx bx-chevron-right"></i>
                            <a href="{{ route('home') }}#services" class="scrollto">{{ __('Cloud Solutions and Integration') }}</a>
                        </li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 footer-links">
                    <h4>{{ __('Follow us') }}</h4>
                    <p>{{ __('Discover our vibrant social network, connecting individuals through shared interests and meaningful interactions')}}</p>
                    <div class="social-links mt-3">
                        @if ($facebook = $settings['facebook_link'] ?? '')
                            <a href="{!! $facebook !!}" class="facebook"><i class="bx bxl-facebook"></i></a>
                        @endif
                        @if ($skype = $settings['skype_link'] ?? '')
                            <a href="{!! $skype !!}" class="google-plus"><i class="bx bxl-skype"></i></a>
                        @endif
                        @if ($linkedin = $settings['linkedin_link'] ?? '')
                            <a href="{!! $linkedin !!}" class="linkedin"><i class="bx bxl-linkedin"></i></a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container footer-bottom clearfix">
        <div class="copyright">
            &copy; Copyright <strong><span>{{ config('app.name') }}</span></strong>.
            All Rights Reserved
        </div>
        <div class="credits">
            Designed by <a href="https://www.garofaloandpartners.it/">SIIGEP</a>
        </div>
    </div>
</footer><!-- End Footer -->

<div id="preloader"></div>
<a href="javascript:void(0);" class="back-to-top d-flex align-items-center justify-content-center">
    <i class="bi bi-arrow-up-short"></i>
</a>

<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1000">
    @include('partials.messages')
</div>

<!-- Vendor JS Files -->
<script src="{{ asset('assets/vendor/aos/aos.js') }}"></script>
<script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/vendor/isotope-layout/isotope.pkgd.min.js') }}"></script>
<script src="{{ asset('assets/vendor/waypoints/noframework.waypoints.js') }}"></script>
<script src="{{ asset('assets/vendor/php-email-form/validate.js') }}"></script>
<!-- Template Main JS File -->
<script src="{{ asset('assets/js/main.js') }}"></script>

@stack('scripts')

</body>
</html>
