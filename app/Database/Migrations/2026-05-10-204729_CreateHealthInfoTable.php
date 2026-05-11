<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateHealthInfoTable extends Migration
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
            'poids' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'comment'    => 'Weight in kg',
            ],
            'taille' => [
                'type'       => 'DECIMAL',
                'constraint' => '3,2',
                'comment'    => 'Height in meters',
            ],
            'age' => [
                'type'       => 'INT',
                'constraint' => 3,
            ],
            'sexe' => [
                'type'       => 'ENUM',
                'constraint' => ['M', 'F', 'Other'],
            ],
            'imc' => [
                'type'       => 'DECIMAL',
                'constraint' => '4,2',
                'null'       => true,
                'comment'    => 'Calculated BMI',
            ],
            'imc_category' => [
                'type'       => 'ENUM',
                'constraint' => ['underweight', 'normal', 'overweight', 'obese'],
                'null'       => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('health_info');
    }

    public function down()
    {
        $this->forge->dropTable('health_info');
    }
}
