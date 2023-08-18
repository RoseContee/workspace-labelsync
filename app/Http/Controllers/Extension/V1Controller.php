<?php

namespace App\Http\Controllers\Extension;

use App\Http\Controllers\Controller;
use App\Models\License;
use App\Models\Setting;
use App\Models\SyncLabel;
use Illuminate\Http\Request;

class V1Controller extends Controller
{
    public function submitKey(Request $request) {
        $email = $request['email'];
        $key = $request['key'];
        if (!$email || !$key || !($license = License::whose($email)->key($key)->first())) {
            return response()->json([
                'success' => false,
                'error' => null,
            ]);
        }
        if ($license['end_at'] < now()) {
            return response()->json([
                'success' => false,
                'error' => 'Your license key has expired.',
            ]);
        }
        if (!$license['active']) {
            $contact_email = Setting::getSetting('contact_email');
            return response()->json([
                'success' => false,
                'error' => 'Your license key has deactivated. Please contact '.$contact_email.'.',
            ]);
        }
        return response()->json([
            'success' => true,
            'expires_on' => strtotime($license['end_at']) * 1000,
        ]);
    }

    public function recoverKey(Request $request) {
        $email = $request['email'];
        if (!$email || !($license = License::whose($email)->where('end_at', '>', now())->active()->first())) {
            return response()->json([
                'licenseKey' => null,
            ]);
        }
        return response()->json([
            'licenseKey' => $license['key'],
        ]);
    }

    public function adminSync(Request $request) {
        $key = $request['key'];
        $email = $request['email'];
        $users = $request['users'];
        if (!$users || !is_array($users)) $users = [];
        $labels = $request['labels'];
        if (!$labels || !is_array($labels)) $labels = [];
        if (!$key || !$email || !License::whose($email)->key($key)->where('end_at', '>', now())->active()->first()) {
            return response()->json([
                'success' => false,
            ]);
        }
        $valid = true;
        foreach ($users as $user) {
            if (filter_var($user, FILTER_VALIDATE_EMAIL) === false) {
                $valid = false;
                break;
            }
        }
        foreach ($labels as $label) {
            if (gettype($label) != 'string') {
                $valid = false;
                break;
            }
        }
        if (!$valid) {
            return response()->json([
                'success' => false,
            ]);
        }
        SyncLabel::updateOrCreate([
            'email' => $email,
            'members' => json_encode($users),
            'labels' => json_encode($labels),
        ]);
        return response()->json([
            'success' => true,
        ]);
    }

    public function getLabels(Request $request) {
        $email = $request['email'];
        $syncLabels = SyncLabel::member($email)->get();
        $labels = [];
        foreach ($syncLabels as $syncLabel) {
            $tempLabels = json_decode($syncLabel['labels'], true) ?? [];
            if (!is_array($tempLabels)) $tempLabels = [];
            foreach ($tempLabels as $tempLabel) {
                if (gettype($tempLabel) === 'string') {
                    $labels[] = $tempLabel;
                }
            }
        }
        return response()->json([
            'labels' => $labels,
        ]);
    }
}
