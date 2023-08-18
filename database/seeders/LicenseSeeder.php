<?php

namespace Database\Seeders;

use App\Models\License;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LicenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        License::create([
            'email' => 'pierluigi.pisanti@aforadsudmilano.org',
            'key' => 'CF17-171B-4F4F-809B',
            'end_at' => '2023-12-31 23:59:59',
            'active' => true,
            'membership_id' => null,
            'transaction_id' => null,
        ]);
    }
}
