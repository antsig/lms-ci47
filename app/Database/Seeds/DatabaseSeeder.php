<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Seed in order due to foreign key dependencies
        $this->call('UserSeeder');
        $this->call('CategorySeeder');
        $this->call('CourseSeeder');
        $this->call('SettingsSeeder');
        $this->call('CompleteCourseSeeder');
    }
}
