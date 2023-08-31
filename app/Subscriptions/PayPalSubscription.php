<?php

namespace App\Subscriptions;

use App\Models\Membership;
use Exception;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PayPalSubscription implements Subscription
{
    protected PayPalClient $provider;

    public function __construct() {
        $this->provider = new PayPalClient;
        $this->provider->setApiCredentials(config('paypal'));
        $this->provider->getAccessToken();
    }

    public function create(Membership $plan) {
        $site = config('app.name');
        try {
            $subscription = $this->provider->createSubscription([
                'plan_id' => $plan['paypal_plan_id'],
                'quantity' => '1',
                'application_context' => [
                    'brand_name' => $plan['name'],
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

    public function getPlan(string $planId) {
        $plan = $this->provider->showPlanDetails($planId);
        return !empty($plan['id']) ? $plan : null;
    }

    public function getSubscription(string $subscriptionId) {
        $subscription = $this->provider->showSubscriptionDetails($subscriptionId);
        return !empty($subscription['id']) ? $subscription : null;
    }

    public function getPayment(string $paymentId) {
        $payment = $this->provider->showCapturedPaymentDetails($paymentId);
        return !empty($payment['id']) ? $payment : null;
    }

    public function cancel(string $subscriptionId) {
        try {
            $this->provider->cancelSubscription($subscriptionId, 'No longer using.');
        } catch (Exception $e) {
            return false;
        }
        return true;
    }

    public function pause(string $subscriptionId) {
        try {
            $this->provider->suspendSubscription($subscriptionId, 'Subscription Paused');
        } catch (Exception $e) {
            return false;
        }
        return true;
    }

    public function resume(string $subscriptionId) {
        try {
            $this->provider->activateSubscription($subscriptionId, 'Reactivating the subscription');
        } catch (Exception $e) {
            return false;
        }
        return true;
    }
}
