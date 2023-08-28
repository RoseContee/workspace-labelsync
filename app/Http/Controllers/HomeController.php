<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Membership;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index() {
        $plans = Membership::active()
            ->where('paypal_subscription_id', '!=', '')
            ->get();
        return view('index', [
            'plans' => $plans,
        ]);
    }

    public function contact(Request $request) {
        $request->validate([
            'name' => ['required'],
            'email' => ['required', 'email'],
            'subject' => ['required'],
            'message' => ['required'],
        ]);
        Contact::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'subject' => $request['subject'],
            'message' => $request['message'],
        ]);
        return response('OK');
    }

    public function terms() {
        return view('terms');
    }

    public function privacy() {
        return view('privacy');
    }
}
