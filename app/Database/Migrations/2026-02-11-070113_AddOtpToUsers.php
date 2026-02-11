<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddOtpToUsers extends Migration
{
    public function up()
    {
        $fields = [
            'otp_code' => [
                'type' => 'VARCHAR',
                'constraint' => 6,
                'null' => true,
                'after' => 'last_name'
            ],
            'otp_expires_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'otp_code'
            ]
        ];
        $this->forge->addColumn('users', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('users', ['otp_code', 'otp_expires_at']);
    }
}
