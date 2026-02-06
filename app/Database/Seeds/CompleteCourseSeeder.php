<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CompleteCourseSeeder extends Seeder
{
    public function run()
    {
        // 1. Create Course
        $courseData = [
            'title' => 'Complete Web Development Bootcamp',
            'short_description' => 'From beginner to professional developer',
            'description' => 'Master HTML, CSS, JavaScript, and Backend development in this all-in-one course. Includes quizzes, assignments, and real-world projects.',
            'outcomes' => 'Build real websites|Master Backend Logic|Deploy applications|Database Design',
            'requirements' => 'Mac or PC|Internet Access|No previous experience needed',
            'language' => 'English',
            'category_id' => 1,  // Assuming 1 is Web Development
            'level' => 'intermediate',
            'user_id' => 1,  // Admin user
            'price' => 0,  // Free
            'is_free_course' => 1,
            'is_top_course' => 1,
            'status' => 'active',
            'date_added' => time(),
            'last_modified' => time(),
            'thumbnail' => 'default.jpg'  // Ensure this exists or use null
        ];

        $this->db->table('courses')->insert($courseData);
        $courseId = $this->db->insertID();

        if (!$courseId) {
            echo "Failed to insert course.\n";
            return;
        }

        echo "Course created with ID: $courseId\n";

        // ==========================================
        // SECTION 1: Introduction
        // ==========================================
        $section1Data = [
            'title' => 'Introduction to the Web',
            'course_id' => $courseId,
            'order' => 1,
        ];
        $this->db->table('sections')->insert($section1Data);
        $section1Id = $this->db->insertID();

        // Lesson 1.1: Video
        $lesson1_1 = [
            'title' => 'How the Internet Works',
            'duration' => '00:05:00',
            'course_id' => $courseId,
            'section_id' => $section1Id,
            'lesson_type' => 'video',
            'video_type' => 'youtube',
            'video_url' => 'https://www.youtube.com/watch?v=HzWk6QV-2uY',  // Example URL
            'summary' => 'Understanding how clients and servers communicate.',
            'order' => 1,
            'date_added' => time(),
            'last_modified' => time()
        ];
        $this->db->table('lessons')->insert($lesson1_1);

        // Lesson 1.2: Document (PDF or Image)
        $lesson1_2 = [
            'title' => 'Web Development Roadmap',
            'duration' => null,
            'course_id' => $courseId,
            'section_id' => $section1Id,
            'lesson_type' => 'document',
            'attachment' => 'roadmap.jpg',  // Placeholder
            'attachment_type' => 'jpg',
            'summary' => 'A visual guide to becoming a developer.',
            'order' => 2,
            'date_added' => time(),
            'last_modified' => time()
        ];
        $this->db->table('lessons')->insert($lesson1_2);

        // ==========================================
        // SECTION 2: HTML & CSS (Quiz Section)
        // ==========================================
        $section2Data = [
            'title' => 'HTML & CSS Foundations',
            'course_id' => $courseId,
            'order' => 2,
        ];
        $this->db->table('sections')->insert($section2Data);
        $section2Id = $this->db->insertID();

        // Quiz 2.1
        $quizData = [
            'title' => 'HTML Basics Quiz',
            'course_id' => $courseId,
            'section_id' => $section2Id,
            'summary' => 'Test your knowledge of HTML tags and structure.',
            'duration' => 10,  // Minutes
            'total_marks' => 20,
            'date_added' => time(),
            'last_modified' => time()
        ];
        $this->db->table('quizzes')->insert($quizData);
        $quizId = $this->db->insertID();

        // (Logic notes: Quizzes are added as lessons with type='quiz')

        // Re-writing Quiz Logic based on findings:

        $lessonQuizEntry = [
            'title' => 'HTML Basics Quiz',
            'course_id' => $courseId,
            'section_id' => $section2Id,
            'lesson_type' => 'quiz',
            'summary' => 'Test your knowledge.',
            'duration' => '00:10:00',  // Duration field in lessons table
            'order' => 1,
            'date_added' => time(),
            'last_modified' => time()
        ];
        $this->db->table('lessons')->insert($lessonQuizEntry);
        $quizLessonId = $this->db->insertID();  // This ID is used as 'quiz_id' for questions

        // Questions for this Quiz
        $questions = [
            [
                'quiz_id' => $quizLessonId,
                'title' => 'What does HTML stand for?',
                'type' => 'multiple_choice',
                'number_of_options' => 4,
                'options' => json_encode(['Hyper Text Markup Language', 'Home Tool Markup Language', 'Hyperlinks and Text Markup Language', 'Hyper Tool Multi Language']),
                'correct_answers' => json_encode([1]),  // Index 1-based usually
                // The view renders value="< ?= $oIndex + 1 ? >". So yes, 1-based index.
                'order' => 1
            ],
            [
                'quiz_id' => $quizLessonId,
                'title' => 'Which tag is used for the largest heading?',
                'type' => 'multiple_choice',
                'number_of_options' => 4,
                'options' => json_encode(['<head>', '<h6>', '<h1>', '<header>']),
                'correct_answers' => json_encode([3]),  // <h1> is the 3rd option
                'order' => 2
            ]
        ];
        $this->db->table('questions')->insertBatch($questions);

        // ==========================================
        // SECTION 3: Final Project (Assignment)
        // ==========================================
        $section3Data = [
            'title' => 'Final Project',
            'course_id' => $courseId,
            'order' => 3,
        ];
        $this->db->table('sections')->insert($section3Data);
        $section3Id = $this->db->insertID();

        // Assignment
        // Similar logic: Does it need to be in `assignments` table OR `lessons` table?
        // Section view loops through `$section['assignments']`.
        // `course_player.php` loops through `$section['assignments']` separately from lessons.
        // `CourseSeeder` didn't show this.
        // `Student.php` index/player:
        // $firstSection['assignments']...
        // `SectionModel::getSectionWithLessons` only gets lessons.
        // `CourseModel::getCourseDetails` calls `SectionModel`... does it get assignments?
        // Let's check `CourseModel::getCourseDetails` quickly? No, I can't view it easily now.
        // BUT `Student.php` accesses `$course['sections'][0]['assignments']`.
        // This implies the `getCourseDetails` method fetches assignments separate from lessons.
        // AND `AssignmentModel` exists and is populated.
        // So Assignments ARE in `assignments` table, UNLIKE Quizzes which seem to be treated as Lessons in the player logic (or at least hybrid).
        // WAIT. `course_player.php` has a specific section for `current_type == 'assignment'`.
        // And `Student.php` line 117: `$currentItem = $this->assignmentModel->getAssignmentDetails($id)`.
        // This CONFIRMS assignments are indeed in the `assignments` table.

        $assignmentData = [
            'title' => 'Build a Portfolio Website',
            'course_id' => $courseId,
            'section_id' => $section3Id,
            'description' => 'Create a personal portfolio website using HTML and CSS. Upload the source code as a ZIP file.',
            'deadline' => strtotime('+30 days'),
            'date_added' => time(),
            'last_modified' => time()
        ];
        $this->db->table('assignments')->insert($assignmentData);

        echo "Seeder Completed Successfully.\n";
    }
}
