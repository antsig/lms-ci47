<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdatePaymentsTable extends Migration
{
    public function up()
    {
        $fields = [
            'admin_fee' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0,
                'after' => 'amount'
            ],
            'proof_file' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'payment_details'
            ]
        ];

        $this->forge->addColumn('payments', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('payments', ['admin_fee', 'proof_file']);
    }
}
