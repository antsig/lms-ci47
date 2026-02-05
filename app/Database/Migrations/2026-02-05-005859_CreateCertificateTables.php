<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCertificateTables extends Migration
{
    public function up()
    {
        // Certificates Table (Templates)
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'background_image' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'template_data' => [
                'type' => 'TEXT',  // JSON string
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('certificates');

        // User Certificates Table (Issued)
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'course_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'certificate_id' => [  // The template used
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true
            ],
            'certificate_code' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'unique' => true
            ],
            'issued_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('user_certificates');
    }

    public function down()
    {
        $this->forge->dropTable('user_certificates');
        $this->forge->dropTable('certificates');
    }
}
