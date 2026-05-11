<?php

namespace App\Models;

use CodeIgniter\Model;

class ProgrammeUtilisateurModel extends Model
{
    protected $table = 'programmes_utilisateur';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'utilisateur_id',
        'regime_id',
        'activite_sportive_id',
        'duree_semaines',
        'prix_total',
        'prix_paye',
        'remise_appliquee',
        'date_debut',
        'date_fin',
        'statut',
        'poids_initial',
        'poids_final'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'date_creation';
    protected $updatedField = '';

    // Validation
    protected $validationRules = [
        'utilisateur_id' => 'required|integer',
        'regime_id' => 'required|integer',
        'duree_semaines' => 'required|integer|greater_than[0]',
        'prix_total' => 'required|decimal|greater_than[0]',
        'prix_paye' => 'required|decimal|greater_than[0]',
        'date_debut' => 'required|valid_date',
        'date_fin' => 'required|valid_date',
        'poids_initial' => 'required|decimal|greater_than[0]',
    ];

    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    /**
     * Obtenir les programmes d'un utilisateur
     */
    public function getParUtilisateur(int $utilisateurId)
    {
        return $this->select('programmes_utilisateur.*, regimes.nom as regime_nom, activites_sportives.nom as activite_nom')
            ->join('regimes', 'regimes.id = programmes_utilisateur.regime_id')
            ->join('activites_sportives', 'activites_sportives.id = programmes_utilisateur.activite_sportive_id', 'left')
            ->where('programmes_utilisateur.utilisateur_id', $utilisateurId)
            ->orderBy('programmes_utilisateur.date_creation', 'DESC')
            ->findAll();
    }

    /**
     * Obtenir un programme avec tous les détails
     */
    public function getAvecDetails(int $id)
    {
        return $this->select('programmes_utilisateur.*, 
                regimes.nom as regime_nom, 
                regimes.description as regime_description,
                regimes.calories_jour,
                regimes.variation_poids_semaine,
                regimes.pourcentage_viande,
                regimes.pourcentage_poisson,
                regimes.pourcentage_volaille,
                regimes.pourcentage_legumes,
                regimes.pourcentage_fruits,
                regimes.pourcentage_cereales,
                activites_sportives.nom as activite_nom,
                activites_sportives.description as activite_description,
                activites_sportives.calories_brulees_heure,
                activites_sportives.duree_recommandee_minutes,
                activites_sportives.frequence_semaine,
                utilisateurs.nom as utilisateur_nom,
                utilisateurs.prenom as utilisateur_prenom')
            ->join('regimes', 'regimes.id = programmes_utilisateur.regime_id')
            ->join('activites_sportives', 'activites_sportives.id = programmes_utilisateur.activite_sportive_id', 'left')
            ->join('utilisateurs', 'utilisateurs.id = programmes_utilisateur.utilisateur_id')
            ->where('programmes_utilisateur.id', $id)
            ->first();
    }

    /**
     * Obtenir les programmes en cours
     */
    public function getProgrammesEnCours()
    {
        return $this->where('statut', 'en_cours')
            ->where('date_fin >=', date('Y-m-d'))
            ->findAll();
    }

    /**
     * Obtenir les statistiques des programmes
     */
    public function getStatistiques()
    {
        return [
            'total' => $this->countAll(),
            'en_cours' => $this->where('statut', 'en_cours')->countAllResults(),
            'termines' => $this->where('statut', 'termine')->countAllResults(),
            'annules' => $this->where('statut', 'annule')->countAllResults(),
            'revenu_total' => $this->selectSum('prix_paye')->first()['prix_paye'] ?? 0
        ];
    }
}
