<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateObjectivesTable extends Migration
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
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'objectif_type' => [
                'type'       => 'ENUM',
                'constraint' => ['gain', 'loss', 'ideal_imc'],
            ],
            'poids_cible' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'null'       => true,
                'comment'    => 'Target weight in kg',
            ],
            'imc_cible' => [
                'type'       => 'DECIMAL',
                'constraint' => '4,2',
                'null'       => true,
                'comment'    => 'Target BMI',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('objectives');
    }

    public function down()
    {
        $this->forge->dropTable('objectives');
    }
}
