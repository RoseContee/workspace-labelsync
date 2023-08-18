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
            'name' => 'Annual Subscription',
            'description' => null,
            'price' => '99.99',
            'origin_price' => '200.00',
            'period' => 1,
            'unit' => 'year',
            'active' => true,
        ]);
    }
}
