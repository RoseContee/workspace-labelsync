<?php

namespace App\Subscriptions;

interface Subscription
{
    public function create(int $plan_id);
    public function cancel(string $subscription_id = null);
    public function pause();
    public function resume();
}
