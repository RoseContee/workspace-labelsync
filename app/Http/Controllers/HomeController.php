<?php

namespace App\Http\Controllers;

use App\Mail\ContactInfo;
use App\Models\Contact;
use App\Models\Membership;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class HomeController extends Controller
{
    public function index() {
        $plans = Membership::active()
            ->where('paypal_plan_id', '!=', '')
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
        if ($contact_email = Setting::getSetting('contact_email')) {
            try {
                Mail::to($contact_email)->send(new ContactInfo(
                    $request['subject'],
                    $request['name'],
                    $request['email'],
                    $request['message'],
                ));
            } catch (\Exception $exception) {
                logger($exception);
            }
        }
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
