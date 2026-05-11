<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRegimesTable extends Migration
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
            'nom' => [
                'type'       => 'VARCHAR',
                'constraint' => '200',
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'duree_jours' => [
                'type'       => 'INT',
                'constraint' => 5,
                'comment'    => 'Duration in days',
            ],
            'prix' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'comment'    => 'Price in Ariary',
            ],
            'variation_poids' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'comment'    => 'Expected weight change in kg (can be negative)',
            ],
            'pct_viande' => [
                'type'       => 'INT',
                'constraint' => 3,
                'comment'    => 'Percentage of meat',
            ],
            'pct_poisson' => [
                'type'       => 'INT',
                'constraint' => 3,
                'comment'    => 'Percentage of fish',
            ],
            'pct_volaille' => [
                'type'       => 'INT',
                'constraint' => 3,
                'comment'    => 'Percentage of poultry',
            ],
            'objectif' => [
                'type'       => 'ENUM',
                'constraint' => ['gain', 'loss', 'maintain'],
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('regimes');
    }

    public function down()
    {
        $this->forge->dropTable('regimes');
    }
}
