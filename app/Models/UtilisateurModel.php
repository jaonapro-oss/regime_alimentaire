<?php

namespace App\Models;

use CodeIgniter\Model;

class UtilisateurModel extends Model
{
    protected $table = 'utilisateurs';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'nom',
        'prenom',
        'email',
        'mot_de_passe',
        'genre',
        'date_naissance',
        'telephone',
        'est_gold',
        'date_abonnement_gold',
        'solde_portemonnaie'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'date_inscription';
    protected $updatedField = 'date_modification';

    // Validation
    protected $validationRules = [
        'nom' => 'required|min_length[2]|max_length[100]',
        'prenom' => 'required|min_length[2]|max_length[100]',
        'email' => 'required|valid_email|is_unique[utilisateurs.email,id,{id}]',
        'mot_de_passe' => 'required|min_length[6]',
        'genre' => 'required|in_list[homme,femme]',
        'date_naissance' => 'required|valid_date',
    ];

    protected $validationMessages = [
        'email' => [
            'is_unique' => 'Cet email est déjà utilisé.'
        ]
    ];

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
        $utilisateur = $this->where('email', $email)->first();
        
        if ($utilisateur && password_verify($motDePasse, $utilisateur['mot_de_passe'])) {
            return $utilisateur;
        }
        
        return false;
    }

    /**
     * Obtenir un utilisateur avec ses informations de santé
     */
    public function getAvecSante(int $id)
    {
        return $this->select('utilisateurs.*, informations_sante.*')
            ->join('informations_sante', 'informations_sante.utilisateur_id = utilisateurs.id', 'left')
            ->where('utilisateurs.id', $id)
            ->first();
    }

    /**
     * Activer l'abonnement Gold
     */
    public function activerGold(int $id)
    {
        return $this->update($id, [
            'est_gold' => true,
            'date_abonnement_gold' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Ajouter du solde au porte-monnaie
     */
    public function ajouterSolde(int $id, float $montant)
    {
        $utilisateur = $this->find($id);
        if ($utilisateur) {
            $nouveauSolde = $utilisateur['solde_portemonnaie'] + $montant;
            return $this->update($id, ['solde_portemonnaie' => $nouveauSolde]);
        }
        return false;
    }

    /**
     * Débiter du solde du porte-monnaie
     */
    public function debiterSolde(int $id, float $montant)
    {
        $utilisateur = $this->find($id);
        if ($utilisateur && $utilisateur['solde_portemonnaie'] >= $montant) {
            $nouveauSolde = $utilisateur['solde_portemonnaie'] - $montant;
            return $this->update($id, ['solde_portemonnaie' => $nouveauSolde]);
        }
        return false;
    }

    /**
     * Obtenir les statistiques des utilisateurs
     */
    public function getStatistiques()
    {
        return [
            'total' => $this->countAll(),
            'gold' => $this->where('est_gold', true)->countAllResults(),
            'hommes' => $this->where('genre', 'homme')->countAllResults(),
            'femmes' => $this->where('genre', 'femme')->countAllResults(),
            'nouveaux_mois' => $this->where('DATE(date_inscription) >=', date('Y-m-01'))->countAllResults()
        ];
    }
}
