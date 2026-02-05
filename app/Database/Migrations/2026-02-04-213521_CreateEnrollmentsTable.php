<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEnrollmentsTable extends Migration
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
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'course_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'enrollment_date' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'expiry_date' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'progress' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
            ],
            'completed_lessons' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'status' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'default' => 'active',
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
        $this->forge->addKey('user_id');
        $this->forge->addKey('course_id');
        $this->forge->createTable('enrollments');
    }

    public function down()
    {
        $this->forge->dropTable('enrollments');
    }
}
