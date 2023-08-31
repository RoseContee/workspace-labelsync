<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\License;
use App\Models\SyncLabel;
use App\Subscriptions\PayPalSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LicenseController extends Controller
{
    private string $menu = 'Licenses';

    public function __construct() {
        view()->share('menu', $this->menu);
    }

    public function index() {
        $licenses = License::orderBy('expires_on', 'desc')
            ->orderBy('active', 'desc')
            ->get();
        return view('admin.licenses.index', [
            'licenses' => $licenses,
        ]);
    }

    public function create() {
        return view('admin.licenses.create');
    }

    public function store(Request $request) {
        if (!is_numeric($timezone = $request['timezoneOffset'])) {
            return back()->withInput()->with('error_message', 'Something went wrong.');
        }
        $request->validate([
            'email' => ['required', 'email', 'unique:licenses'],
            'expires_on' => ['required', 'dateFormat:Y-m-d H:i:s'],
            'subscription_id' => ['required_with:payment_method', 'unique:licenses'],
            'payment_method' => ['nullable', 'in:paypal'],
        ]);
        $subscription_id = null;
        $payment_method = $request['payment_method'];
        if ($payment_method === 'paypal') {
            $subscription_id = $request['subscription_id'];
            $paypal = new PayPalSubscription();
            $subscription = $paypal->getSubscription($subscription_id);
            if (!$subscription) {
                return back()->withErrors([
                    'subscription_id' => ['Invalid subscription ID'],
                ])->withInput();
            }
        }
        License::create([
            'email' => $request['email'],
            'key' => Str::uuid(),
            'expires_on' => date('Y-m-d H:i:s', strtotime($request['expires_on']) + $timezone * 60),
            'active' => !empty($request['status']),
            'subscription_id' => $subscription_id,
            'payment_method' => $payment_method,
            'note' => $request['note'],
        ]);
        return redirect()->route('admin.licenses.index')
            ->with('success_message', 'New license key has been created.');
    }

    public function edit($id) {
        if (!($license = License::find($id))) {
            return back()->with('error_message', 'Cannot find license info.');
        }
        return view('admin.licenses.edit', [
            'license' => $license,
        ]);
    }

    public function update($id, Request $request) {
        if (!is_numeric($timezone = $request['timezoneOffset'])) {
            return back()->withInput()->with('error_message', 'Something went wrong.');
        }
        if (!($license = License::find($id))) {
            return back()->withInput()->with('error_message', 'Cannot find license info.');
        }
        $rule = [];
        if (!$license['email']) {
            $rule['email'] = ['nullable', 'email', 'unique:licenses'];
        }
        if (!$license['subscription_id']) {
            $rule['expires_on'] = ['required', 'dateFormat:Y-m-d H:i:s'];
            $rule['subscription_id'] = [
                'required_with:payment_method',
                $request['subscription_id'] ? 'unique:licenses' : ''
            ];
            $rule['payment_method'] = ['nullable', 'in:paypal'];
        }
        $request->validate($rule);
        $subscription_id = null;
        $payment_method = null;
        if (!$license['subscription_id']) {
            if ($payment_method === 'paypal') {
                $subscription_id = $request['subscription_id'];
                $payment_method = $request['payment_method'];
                $paypal = new PayPalSubscription();
                $subscription = $paypal->getSubscription($subscription_id);
                if (!$subscription) {
                    return back()->withErrors([
                        'subscription_id' => ['Invalid subscription ID'],
                    ])->withInput();
                }
            }
        }
        if (!$license['email']) {
            $license['email'] = $request['email'];
        } else if ($request['key_update']) {
            $license['key'] = Str::uuid();
        }
        if (!$license['subscription_id']) {
            $license['expires_on'] = date('Y-m-d H:i:s', strtotime($request['expires_on']) + $timezone * 60);
            $license['subscription_id'] = $subscription_id;
            $license['payment_method'] = $payment_method;
        }
        $license['active'] = !empty($request['status']);
        $license['note'] = $request['note'];
        $license->save();
        return back()->with('info_message', 'License info has been updated.');
    }

    public function destroy($id) {
        if (!($license = License::find($id))) {
            return back()->with('error_message', 'Cannot find license info.');
        }
        if (!$license['subscription_id'] || now() >= $license['expires_on']) {
            SyncLabel::where('email', $license['email'])->delete();
            $license->delete();
            return back()->with('info_message', 'License info has been removed.');
        }
        $license['active'] = false;
        $license->save();
        return back()->with('info_message', 'License info has been disabled.');
    }
}
