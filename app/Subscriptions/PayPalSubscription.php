<?php

namespace App\Subscriptions;

use App\Models\Membership;
use App\Models\Setting;
use Exception;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PayPalSubscription implements Subscription
{
    protected PayPalClient $provider;

    public function __construct() {
        $this->provider = new PayPalClient;
        $this->provider->setApiCredentials(config('paypal'));
    }

    public function create(int $plan_id) {
        $site = getSiteName(Setting::getSetting('site_name'));
        $plan = Membership::find($plan_id);
        try {
            $this->provider->getAccessToken();
            $subscription = $this->provider->createSubscription([
                'plan_id' => $plan['paypal_subscription_id'],
                'quantity' => '1',
                'application_context' => [
                    'brand_name' => $plan['name'].' for '.$site,
                    'locale' => 'en-US',
                    'shipping_preference' => 'NO_SHIPPING',
                    'user_action' => 'SUBSCRIBE_NOW',
                    'payment_method' => [
                        'payer_selected' => 'PAYPAL',
                        'payee_preferred' => 'IMMEDIATE_PAYMENT_REQUIRED',
                    ],
                    'return_url' => route('subscribe.paypal.success'),
                    'cancel_url' => route('subscribe.cancel'),
                ],
            ]);
            logger($subscription);
            if (!empty($subscription['id'])) {
                foreach ($subscription['links'] as $link) {
                    if ($link['rel'] == 'approve') {
                        return redirect()->away($link['href']);
                    }
                }
                return back()->with('error_message', 'Something went wrong.');
            }
        } catch(\Exception $exception) {
            return back()->with('error_message', $exception->getMessage());
        }
        return back()->with('error_message', $subscription['error']['message'] ?? 'Something went wrong.');
    }

    public function cancel(string $subscription_id = null) {
        if (is_null($subscription_id)) {
            $subscription = SubscriptionTable::where('user_id', auth()->user()->id)->first();
            $reason = 'no longer using';
        } else {
            $subscription = SubscriptionTable::where('subscription_id', $subscription_id)->first();
            $reason = 'new subscription';
        }
        $subscriptionId = $subscription->subscription_id;
        try {
            $response = $this->provider->cancelSubscription($subscriptionId, $reason);
            return true;
        } catch (Exception $e) {
            $error = "Something went wrong." . $e->getMessage();
            return false;
        }
    }

    public function pause() {
        $subscription = SubscriptionTable::where('user_id', auth()->user()->id)->first();
        $subscriptionId = $subscription->subscription_id;
        try {
            $response = $this->provider->suspendSubscription($subscriptionId, 'Subscription Paused');
            return true;
        } catch (Exception $e) {
            $error = "Something went wrong." . $e->getMessage();
            return false;
        }
    }

    public function resume() {
        $subscription = SubscriptionTable::where('user_id', auth()->user()->id)->first();
        $subscriptionId = $subscription->subscription_id;
        try {
            $response = $this->provider->activateSubscription($subscriptionId, 'Reactivating the subscription');
            return true;
        } catch (Exception $e) {
            $error = "Something went wrong." . $e->getMessage();
            return false;
        }
    }

    public function getProvider() {
        return $this->provider;
    }
}
