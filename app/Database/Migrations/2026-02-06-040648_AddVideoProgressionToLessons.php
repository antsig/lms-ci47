<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddVideoProgressionToLessons extends Migration
{
    public function up()
    {
        $fields = [
            'video_progression' => [
                'type' => 'INT',
                'constraint' => 5,
                'default' => 0,
                'null' => false,
                'after' => 'summary'
            ]
        ];
        $this->forge->addColumn('lessons', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('lessons', 'video_progression');
    }
}
