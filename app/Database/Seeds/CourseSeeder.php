<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // Course 1 - Free
            [
                'title' => 'Introduction to Web Development',
                'short_description' => 'Learn the basics of HTML, CSS, and JavaScript',
                'description' => 'This comprehensive course will teach you the fundamentals of web development. You will learn HTML for structure, CSS for styling, and JavaScript for interactivity.',
                'outcomes' => 'Build responsive websites|Understand HTML5 and CSS3|Write JavaScript code|Create interactive web pages',
                'requirements' => 'Basic computer skills|Text editor|Web browser',
                'language' => 'English',
                'category_id' => 1,
                'level' => 'beginner',
                'user_id' => '2',
                'price' => 0,
                'is_free_course' => 1,
                'is_top_course' => 1,
                'status' => 'active',
                'date_added' => time(),
                'last_modified' => time(),
            ],
            // Course 2 - Paid
            [
                'title' => 'Advanced PHP & CodeIgniter 4',
                'short_description' => 'Master PHP and CodeIgniter 4 framework',
                'description' => 'Deep dive into PHP programming and learn how to build professional web applications using CodeIgniter 4 framework.',
                'outcomes' => 'Master PHP OOP|Build MVC applications|Use CodeIgniter 4|Database management|RESTful APIs',
                'requirements' => 'Basic PHP knowledge|HTML & CSS|MySQL basics',
                'language' => 'English',
                'category_id' => 1,
                'level' => 'intermediate',
                'user_id' => '2',
                'price' => 299000,
                'is_free_course' => 0,
                'is_top_course' => 1,
                'status' => 'active',
                'date_added' => time(),
                'last_modified' => time(),
            ],
            // Course 3
            [
                'title' => 'React JS Complete Guide',
                'short_description' => 'Build modern web apps with React',
                'description' => 'Learn React from scratch and build amazing single-page applications. Covers hooks, context, routing, and more.',
                'outcomes' => 'Build React apps|Use React Hooks|State management|React Router|API integration',
                'requirements' => 'JavaScript fundamentals|HTML & CSS|Node.js basics',
                'language' => 'English',
                'category_id' => 1,
                'level' => 'intermediate',
                'user_id' => '3',
                'price' => 399000,
                'is_free_course' => 0,
                'is_top_course' => 1,
                'status' => 'active',
                'date_added' => time(),
                'last_modified' => time(),
            ],
            // Course 4
            [
                'title' => 'UI/UX Design Fundamentals',
                'short_description' => 'Learn to design beautiful user interfaces',
                'description' => 'Master the principles of UI/UX design and create stunning user experiences for web and mobile applications.',
                'outcomes' => 'Design principles|User research|Wireframing|Prototyping|Figma mastery',
                'requirements' => 'No prior experience needed|Computer with internet',
                'language' => 'English',
                'category_id' => 4,
                'level' => 'beginner',
                'user_id' => '3',
                'price' => 249000,
                'is_free_course' => 0,
                'is_top_course' => 0,
                'status' => 'active',
                'date_added' => time(),
                'last_modified' => time(),
            ],
            // Course 5 - Free
            [
                'title' => 'Python for Beginners',
                'short_description' => 'Start your programming journey with Python',
                'description' => 'Learn Python programming from scratch. Perfect for absolute beginners who want to start coding.',
                'outcomes' => 'Python basics|Variables and data types|Functions|Loops and conditions|File handling',
                'requirements' => 'No programming experience needed|Computer with Python installed',
                'language' => 'English',
                'category_id' => 3,
                'level' => 'beginner',
                'user_id' => '2',
                'price' => 0,
                'is_free_course' => 1,
                'is_top_course' => 0,
                'status' => 'active',
                'date_added' => time(),
                'last_modified' => time(),
            ],
            // Course 6
            [
                'title' => 'Digital Marketing Masterclass',
                'short_description' => 'Complete digital marketing course',
                'description' => 'Learn all aspects of digital marketing including SEO, social media marketing, email marketing, and paid advertising.',
                'outcomes' => 'SEO optimization|Social media marketing|Email campaigns|Google Ads|Analytics',
                'requirements' => 'Basic internet knowledge|Social media accounts',
                'language' => 'English',
                'category_id' => 6,
                'level' => 'beginner',
                'user_id' => '3',
                'price' => 199000,
                'is_free_course' => 0,
                'is_top_course' => 1,
                'status' => 'active',
                'date_added' => time(),
                'last_modified' => time(),
            ],
        ];

        $this->db->table('courses')->insertBatch($data);
    }
}
