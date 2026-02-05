<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddGiftedByToEnrollments extends Migration
{
    public function up()
    {
        $this->forge->addColumn('enrollments', [
            'gifted_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
                'null' => false,
                'after' => 'course_id',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('enrollments', 'gifted_by');
    }
}
