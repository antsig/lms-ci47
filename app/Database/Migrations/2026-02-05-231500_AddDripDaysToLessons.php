<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDripDaysToLessons extends Migration
{
    public function up()
    {
        $fields = [
            'drip_days' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
                'comment' => 'Days after enrollment to unlock this lesson'
            ],
        ];
        $this->forge->addColumn('lessons', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('lessons', 'drip_days');
    }
}
