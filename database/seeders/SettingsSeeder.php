<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::upsert([[
            'key' => 'site_name',
            'value' => 'Workspace Labelsync',
        ], [
            'key' => 'favicon',
            'value' => 'favicon.ico',
        ], [
            'key' => 'contact_email',
            'value' => 'pierluigi.pisanti@aforadsudmilano.org',
        ]], ['key']);
    }
}
