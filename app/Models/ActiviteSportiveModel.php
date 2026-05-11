<?php

namespace App\Models;

use CodeIgniter\Model;

class ActiviteSportiveModel extends Model
{
    protected $table = 'activites_sportives';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'nom',
        'description',
        'calories_brulees_heure',
        'niveau_difficulte',
        'duree_recommandee_minutes',
        'frequence_semaine',
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
        'calories_brulees_heure' => 'required|integer|greater_than[0]',
        'niveau_difficulte' => 'required|in_list[facile,moyen,difficile]',
        'duree_recommandee_minutes' => 'required|integer|greater_than[0]',
        'frequence_semaine' => 'required|integer|greater_than[0]',
    ];

    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    /**
     * Obtenir les activités actives
     */
    public function getActifs()
    {
        return $this->where('actif', true)->findAll();
    }

    /**
     * Obtenir les activités par niveau
     */
    public function getParNiveau(string $niveau)
    {
        return $this->where('actif', true)
            ->where('niveau_difficulte', $niveau)
            ->findAll();
    }
}
