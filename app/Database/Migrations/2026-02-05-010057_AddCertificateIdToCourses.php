<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCertificateIdToCourses extends Migration
{
    public function up()
    {
        $fields = [
            'certificate_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'after' => 'status'
            ],
        ];
        $this->forge->addColumn('courses', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('courses', 'certificate_id');
    }
}
