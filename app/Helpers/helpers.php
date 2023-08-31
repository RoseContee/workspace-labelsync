<?php
if (!function_exists('currency')) {
    function currency() {
        return \App\Models\Setting::getSetting('currency', '€');
    }
}

if (!function_exists('currency_format')) {
    function currency_format($number) {
        if ($number < 10e5) {
            $number = number_format($number, 2);
        } else if ($number < 10e8) {
            $number = number_format($number / 10e5, 2).'M';
        } else {
            $number = '1.00B+';
        }
        return currency().$number;
    }
}

if (!function_exists('getFavicon')) {
    function getFavicon($favicon) {
        if ($favicon && file_exists(public_path($favicon))) {
            return asset($favicon);
        }
        return asset('favicon.ico');
    }
}

if (!function_exists('getLogo')) {
    function getLogo($logo) {
        if ($logo && file_exists(public_path($logo))) {
            return asset($logo);
        }
        return asset('assets/img/logo.png');
    }
}
