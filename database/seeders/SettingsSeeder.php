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
            'value' => env('APP_NAME', 'LabelSync'),
        ], [
            'key' => 'favicon',
            'value' => null,
        ], [
            'key' => 'logo',
            'value' => null,
        ], [
            'key' => 'contact_email',
            'value' => 'info@labelsync.it',
        ], [
            'key' => 'contact_phone',
            'value' => '+39 338 7825309',
        ], [
            'key' => 'contact_address',
            'value' => 'Largo Conservatorio Vecchio 1
84121 - Salerno
Italy',
        ], [
            'key' => 'map_link',
            'value' => 'https://maps.google.com/maps?width=100%25&amp;height=600&amp;hl=en&amp;q=Largo%20Conservatorio%20Vecchio%201+(My%20Business%20Name)&amp;t=&amp;z=14&amp;ie=UTF8&amp;iwloc=B&amp;output=embed',
        ], [
            'key' => 'facebook_link',
            'value' => 'https://facebook.com',
        ], [
            'key' => 'skype_link',
            'value' => 'https://web.skype.com',
        ], [
            'key' => 'linkedin_link',
            'value' => 'https://linkedin.com',
        ], [
            'key' => 'currency',
            'value' => '€',
        ], [
            'key' => 'dark_mode',
            'value' => false,
        ]], ['key']);
    }
}
