<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ActivitySeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nom' => 'Course à pied',
                'description' => 'Running ou jogging en extérieur',
                'calories_hour' => 500,
                'intensite' => 'high',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nom' => 'Natation',
                'description' => 'Nage en piscine, tous styles',
                'calories_hour' => 400,
                'intensite' => 'medium',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nom' => 'Musculation',
                'description' => 'Entraînement avec poids et haltères',
                'calories_hour' => 350,
                'intensite' => 'high',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nom' => 'Marche rapide',
                'description' => 'Marche à allure soutenue',
                'calories_hour' => 200,
                'intensite' => 'low',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nom' => 'Yoga',
                'description' => 'Pratique du yoga et étirements',
                'calories_hour' => 150,
                'intensite' => 'low',
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('activities')->insertBatch($data);
    }
}