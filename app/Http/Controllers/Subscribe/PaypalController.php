<?php

namespace App\Http\Controllers\Subscribe;

use App\Http\Controllers\Controller;
use App\Subscriptions\PayPalSubscription;
use Illuminate\Http\Request;

class PaypalController extends Controller
{
    public function success(Request $request) {
        $payment = new PayPalSubscription();
        $paypal = $payment->getProvider();
        $paypal->getAccessToken();
        logger($paypal->showSubscriptionDetails($request['subscription_id']));
    }

    public function webhook(Request $request) {
        logger('webhook');
        logger($request);
    }
}
