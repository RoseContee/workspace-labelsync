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
            'origin_price' => null,
            'period' => 1,
            'unit' => 'year',
            'supported_features' => 'Core Features
Support by email
Basic Integration',
            'unsupported_features' => 'Remote Support
Standard Support',
            'description' => null,
            'featured' => false,
            'active' => true,
        ]);

        Membership::create([
            'name' => 'Edu Plan',
            'price' => '39.90',
            'origin_price' => null,
            'period' => 1,
            'unit' => 'year',
            'supported_features' => 'Core Features
Support by email
Basic Integration
Remote Support
Standard Support',
            'unsupported_features' => null,
            'description' => null,
            'featured' => true,
            'active' => true,
        ]);

        Membership::create([
            'name' => 'Business Plan',
            'price' => '59.99',
            'origin_price' => null,
            'period' => 1,
            'unit' => 'year',
            'supported_features' => 'Core Features
Support by phone
Priority Support
Remote Support
On.site support (optional)',
            'unsupported_features' => null,
            'description' => null,
            'featured' => false,
            'active' => true,
        ]);
    }
}
