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
        $settings = [
            [
                'key' => 'site_name',
                'value' => 'BlogBB',
                'type' => 'string',
            ],
            [
                'key' => 'site_description',
                'value' => 'A modern, modular blog platform built with Laravel',
                'type' => 'string',
            ],
            [
                'key' => 'site_url',
                'value' => config('app.url'),
                'type' => 'string',
            ],
            [
                'key' => 'site_image',
                'value' => 'https://images.unsplash.com/photo-1499750310107-5fef28a66643?w=1200',
                'type' => 'string',
            ],
            [
                'key' => 'site_announcement',
                'value' => 'Welcome to BlogBB! Share your thoughts with the world.',
                'type' => 'string',
            ],
            [
                'key' => 'maintenance',
                'value' => '0',
                'type' => 'boolean',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                ['value' => $setting['value'], 'type' => $setting['type']]
            );
        }
    }
}

