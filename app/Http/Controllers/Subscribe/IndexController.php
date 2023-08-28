<?php

namespace App\Http\Controllers\Subscribe;

use App\Http\Controllers\Controller;
use App\Subscriptions\PayPalSubscription;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function subscribe(Request $request) {
        $request->validate([
            'plan' => ['required', 'exists:memberships,id'],
        ]);
        $subscription = new PayPalSubscription();
        return $subscription->create($request['plan']);
    }

    public function cancel(Request $request) {
        return redirect()->route('home')
            ->with('info_message', 'Your payment has been cancelled.');
    }
}
