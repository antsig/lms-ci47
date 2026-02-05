<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAssignmentTables extends Migration
{
    public function up()
    {
        // Assignments Table
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
            'description' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'attachment_url' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true
            ],
            'deadline' => [
                'type' => 'BIGINT',  // Timestamp
                'null' => true
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
        $this->forge->createTable('assignments');

        // Assignment Submissions Table
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'assignment_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true
            ],
            'file_url' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            'note' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'grade' => [
                'type' => 'FLOAT',
                'null' => true
            ],
            'feedback' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'graded'],
                'default' => 'pending'
            ],
            'date_submitted' => [
                'type' => 'BIGINT',
            ]
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('assignment_submissions');
    }

    public function down()
    {
        $this->forge->dropTable('assignment_submissions');
        $this->forge->dropTable('assignments');
    }
}
