<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RegimeSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nom' => 'Regime Prise de Masse',
                'description' => 'Programme intensif pour gagner du muscle et du poids sainement',
                'duree_jours' => 90,
                'prix' => 45000,
                'variation_poids' => 5.0,
                'pct_viande' => 40,
                'pct_poisson' => 30,
                'pct_volaille' => 30,
                'objectif' => 'gain',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nom' => 'Regime Minceur Express',
                'description' => 'Perte de poids rapide et efficace',
                'duree_jours' => 60,
                'prix' => 35000,
                'variation_poids' => -8.0,
                'pct_viande' => 20,
                'pct_poisson' => 40,
                'pct_volaille' => 40,
                'objectif' => 'loss',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nom' => 'Regime Equilibre',
                'description' => 'Maintien du poids idéal',
                'duree_jours' => 30,
                'prix' => 25000,
                'variation_poids' => 0,
                'pct_viande' => 33,
                'pct_poisson' => 33,
                'pct_volaille' => 34,
                'objectif' => 'maintain',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nom' => 'Regime Sportif Gain',
                'description' => 'Prise de poids avec activité sportive intensive',
                'duree_jours' => 120,
                'prix' => 55000,
                'variation_poids' => 7.0,
                'pct_viande' => 50,
                'pct_poisson' => 25,
                'pct_volaille' => 25,
                'objectif' => 'gain',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nom' => 'Regime Detox Minceur',
                'description' => 'Perte de poids douce et détoxification',
                'duree_jours' => 45,
                'prix' => 30000,
                'variation_poids' => -5.0,
                'pct_viande' => 15,
                'pct_poisson' => 50,
                'pct_volaille' => 35,
                'objectif' => 'loss',
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('regimes')->insertBatch($data);
    }
}