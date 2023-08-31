<?php

namespace App\Subscriptions;

use App\Models\Membership;

interface Subscription
{
    public function create(Membership $plan);
    public function cancel(string $subscriptionId);
    public function pause(string $subscriptionId);
    public function resume(string $subscriptionId);
}
