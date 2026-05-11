<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'email'    => 'alice@test.com',
                'password' => password_hash('password123', PASSWORD_DEFAULT),
                'nom'      => 'Dupont',
                'prenom'   => 'Alice',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'email'    => 'bob@test.com',
                'password' => password_hash('password123', PASSWORD_DEFAULT),
                'nom'      => 'Martin',
                'prenom'   => 'Bob',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'email'    => 'charlie@test.com',
                'password' => password_hash('password123', PASSWORD_DEFAULT),
                'nom'      => 'Ratsimbazafy',
                'prenom'   => 'Charlie',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'email'    => 'admin@test.com',
                'password' => password_hash('admin123', PASSWORD_DEFAULT),
                'nom'      => 'Admin',
                'prenom'   => 'System',
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('users')->insertBatch($data);

        // Also insert health info for users
        $healthData = [
            ['user_id' => 1, 'poids' => 65.5, 'taille' => 1.68, 'age' => 25, 'sexe' => 'F', 'imc' => 23.21, 'imc_category' => 'normal'],
            ['user_id' => 2, 'poids' => 82.0, 'taille' => 1.75, 'age' => 30, 'sexe' => 'M', 'imc' => 26.78, 'imc_category' => 'overweight'],
            ['user_id' => 3, 'poids' => 58.0, 'taille' => 1.65, 'age' => 22, 'sexe' => 'M', 'imc' => 21.30, 'imc_category' => 'normal'],
        ];

        $this->db->table('health_info')->insertBatch($healthData);

        // Create wallets for all users
        $walletData = [
            ['user_id' => 1, 'solde' => 50000],
            ['user_id' => 2, 'solde' => 25000],
            ['user_id' => 3, 'solde' => 0],
            ['user_id' => 4, 'solde' => 100000],
        ];

        $this->db->table('wallet')->insertBatch($walletData);

        // Create free subscriptions
        $subData = [
            ['user_id' => 1, 'type' => 'free', 'discount_pct' => 0, 'start_date' => date('Y-m-d H:i:s'), 'is_active' => true],
            ['user_id' => 2, 'type' => 'gold', 'discount_pct' => 15, 'start_date' => date('Y-m-d H:i:s'), 'is_active' => true],
            ['user_id' => 3, 'type' => 'free', 'discount_pct' => 0, 'start_date' => date('Y-m-d H:i:s'), 'is_active' => true],
        ];

        $this->db->table('subscriptions')->insertBatch($subData);
    }
}