<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCodesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'code' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'unique'     => true,
            ],
            'montant' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'comment'    => 'Recharge amount in Ariary',
            ],
            'is_used' => [
                'type'    => 'BOOLEAN',
                'default' => false,
            ],
            'used_by' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'used_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('used_by', 'users', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('codes');
    }

    public function down()
    {
        $this->forge->dropTable('codes');
    }
}
