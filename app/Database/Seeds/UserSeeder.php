<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        // First, let's check what columns exist in the users table
        $db = \Config\Database::connect();
        $fields = $db->getFieldNames('users');

        // Prepare base data
        $users = [
            // Admin User
            [
                'first_name' => 'Admin',
                'last_name' => 'User',
                'email' => 'admin@lms.com',
                'password' => password_hash('admin123', PASSWORD_DEFAULT),
                'role_id' => 1,
                'is_instructor' => 0,
                'status' => 1,
            ],
            // Instructor 1
            [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john@lms.com',
                'password' => password_hash('instructor123', PASSWORD_DEFAULT),
                'role_id' => 2,
                'is_instructor' => 1,
                'biography' => 'Experienced web developer with 10+ years in the industry.',
                'skills' => 'PHP, JavaScript, Laravel, CodeIgniter, React, Vue.js',
                'status' => 1,
            ],
            // Instructor 2
            [
                'first_name' => 'Jane',
                'last_name' => 'Smith',
                'email' => 'jane@lms.com',
                'password' => password_hash('instructor123', PASSWORD_DEFAULT),
                'role_id' => 2,
                'is_instructor' => 1,
                'biography' => 'Full-stack developer and UI/UX designer.',
                'skills' => 'HTML, CSS, JavaScript, Bootstrap, Tailwind, Figma',
                'status' => 1,
            ],
            // Student User
            [
                'first_name' => 'Student',
                'last_name' => 'Demo',
                'email' => 'student@lms.com',
                'password' => password_hash('student123', PASSWORD_DEFAULT),
                'role_id' => 2,
                'is_instructor' => 0,
                'status' => 1,
            ],
        ];

        // Insert each user individually to handle different table structures
        foreach ($users as $userData) {
            // Only include fields that exist in the table
            $insertData = [];
            foreach ($userData as $key => $value) {
                if (in_array($key, $fields)) {
                    $insertData[$key] = $value;
                }
            }

            // Add timestamps if columns exist
            if (in_array('created_at', $fields)) {
                $insertData['created_at'] = date('Y-m-d H:i:s');
            }
            if (in_array('updated_at', $fields)) {
                $insertData['updated_at'] = date('Y-m-d H:i:s');
            }

            // Check if user already exists
            $existing = $db->table('users')->where('email', $userData['email'])->get()->getRow();

            if (!$existing) {
                $db->table('users')->insert($insertData);
                echo "Created user: {$userData['email']}\n";
            } else {
                echo "User already exists: {$userData['email']}\n";
            }
        }
    }
}
