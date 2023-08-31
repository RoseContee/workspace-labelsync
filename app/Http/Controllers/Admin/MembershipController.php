<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Membership;
use App\Subscriptions\PayPalSubscription;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MembershipController extends Controller
{
    private string $menu = 'Memberships';

    public function __construct() {
        view()->share('menu', $this->menu);
    }

    public function index() {
        $memberships = Membership::get();
        return view('admin.memberships.index', [
            'memberships' => $memberships,
        ]);
    }

    public function create() {
        return view('admin.memberships.edit');
    }

    public function store(Request $request) {
        $request->validate([
            'name' => ['required'],
            'price' => ['required', 'numeric'],
            'origin_price' => ['nullable', 'numeric'],
            'period' => ['required', 'numeric'],
            'unit' => ['required', 'in:year,month,week,day'],
            'supported_features' => ['required'],
            'paypal_plan_id' => ['required', 'unique:memberships'],
        ]);
        $paypal = new PayPalSubscription();
        if (!($plan = $paypal->getPlan($request['paypal_plan_id']))) {
            return back()->withErrors([
                'paypal_plan_id' => ['Invalid plan ID'],
            ])->withInput();
        }
        Membership::create([
            'name' => $request['name'],
            'price' => $request['price'],
            'origin_price' => $request['origin_price'],
            'period' => $request['period'],
            'unit' => $request['unit'],
            'supported_features' => $request['supported_features'],
            'unsupported_features' => $request['unsupported_features'],
            'description' => $request['description'],
            'featured' => !empty($request['featured']),
            'active' => !empty($request['status']),
            'paypal_plan_id' => $plan['id'],
        ]);
        return redirect()->route('admin.memberships.index')
            ->with('success_message', 'New membership has been created.');
    }

    public function edit($id) {
        $membership = Membership::find($id);
        if (!$membership) {
            return back()->with('error_message', 'Cannot find membership.');
        }
        return view('admin.memberships.edit', [
            'membership' => $membership,
        ]);
    }

    public function update($id, Request $request) {
        $membership = Membership::find($id);
        if (!$membership) {
            return back()->withInput()->with('error_message', 'Cannot find membership.');
        }
        $request->validate([
            'name' => ['required'],
            'price' => ['required', 'numeric'],
            'origin_price' => ['nullable', 'numeric'],
            'period' => ['required', 'numeric'],
            'unit' => ['required', 'in:year,month,week,day'],
            'supported_features' => ['required'],
            'paypal_plan_id' => ['required', Rule::unique('memberships')->ignore($membership['id'])],
        ]);
        $paypal = new PayPalSubscription();
        if (!($plan = $paypal->getPlan($request['paypal_plan_id']))) {
            return back()->withErrors([
                'paypal_plan_id' => ['Invalid plan ID'],
            ])->withInput();
        }
        $membership['name'] = $request['name'];
        $membership['price'] = $request['price'];
        $membership['origin_price'] = $request['origin_price'];
        $membership['period'] = $request['period'];
        $membership['unit'] = $request['unit'];
        $membership['supported_features'] = $request['supported_features'];
        $membership['unsupported_features'] = $request['unsupported_features'];
        $membership['description'] = $request['description'];
        $membership['featured'] = !empty($request['featured']);
        $membership['active'] = !empty($request['status']);
        $membership['paypal_plan_id'] = $plan['id'];
        $membership->save();
        return back()->with('info_message', 'Membership has been updated.');
    }

    public function destroy($id) {
        $membership = Membership::find($id);
        if (!$membership) {
            return back()->with('error_message', 'Cannot find membership.');
        }
        $membership['active'] = false;
        $membership->save();
        return back()->with('info_message', 'Membership has been disabled.');
    }
}
