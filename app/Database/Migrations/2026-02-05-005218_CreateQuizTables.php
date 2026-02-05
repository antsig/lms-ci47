<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateQuizTables extends Migration
{
    public function up()
    {
        // Quizzes Table
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            'course_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true
            ],
            'section_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true
            ],
            'summary' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'duration' => [
                'type' => 'INT',  // Minutes
                'default' => 0
            ],
            'total_marks' => [
                'type' => 'INT',
                'default' => 0
            ],
            'date_added' => [
                'type' => 'BIGINT',
            ],
            'last_modified' => [
                'type' => 'BIGINT',
                'null' => true
            ]
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('quizzes');

        // Questions Table
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'quiz_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true
            ],
            'title' => [
                'type' => 'TEXT'
            ],
            'type' => [
                'type' => 'ENUM',
                'constraint' => ['multiple_choice', 'true_false', 'fill_in_the_blanks'],
                'default' => 'multiple_choice'
            ],
            'number_of_options' => [
                'type' => 'INT',
                'default' => 0
            ],
            'options' => [
                'type' => 'LONGTEXT',  // JSON
                'null' => true
            ],
            'correct_answers' => [
                'type' => 'LONGTEXT',  // JSON
                'null' => true
            ],
            'order' => [
                'type' => 'INT',
                'default' => 0
            ]
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('questions');

        // Quiz Results Table
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'quiz_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true
            ],
            'user_answers' => [
                'type' => 'LONGTEXT',  // JSON
                'null' => true
            ],
            'correct_answers_count' => [
                'type' => 'INT',
                'default' => 0
            ],
            'total_questions' => [
                'type' => 'INT',
                'default' => 0
            ],
            'score' => [
                'type' => 'FLOAT',
                'default' => 0
            ],
            'date_submitted' => [
                'type' => 'BIGINT'
            ]
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('quiz_results');
    }

    public function down()
    {
        $this->forge->dropTable('quiz_results');
        $this->forge->dropTable('questions');
        $this->forge->dropTable('quizzes');
    }
}
