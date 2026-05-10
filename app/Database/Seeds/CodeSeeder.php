<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CodeSeeder extends Seeder
{
    public function run()
    {
        $codes = [];
        
        // Generate 15 random wallet codes
        for ($i = 0; $i < 15; $i++) {
            $codes[] = [
                'code' => 'CODE-' . strtoupper(substr(md5(rand()), 0, 8)),
                'montant' => [5000, 10000, 20000, 50000][rand(0, 3)],
                'is_used' => false,
                'created_at' => date('Y-m-d H:i:s'),
            ];
        }

        $this->db->table('codes')->insertBatch($codes);
    }
}