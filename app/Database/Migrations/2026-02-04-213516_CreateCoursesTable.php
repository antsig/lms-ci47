<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCoursesTable extends Migration
{
    public function up()
    {
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
            'short_description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'description' => [
                'type' => 'LONGTEXT',
                'null' => true,
            ],
            'outcomes' => [
                'type' => 'LONGTEXT',
                'null' => true,
            ],
            'requirements' => [
                'type' => 'LONGTEXT',
                'null' => true,
            ],
            'language' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'default' => 'english',
            ],
            'category_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'sub_category_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'level' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'user_id' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'thumbnail' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'video_url' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'price' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0,
            ],
            'discount_flag' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
            ],
            'discounted_price' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0,
            ],
            'is_top_course' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
            ],
            'is_free_course' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
            ],
            'status' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'default' => 'draft',
            ],
            'date_added' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'last_modified' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('category_id');
        $this->forge->addKey('user_id');
        $this->forge->addKey('status');
        $this->forge->createTable('courses');
    }

    public function down()
    {
        $this->forge->dropTable('courses');
    }
}
