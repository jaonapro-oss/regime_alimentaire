<?php

namespace App\Models;

use CodeIgniter\Model;

class ParametreSystemeModel extends Model
{
    protected $table = 'parametres_systeme';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'cle',
        'valeur',
        'description',
        'type_donnee'
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = '';
    protected $updatedField = 'date_modification';

    // Validation
    protected $validationRules = [
        'cle' => 'required|min_length[2]|max_length[100]|is_unique[parametres_systeme.cle,id,{id}]',
        'valeur' => 'required',
        'type_donnee' => 'required|in_list[string,number,boolean,json]',
    ];

    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    /**
     * Obtenir la valeur d'un paramètre
     */
    public function getValeur(string $cle, $defaut = null)
    {
        $parametre = $this->where('cle', $cle)->first();
        
        if (!$parametre) {
            return $defaut;
        }
        
        // Convertir selon le type
        switch ($parametre['type_donnee']) {
            case 'number':
                return is_numeric($parametre['valeur']) ? (float)$parametre['valeur'] : $defaut;
            case 'boolean':
                return filter_var($parametre['valeur'], FILTER_VALIDATE_BOOLEAN);
            case 'json':
                return json_decode($parametre['valeur'], true);
            default:
                return $parametre['valeur'];
        }
    }

    /**
     * Définir la valeur d'un paramètre
     */
    public function setValeur(string $cle, $valeur, string $description = '', string $typeDonnee = 'string')
    {
        $parametre = $this->where('cle', $cle)->first();
        
        // Convertir la valeur en string pour le stockage
        if ($typeDonnee === 'json') {
            $valeur = json_encode($valeur);
        } elseif ($typeDonnee === 'boolean') {
            $valeur = $valeur ? '1' : '0';
        } else {
            $valeur = (string)$valeur;
        }
        
        if ($parametre) {
            return $this->update($parametre['id'], ['valeur' => $valeur]);
        } else {
            return $this->insert([
                'cle' => $cle,
                'valeur' => $valeur,
                'description' => $description,
                'type_donnee' => $typeDonnee
            ]);
        }
    }

    /**
     * Obtenir tous les paramètres groupés
     */
    public function getTousGroupes()
    {
        $parametres = $this->findAll();
        $groupes = [];
        
        foreach ($parametres as $param) {
            $groupes[$param['cle']] = $this->getValeur($param['cle']);
        }
        
        return $groupes;
    }
}
