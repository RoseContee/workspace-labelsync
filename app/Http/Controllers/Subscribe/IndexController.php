<?php

namespace App\Http\Controllers\Subscribe;

use App\Http\Controllers\Controller;
use App\Models\Membership;
use App\Subscriptions\PayPalSubscription;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function subscribe(Request $request) {
        if (!$request['plan'] || !($plan = Membership::find($request['plan']))) {
            return back()->with('error_message', 'Something went wrong.');
        }
        return (new PayPalSubscription())->create($plan);
    }

    public function cancel() {
        return redirect()->route('home')
            ->with('info_message', 'Your payment has been cancelled.');
    }
}
