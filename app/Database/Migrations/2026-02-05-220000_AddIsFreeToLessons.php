<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddIsFreeToLessons extends Migration
{
    public function up()
    {
        $this->forge->addColumn('lessons', [
            'is_free' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'after' => 'summary',  // Placing it after summary
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('lessons', 'is_free');
    }
}
