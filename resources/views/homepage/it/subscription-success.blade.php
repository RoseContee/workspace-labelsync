@extends('homepage.en.layouts')

@section('title', 'Thank your for starting')

@section('metadata')
@endsection

@section('content')
    <section class="section-bg">
        <div class="container">
            <div class="py-5"></div>
            <h2 class="text-success mt-5 mb-5">Thank you for starting {{ $plan }}</h2>
            <p>You have started {{ $plan }}. Here is the information you can use for activation.</p>
            <p class="my-5">License Key: <b>{{ $key }}</b></p>
            <p>Do not close or reload this page before you copy this key.
                But do not worry we will email this key to your email address you used in payment.</p>
        </div>
    </section>
@endsection
