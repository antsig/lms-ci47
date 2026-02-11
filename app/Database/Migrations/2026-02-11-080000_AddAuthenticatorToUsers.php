<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAuthenticatorToUsers extends Migration
{
    public function up()
    {
        $fields = [
            'authenticator_secret' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'otp_expires_at'
            ]
        ];
        $this->forge->addColumn('users', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('users', 'authenticator_secret');
    }
}
