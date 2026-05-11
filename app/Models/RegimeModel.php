<?php

namespace App\Models;

use CodeIgniter\Model;

class RegimeModel extends Model
{
    protected $table = 'regimes';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'nom',
        'description',
        'pourcentage_viande',
        'pourcentage_poisson',
        'pourcentage_volaille',
        'pourcentage_legumes',
        'pourcentage_fruits',
        'pourcentage_cereales',
        'calories_jour',
        'variation_poids_semaine',
        'prix_base_semaine',
        'actif'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'date_creation';
    protected $updatedField = 'date_modification';

    // Validation
    protected $validationRules = [
        'nom' => 'required|min_length[3]|max_length[150]',
        'calories_jour' => 'required|integer|greater_than[0]',
        'variation_poids_semaine' => 'required|decimal',
        'prix_base_semaine' => 'required|decimal|greater_than[0]',
    ];

    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    /**
     * Obtenir les régimes actifs
     */
    public function getActifs()
    {
        return $this->where('actif', true)->findAll();
    }

    /**
     * Obtenir les régimes suggérés selon l'objectif
     */
    public function getSuggestions(string $objectif, float $poidsActuel, float $poidsCible)
    {
        $variationSouhaitee = $poidsCible - $poidsActuel;
        
        $builder = $this->where('actif', true);
        
        if ($objectif === 'augmenter_poids') {
            $builder->where('variation_poids_semaine >', 0);
        } elseif ($objectif === 'reduire_poids') {
            $builder->where('variation_poids_semaine <', 0);
        }
        
        return $builder->orderBy('ABS(variation_poids_semaine)', 'DESC')->findAll();
    }

    /**
     * Calculer la durée nécessaire pour atteindre l'objectif
     */
    public function calculerDuree(int $regimeId, float $poidsActuel, float $poidsCible): int
    {
        $regime = $this->find($regimeId);
        
        if (!$regime || $regime['variation_poids_semaine'] == 0) {
            return 0;
        }
        
        $variationSouhaitee = abs($poidsCible - $poidsActuel);
        $variationParSemaine = abs($regime['variation_poids_semaine']);
        
        $semaines = ceil($variationSouhaitee / $variationParSemaine);
        
        // Limiter entre 4 et 52 semaines
        return max(4, min(52, $semaines));
    }

    /**
     * Calculer le prix total d'un programme
     */
    public function calculerPrix(int $regimeId, int $duree, bool $estGold = false): array
    {
        $regime = $this->find($regimeId);
        
        if (!$regime) {
            return ['prix_total' => 0, 'prix_paye' => 0, 'remise' => 0];
        }
        
        $prixTotal = $regime['prix_base_semaine'] * $duree;
        $remise = 0;
        $prixPaye = $prixTotal;
        
        if ($estGold) {
            $parametreModel = new \App\Models\ParametreSystemeModel();
            $remisePourcentage = $parametreModel->getValeur('remise_gold_pourcentage', 15);
            $remise = $remisePourcentage;
            $prixPaye = $prixTotal * (1 - ($remisePourcentage / 100));
        }
        
        return [
            'prix_total' => $prixTotal,
            'prix_paye' => $prixPaye,
            'remise' => $remise
        ];
    }

    /**
     * Obtenir les statistiques des régimes
     */
    public function getStatistiques()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('v_stats_regimes');
        return $builder->get()->getResultArray();
    }

    /**
     * Toggle l'état actif d'un régime
     */
    public function toggleActif(int $id)
    {
        $regime = $this->find($id);
        if ($regime) {
            return $this->update($id, ['actif' => !$regime['actif']]);
        }
        return false;
    }
}
