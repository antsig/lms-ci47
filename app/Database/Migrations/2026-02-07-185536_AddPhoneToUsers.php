<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPhoneToUsers extends Migration
{
    public function up()
    {
        // Assuming the table is named 'users'
        $this->forge->addColumn('users', [
            'phone' => [
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => true,
                'after' => 'email',  // Adjust placement as needed
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('users', 'phone');
    }
}
