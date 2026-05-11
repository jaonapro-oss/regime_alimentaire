<?php

namespace App\Models;

use CodeIgniter\Model;

class CodePortemonnaieModel extends Model
{
    protected $table = 'codes_portemonnaie';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'code',
        'montant',
        'est_utilise',
        'utilisateur_id',
        'date_utilisation',
        'date_expiration'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'date_creation';
    protected $updatedField = '';

    // Validation
    protected $validationRules = [
        'code' => 'required|min_length[5]|max_length[50]|is_unique[codes_portemonnaie.code]',
        'montant' => 'required|decimal|greater_than[0]',
    ];

    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    /**
     * Vérifier et utiliser un code
     */
    public function utiliserCode(string $code, int $utilisateurId)
    {
        $codeData = $this->where('code', $code)
            ->where('est_utilise', false)
            ->first();
        
        if (!$codeData) {
            return ['success' => false, 'message' => 'Code invalide ou déjà utilisé'];
        }
        
        // Vérifier l'expiration
        if ($codeData['date_expiration'] && strtotime($codeData['date_expiration']) < time()) {
            return ['success' => false, 'message' => 'Ce code a expiré'];
        }
        
        // Marquer comme utilisé
        $this->update($codeData['id'], [
            'est_utilise' => true,
            'utilisateur_id' => $utilisateurId,
            'date_utilisation' => date('Y-m-d H:i:s')
        ]);
        
        return [
            'success' => true,
            'montant' => $codeData['montant'],
            'message' => 'Code validé avec succès'
        ];
    }

    /**
     * Générer un code unique
     */
    public function genererCode(int $longueur = 10): string
    {
        $caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        do {
            $code = '';
            for ($i = 0; $i < $longueur; $i++) {
                $code .= $caracteres[rand(0, strlen($caracteres) - 1)];
            }
        } while ($this->where('code', $code)->first());
        
        return $code;
    }

    /**
     * Obtenir les codes non utilisés
     */
    public function getCodesDisponibles()
    {
        return $this->where('est_utilise', false)
            ->where('(date_expiration IS NULL OR date_expiration >', date('Y-m-d H:i:s'))
            ->findAll();
    }
}
