<?php

namespace Database\Seeders;

use App\Models\Membership;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MembershipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Membership::create([
            'name' => 'Non-Profit Plan',
            'price' => '29.90',
            'period' => 1,
            'unit' => 'year',
            'supported_features' => 'Core Features
Support by email
Basic Integration',
            'unsupported_features' => 'Remote Support
Standard Support',
            'featured' => false,
            'active' => true,
            'paypal_plan_id' => '',
        ]);

        Membership::create([
            'name' => 'Edu Plan',
            'price' => '39.90',
            'period' => 1,
            'unit' => 'year',
            'supported_features' => 'Core Features
Support by email
Basic Integration
Remote Support
Standard Support',
            'unsupported_features' => null,
            'featured' => true,
            'active' => true,
            'paypal_plan_id' => '',
        ]);

        Membership::create([
            'name' => 'Business Plan',
            'price' => '59.99',
            'period' => 1,
            'unit' => 'year',
            'supported_features' => 'Core Features
Support by phone
Priority Support
Remote Support
On.site support (optional)',
            'unsupported_features' => null,
            'featured' => false,
            'active' => true,
            'paypal_plan_id' => '',
        ]);
    }
}
