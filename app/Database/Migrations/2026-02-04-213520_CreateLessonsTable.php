<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLessonsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'section_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'course_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'lesson_type' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'video_type' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'video_url' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'duration' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'attachment' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'attachment_type' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'summary' => [
                'type' => 'LONGTEXT',
                'null' => true,
            ],
            'is_preview' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
            ],
            'order' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
            ],
            'date_added' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'last_modified' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('section_id');
        $this->forge->addKey('course_id');
        $this->forge->createTable('lessons');
    }

    public function down()
    {
        $this->forge->dropTable('lessons');
    }
}
