<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Membership;
use Illuminate\Http\Request;

class MembershipController extends Controller
{
    private string $menu = 'Memberships';

    public function __construct() {
        view()->share('menu', $this->menu);
    }

    public function index() {
        $memberships = Membership::get();
        return view('home.memberships.index', [
            'memberships' => $memberships,
        ]);
    }

    public function create() {
        return view('home.memberships.edit');
    }

    public function store(Request $request) {
        $request->validate([
            'name' => ['required'],
            'price' => ['required', 'numeric'],
            'origin_price' => ['nullable', 'numeric'],
            'period' => ['required', 'numeric'],
            'unit' => ['required', 'in:year,month,day'],
            'supported_features' => ['required'],
        ]);
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
        ]);
        return redirect()->route('memberships.index')
            ->with('success_message', 'New membership has been created.');
    }

    public function edit($id) {
        $membership = Membership::find($id);
        if (!$membership) {
            return back()->with('error_message', 'Cannot find membership.');
        }
        return view('home.memberships.edit', [
            'membership' => $membership,
        ]);
    }

    public function update($id, Request $request) {
        $membership = Membership::find($id);
        if (!$membership) {
            return back()->with('error_message', 'Cannot find membership.');
        }
        $request->validate([
            'name' => ['required'],
            'price' => ['required', 'numeric'],
            'origin_price' => ['nullable', 'numeric'],
            'period' => ['required', 'numeric'],
            'unit' => ['required', 'in:year,month,day'],
            'supported_features' => ['required'],
        ]);
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
