<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name' => 'Web Development',
                'slug' => 'web-development',
                'description' => 'Learn to build modern websites and web applications',
                'font_awesome_class' => 'fas fa-code',
                'date_added' => time(),
                'last_modified' => time(),
            ],
            [
                'name' => 'Mobile Development',
                'slug' => 'mobile-development',
                'description' => 'Create mobile apps for iOS and Android',
                'font_awesome_class' => 'fas fa-mobile-alt',
                'date_added' => time(),
                'last_modified' => time(),
            ],
            [
                'name' => 'Data Science',
                'slug' => 'data-science',
                'description' => 'Master data analysis and machine learning',
                'font_awesome_class' => 'fas fa-chart-line',
                'date_added' => time(),
                'last_modified' => time(),
            ],
            [
                'name' => 'Design',
                'slug' => 'design',
                'description' => 'UI/UX design and graphic design courses',
                'font_awesome_class' => 'fas fa-palette',
                'date_added' => time(),
                'last_modified' => time(),
            ],
            [
                'name' => 'Business',
                'slug' => 'business',
                'description' => 'Business skills and entrepreneurship',
                'font_awesome_class' => 'fas fa-briefcase',
                'date_added' => time(),
                'last_modified' => time(),
            ],
            [
                'name' => 'Marketing',
                'slug' => 'marketing',
                'description' => 'Digital marketing and social media',
                'font_awesome_class' => 'fas fa-bullhorn',
                'date_added' => time(),
                'last_modified' => time(),
            ],
            [
                'name' => 'Photography',
                'slug' => 'photography',
                'description' => 'Photography and video production',
                'font_awesome_class' => 'fas fa-camera',
                'date_added' => time(),
                'last_modified' => time(),
            ],
            [
                'name' => 'Music',
                'slug' => 'music',
                'description' => 'Music theory and instrument lessons',
                'font_awesome_class' => 'fas fa-music',
                'date_added' => time(),
                'last_modified' => time(),
            ],
        ];

        $this->db->table('categories')->insertBatch($data);
    }
}
