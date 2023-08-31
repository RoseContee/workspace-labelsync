@extends('layouts')

@section('title', 'Thank your for starting')

@section('metadata')
@endsection

@section('content')
    <section class="section-bg">
        <div class="container">
            <div class="py-5"></div>
            <h2 class="text-success mt-5 mb-5">{{ __('Thank you for starting :NAME', ['NAME' => $plan]) }}</h2>
            <p>{{ __('You have started :NAME. Here is the information you can use for activation.', ['NAME' => $plan]) }}</p>
            <p class="my-5">{{ __('License Key') }}: <b>{{ $key }}</b></p>
            <p>{{ __('Do not close or reload this page before you copy this key. But do not worry we will email this key to your email address you used in payment.') }}</p>
        </div>
    </section>
@endsection
