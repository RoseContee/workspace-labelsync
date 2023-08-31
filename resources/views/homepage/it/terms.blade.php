@extends('homepage.en.layouts')

@section('title', '')

@section('metadata')
    <link rel="canonical" href="{{ route('terms') }}">

    <meta content="" name="description">
    <meta content="" name="keywords">

    <meta property="og:type" content="">
    <meta property="og:url" content="{{ route('terms') }}">
    <meta property="og:title" content="">
    <meta property="og:description" content="">
    <meta property="og:site_name" content="">
@endsection

@section('content')
    <section class="section-bg">
        <div class="container">
            <h2 class="mt-5">Terms of Service</h2>
            <p class="fw-bold small">Last updated on September 1, 2023</p>
            <p>Welcome to the LabelSync Chrome extension, hereinafter referred to as the "Extension,
                " provided by Garofalo & Partners, hereinafter referred to as "we," "us," "our," or the "Company,"
                with its registered office at Largo Conservatorio Vecchio 1,3, 84121 – Salerno – Italy.
                Your use of the Extension is subject to the following Terms and Conditions ("Terms").
                Please read these Terms carefully before using the Extension. By using the Extension,
                you agree to be bound by the following Terms. If you do not agree to these Terms,
                please do not use the Extension.</p>
            <br>
            <h5>1. Acceptance of Terms</h5>
            <p>By using the Extension, you declare that you are at least 18 years old
                and accept all the Terms described herein. If you are using the Extension
                on behalf of an organization, you represent that you have the authority
                to bind that organization to these Terms.</p>
            <br>
            <h5>2. Use of the Extension</h5>
            <p><b>2.1 User Account:</b> You may need to create a user account to access certain features
                of the Extension. You agree to provide accurate and up-to-date information
                during the registration process and to maintain the security of your account credentials.</p>
            <p><b>2.2 Permitted Use:</b> You agree to use the Extension only for lawful purposes
                and in compliance with all applicable laws and regulations.</p>
            <p><b>2.3 User Content:</b> Any content (text, images, videos, etc.) you provide
                through the Extension is your sole responsibility. Do not upload, share, or transmit content
                that infringes upon third-party rights or is unlawful, defamatory, offensive, or deceptive.</p>
            <br>
            <h5>3. Intellectual Property</h5>
            <p>The Extension, including but not limited to trademarks, logos, text, graphics, and software,
                are the exclusive property of Garofalo & Partners. Copying, distributing, modifying,
                or using such elements without our explicit consent is prohibited.</p>
            <br>
            <h5>4. Limitation of Liability</h5>
            <p><b>4.1 No Warranties:</b> The Extension is provided "as is" without any express or implied warranties.
                We do not warrant the accuracy, reliability, or suitability of the Extension for a specific purpose.</p>
            <p><b>4.2 Limitation of Liability:</b> Under no circumstances shall Garofalo & Partners
                be liable for direct, indirect, special, consequential, or punitive damages arising
                from the use or inability to use the Extension.</p>
            <br>
            <h5>5. Changes to the Terms</h5>
            <p>We reserve the right to modify or update these Terms at any time. Changes will be notified
                through the Extension or the website labelsync.it. Your continued use of the Extension
                after such changes constitutes acceptance of the revised Terms.</p>
            <br>
            <h5>6. Termination</h5>
            <p>We reserve the right to suspend or terminate your access to the Extension
                in case of violation of the Terms.</p>
            <br>
            <h5>7. Applicable Law</h5>
            <p>These Terms are governed and interpreted in accordance with the laws of Italy.</p>
            <p>By using the Extension, you agree to comply with and be bound by these Terms and Conditions.
                For questions or comments, please contact info@siigep.tech.</p>
        </div>
    </section>
@endsection
