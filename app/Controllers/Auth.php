<?php

namespace App\Controllers;

use App\Models\UtilisateurModel;
use App\Models\InformationSanteModel;

class Auth extends BaseController
{
    protected $utilisateurModel;
    protected $santeModel;

    public function __construct()
    {
        $this->utilisateurModel = new UtilisateurModel();
        $this->santeModel = new InformationSanteModel();
    }

    /**
     * Afficher la page d'inscription - Étape 1
     */
    public function inscription()
    {
        if (session()->get('user_id')) {
            return redirect()->to('/');
        }
        
        return view('auth/inscription_etape1');
    }

    /**
     * Traiter l'inscription - Étape 1
     */
    public function inscriptionEtape1()
    {
        if (!$this->validate([
            'nom' => 'required|min_length[2]|max_length[100]',
            'prenom' => 'required|min_length[2]|max_length[100]',
            'email' => 'required|valid_email|is_unique[utilisateurs.email]',
            'mot_de_passe' => 'required|min_length[6]',
            'mot_de_passe_confirm' => 'required|matches[mot_de_passe]',
            'genre' => 'required|in_list[homme,femme]',
            'date_naissance' => 'required|valid_date',
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Stocker les données en session pour étape 2
        session()->set([
            'inscription_nom' => $this->request->getPost('nom'),
            'inscription_prenom' => $this->request->getPost('prenom'),
            'inscription_email' => $this->request->getPost('email'),
            'inscription_mot_de_passe' => $this->request->getPost('mot_de_passe'),
            'inscription_genre' => $this->request->getPost('genre'),
            'inscription_date_naissance' => $this->request->getPost('date_naissance'),
            'inscription_telephone' => $this->request->getPost('telephone'),
        ]);

        return redirect()->to('inscription/etape2');
    }



    

     
}
