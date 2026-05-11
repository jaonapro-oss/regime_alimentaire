<?php

namespace App\Models;

use CodeIgniter\Model;

class InformationSanteModel extends Model
{
    protected $table = 'informations_sante';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'utilisateur_id',
        'taille',
        'poids',
        'objectif',
        'poids_cible'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'date_enregistrement';
    protected $updatedField = '';

    // Validation
    protected $validationRules = [
        'utilisateur_id' => 'required|integer',
        'taille' => 'required|decimal|greater_than[0]',
        'poids' => 'required|decimal|greater_than[0]',
        'objectif' => 'required|in_list[augmenter_poids,reduire_poids,imc_ideal]',
    ];

    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    /**
     * Calculer l'IMC
     */
    public function calculerIMC(float $poids, float $taille): float
    {
        $tailleM = $taille / 100;
        return round($poids / ($tailleM * $tailleM), 2);
    }

    /**
     * Obtenir la catégorie IMC
     */
    public function getCategorieIMC(float $imc): string
    {
        if ($imc < 18.5) {
            return 'Insuffisance pondérale';
        } elseif ($imc >= 18.5 && $imc < 25) {
            return 'Poids normal';
        } elseif ($imc >= 25 && $imc < 30) {
            return 'Surpoids';
        } else {
            return 'Obésité';
        }
    }

    /**
     * Calculer le poids idéal
     */
    public function calculerPoidsIdeal(float $taille, string $genre): array
    {
        $tailleM = $taille / 100;
        $imcIdealMin = 18.5;
        $imcIdealMax = 24.9;
        
        return [
            'min' => round($imcIdealMin * ($tailleM * $tailleM), 2),
            'max' => round($imcIdealMax * ($tailleM * $tailleM), 2)
        ];
    }

    /**
     * Obtenir les informations de santé par utilisateur
     */
    public function getParUtilisateur(int $utilisateurId)
    {
        return $this->where('utilisateur_id', $utilisateurId)
            ->orderBy('date_enregistrement', 'DESC')
            ->first();
    }

    /**
     * Mettre à jour ou créer les informations de santé
     */
    public function updateOrCreate(int $utilisateurId, array $data)
    {
        $existing = $this->getParUtilisateur($utilisateurId);
        
        $data['utilisateur_id'] = $utilisateurId;
        
        if ($existing) {
            return $this->update($existing['id'], $data);
        } else {
            return $this->insert($data);
        }
    }
}
