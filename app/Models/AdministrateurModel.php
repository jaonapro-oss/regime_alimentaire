<?php

namespace App\Models;

use CodeIgniter\Model;

class AdministrateurModel extends Model
{
    protected $table = 'administrateurs';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'nom',
        'email',
        'mot_de_passe',
        'role',
        'actif',
        'derniere_connexion'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'date_creation';
    protected $updatedField = '';

    // Validation
    protected $validationRules = [
        'nom' => 'required|min_length[2]|max_length[100]',
        'email' => 'required|valid_email|is_unique[administrateurs.email,id,{id}]',
        'mot_de_passe' => 'required|min_length[6]',
        'role' => 'required|in_list[super_admin,admin]',
    ];

    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];

    protected function hashPassword(array $data)
    {
        if (isset($data['data']['mot_de_passe'])) {
            $data['data']['mot_de_passe'] = password_hash($data['data']['mot_de_passe'], PASSWORD_DEFAULT);
        }
        return $data;
    }

    /**
     * Vérifier les identifiants de connexion
     */
    public function verifierConnexion(string $email, string $motDePasse)
    {
        $admin = $this->where('email', $email)
            ->where('actif', true)
            ->first();
        
        if ($admin && password_verify($motDePasse, $admin['mot_de_passe'])) {
            // Mettre à jour la dernière connexion
            $this->update($admin['id'], ['derniere_connexion' => date('Y-m-d H:i:s')]);
            return $admin;
        }
        
        return false;
    }
}
