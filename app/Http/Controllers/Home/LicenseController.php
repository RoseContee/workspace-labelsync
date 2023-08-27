<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\License;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LicenseController extends Controller
{
    private string $menu = 'Licenses';

    public function __construct() {
        view()->share('menu', $this->menu);
    }

    public function index() {
        $licenses = License::with(['membership', 'transaction'])
            ->orderBy('expires_on', 'desc')
            //->orderBy('active', 'desc')
            ->get();
        $now = now();
        $result = [];
        foreach ($licenses as $license) {
            if (!$license['active']) $status = 'Disabled';
            else if ($now >= $license['expires_on']) $status = 'Expired';
            else $status = 'Active';
            $result[] = [
                'id' => $license['id'],
                'email' => $license['email'],
                'key' => $license['key'],
                'status' => $status,
                'expires_on' => $license['expires_on'],
                'membership' => $license['membership']['name'] ?? '',
                'transaction_id' => $license['transaction_id'],
                'note' => $license['note'],
                'transaction' => ($license['transaction']['status'] ?? '') === 'completed',
            ];
        }
        return view('home.licenses.index', [
            'licenses' => $result,
        ]);
    }

    public function create() {
        return view('home.licenses.create');
    }

    public function store(Request $request) {
        $timezone = $request['timezoneOffset'];
        if (!is_numeric($timezone)) {
            return back()->withInput()->with('error_message', 'Something went wrong.');
        }
        $request->validate([
            'email' => ['required', 'email', 'unique:licenses'],
            'expires_on' => ['required', 'dateFormat:Y-m-d H:i:s'],
        ]);
        License::create([
            'email' => $request['email'],
            'key' => Str::uuid(),
            'expires_on' => date('Y-m-d H:i:s', strtotime($request['expires_on']) + $timezone * 60),
            'active' => !empty($request['status']),
            'note' => $request['note'],
            'transaction_id' => $request['transaction_id'],
        ]);
        return redirect()->route('licenses.index')
            ->with('success_message', 'New license key has been created.');
    }

    public function edit($id) {
        $license = License::with(['transaction'])->find($id);
        if (!$license) {
            return back()->with('error_message', 'Cannot find license info.');
        }
        return view('home.licenses.edit', [
            'license' => $license,
        ]);
    }

    public function update($id, Request $request) {
        $timezone = $request['timezoneOffset'];
        if (!is_numeric($timezone)) {
            return back()->withInput()->with('error_message', 'Something went wrong.');
        }
        $license = License::with(['transaction'])->find($id);
        if (!$license) {
            return back()->with('error_message', 'Cannot find license info.');
        }
        $request->validate([
            'expires_on' => ['required', 'dateFormat:Y-m-d H:i:s'],
        ]);
        if ($request['key_update']) {
            $license['key'] = Str::uuid();
        }
        $license['expires_on'] = date('Y-m-d H:i:s', strtotime($request['expires_on']) + $timezone * 60);
        $license['active'] = !empty($request['status']);
        $license['note'] = $request['note'];
        if (!$license['transaction']) {
            $license['transaction_id'] = $request['transaction_id'];
        }
        $license->save();
        return back()->with('info_message', 'License info has been updated.');
    }

    public function destroy($id) {
        $license = License::with(['transaction'])->find($id);
        if (!$license) {
            return back()->with('error_message', 'Cannot find license info.');
        }
        if (!$license['transaction']) {
            $license->delete();
            return back()->with('info_message', 'License info has been removed.');
        }
        $license['active'] = false;
        $license->save();
        return back()->with('info_message', 'License info has been disabled.');
    }
}
