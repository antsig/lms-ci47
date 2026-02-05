<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run()
    {
        // System Settings
        $settingsData = [
            ['key' => 'system_name', 'value' => 'LMS'],
            ['key' => 'system_title', 'value' => 'Learning Management System'],
            ['key' => 'system_email', 'value' => 'admin@lms.com'],
            ['key' => 'address', 'value' => 'Jakarta, Indonesia'],
            ['key' => 'phone', 'value' => '+62 812 3456 7890'],
            ['key' => 'currency', 'value' => 'Rp'],
            ['key' => 'language', 'value' => 'english'],
            ['key' => 'system_logo', 'value' => 'logo.png'],
            ['key' => 'favicon', 'value' => 'favicon.png'],
            ['key' => 'admin_revenue_percentage', 'value' => '20'],
        ];

        // Frontend Settings
        $frontendSettingsData = [
            ['key' => 'banner_title', 'value' => 'Learn on your schedule'],
            ['key' => 'banner_subtitle', 'value' => 'Study any topic, anytime. Explore thousands of courses for the lowest price!'],
            ['key' => 'about_us', 'value' => '<p>We are a leading online learning platform...</p>'],
            ['key' => 'terms_and_conditions', 'value' => '<p>Terms and conditions content...</p>'],
            ['key' => 'privacy_policy', 'value' => '<p>Privacy policy content...</p>'],
        ];

        $db = \Config\Database::connect();

        $builder = $db->table('settings');
        // Check if data exists before inserting to avoid duplicates if re-seeding
        foreach ($settingsData as $data) {
            if ($builder->where('key', $data['key'])->countAllResults() == 0) {
                $builder->insert($data);
            }
        }

        $builder = $db->table('frontend_settings');
        foreach ($frontendSettingsData as $data) {
            if ($builder->where('key', $data['key'])->countAllResults() == 0) {
                $builder->insert($data);
            }
        }
    }
}
