<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddServiceFeeToPayments extends Migration
{
    public function up()
    {
        $this->forge->addColumn('payments', [
            'service_fee' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0,
                'after' => 'amount'
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('payments', 'service_fee');
    }
}
