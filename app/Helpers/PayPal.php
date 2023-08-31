<?php

namespace App\Helpers;

class PayPal
{
    static public function planPeriod($plan) {
        return $plan['billing_cycles'][0]['frequency']['interval_count'] ?? '';
    }

    static public function planUnit($plan) {
        return strtolower($plan['billing_cycles'][0]['frequency']['interval_unit'] ?? '');
    }

    static public function getExpiresOn($subscription) {
        return $subscription['billing_info']['next_billing_time'] ?? 'now';
    }

    static public function isActive($subscription) {
        return strtoupper($subscription['status'] ?? '') === 'ACTIVE';
    }

    static public function isCancelled($subscription) {
        return strtoupper($subscription['status'] ?? '') === 'CANCELLED';
    }
}
