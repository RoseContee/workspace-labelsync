<?php

namespace App\Http\Controllers\Subscribe;

use App\Helpers\PayPal as PayPalHelper;
use App\Http\Controllers\Controller;
use App\Mail\SubscriptionCreated;
use App\Models\License;
use App\Models\Membership;
use App\Models\Transaction;
use App\Subscriptions\PayPalSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PayPalController extends Controller
{
    protected string $payment_method = 'paypal';

    public function success(Request $request) {
        $paypal = new PayPalSubscription();
        if (($subscription = $paypal->getSubscription($request['subscription_id']))
            && PayPalHelper::isActive($subscription)
            && !(License::where('subscription_id', $subscription['id'])
                ->where('payment_method', $this->payment_method)
                ->first())
        ) {
            $expires_on = date('Y-m-d H:i:s', strtotime(PayPalHelper::getExpiresOn($subscription)));
            $license = License::create([
                'key' => Str::uuid(),
                'expires_on' => $expires_on,
                'active' => true,
                'subscription_id' => $subscription['id'],
                'payment_method' => $this->payment_method,
            ]);
            try {
                $membership = Membership::paypal($subscription['plan_id'])->first();
                $email = $subscription['subscriber']['email_address'] ?? '';
                $name = $subscription['subscriber']['name'] ?? [];
                Mail::to($email)->send(new SubscriptionCreated([
                    'name' => ($name['given_name'] ?? '').' '.($name['surname'] ?? ''),
                    'plan' => $membership['name'] ?? '',
                    'key' => $license['key'],
                ]));
            } catch (\Exception $exception) {
                logger('Paypal subscription success error: '.$exception->getMessage());
            }
            return view('subscription-success', [
                'plan' => $membership['name'] ?? '',
                'key' => $license['key'],
            ]);
        }
        return redirect()->route('home');
    }

    public function webhook(Request $request) {
        logger('webhook');
        logger($request);
        $paypal = new PayPalSubscription();
        switch ($request['event_type']) {
            case 'BILLING.SUBSCRIPTION.CANCELLED':
                if ($subscriptionId = $request['resource']['id'] ?? null) {
                    $subscription = $paypal->getSubscription($subscriptionId);
                }
                break;
            case 'PAYMENT.SALE.COMPLETED':
                if ($subscriptionId = $request['resource']['billing_agreement_id'] ?? null) {
                    $subscription = $paypal->getSubscription($subscriptionId);
                    if ($planId = $subscription['plan_id'] ?? null) {
                        $plan = $paypal->getPlan($planId);
                    }
                }
                if ($paymentId = $request['resource']['id'] ?? null) {
                    $payment = $paypal->getPayment($paymentId);
                }
                break;
        }
        if (!empty($subscription)) {
            if (PayPalHelper::isActive($subscription)) {
                $expires_on = PayPalHelper::getExpiresOn($subscription);
                License::where('subscription_id', $subscription['id'])->update([
                    'expires_on' => date('Y-m-d H:i:s', strtotime($expires_on)),
                ]);
            } else if (PayPalHelper::isCancelled($subscription)) {
                License::where('subscription_id', $subscription['id'])->delete();
            }
        }
        if (!empty($payment) && !empty($subscription) && !empty($plan)) {
            $membership = Membership::paypal($subscription['plan_id'])->first();
            $start_at = $payment['create_time'] ?? 'now';
            Transaction::create([
                'transaction_id' => $payment['id'],
                'subscription_id' => $subscription['id'],
                'membership_id' => $membership['id'] ?? null,
                'payment_method' => $this->payment_method,
                'email' => $payment['payee']['email_address'],
                'amount' => $payment['amount']['value'],
                'currency' => $payment['amount']['currency_code'],
                'period' => PayPalHelper::planPeriod($plan),
                'unit' => PayPalHelper::planUnit($plan),
                'start_at' => date('Y-m-d H:i:s', strtotime($start_at)),
                'end_at' => null,
                'status' => strtolower($payment['status']),
            ]);
        }
    }
}
