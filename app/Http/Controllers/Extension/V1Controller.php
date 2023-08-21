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
        if (!($email = $request['email'])
            || !($key = $request['key'])
            || !($license = License::whose($email)->key($key)->first())
        ) {
            return response()->json([
                'success' => false,
                'error' => null,
            ]);
        }
        if (($isExpired = $license['end_at'] < now()) || !$license['active']) {
            SyncLabel::where('email', $email)->delete();
            if ($isExpired) {
                $error = 'Your license key has expired.';
            } else {
                $error = 'Your license key has deactivated.';
                if (($contact_email = Setting::getSetting('contact_email'))) {
                    $error .= " Please contact {$contact_email}.";
                }
            }
            return response()->json([
                'success' => false,
                'error' => $error,
            ]);
        }
        return response()->json([
            'success' => true,
            'expires_on' => strtotime($license['end_at']) * 1000,
        ]);
    }

    public function recoverKey(Request $request) {
        if (!($email = $request['email'])
            || !($license = License::whose($email)->where('end_at', '>', now())->active()->first())
        ) {
            return response()->json([
                'licenseKey' => null,
            ]);
        }
        return response()->json([
            'licenseKey' => $license['key'],
        ]);
    }

    public function adminSync(Request $request) {
        if (!($email = $request['email'])
            || !($key = $request['key'])
            || !isset($request['users'])
            || !is_array($users = $request['users'])
            || !($labels = $request['labels'] ?? '{}')
            || !is_string($labels)
            || !($license = License::whose($email)->key($key)->first())
            || $license['end_at'] < now()
            || !$license['active']
        ) {
            if (!empty($license)) SyncLabel::where('email', $email)->delete();
            return response()->json([
                'success' => false,
            ]);
        }
        $error = false;
        foreach ($users as $user) {
            if (filter_var($user, FILTER_VALIDATE_EMAIL) === false) {
                $error = true;
                break;
            }
        }
        if ($error) {
            return response()->json([
                'success' => false,
            ]);
        }
        SyncLabel::updateOrCreate([
            'email' => $email,
        ], [
            'members' => json_encode($users),
            'labels' => $labels,
        ]);
        return response()->json([
            'success' => true,
        ]);
    }

    public function getLabels(Request $request) {
        if (!($email = $request['email'])
            || filter_var($email, FILTER_VALIDATE_EMAIL) === false
        ) {
            return response()->json([]);
        }
        $syncLabels = SyncLabel::member($email)->get();
        $labels = [];
        foreach ($syncLabels as $item) {
            if (!($license = License::whose($item['email'])->first())
                || $license['end_at'] < now()
                || !$license['active']
            ) {
                $item->delete();
                continue;
            }
            $labels[] = $item['labels'];
        }
        return response()->json([
            'labels' => $labels,
        ]);
    }
}
