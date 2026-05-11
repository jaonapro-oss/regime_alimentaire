<?php

namespace App\Controllers;

use App\Models\UtilisateurModel;
use App\Models\InformationSanteModel;

class Profil extends BaseController
{
    protected $utilisateurModel;
    protected $santeModel;

    public function __construct()
    {
        $this->utilisateurModel = new UtilisateurModel();
        $this->santeModel = new InformationSanteModel();
    }

    protected function checkAuth()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('/login');
        }
    }

    /**
     * Afficher le profil utilisateur
     */
    public function index()
    {
        $this->checkAuth();
        
        $userId = session()->get('user_id');
        $utilisateur = $this->utilisateurModel->find($userId);
        $sante = $this->santeModel->where('utilisateur_id', $userId)->first();

        if (!$sante) {
            $sante = [
                'taille' => 0,
                'poids' => 0,
                'imc' => 0,
                'objectif' => 'imc_ideal',
                'poids_cible' => 0
            ];
        }

        return view('profil/index', [
            'utilisateur' => $utilisateur,
            'sante' => $sante
        ]);
    }

    /**
     * Afficher le formulaire de modification du profil
     */
    public function modifier()
    {
        $this->checkAuth();
        
        $userId = session()->get('user_id');
        $utilisateur = $this->utilisateurModel->find($userId);

        return view('profil/modifier', [
            'utilisateur' => $utilisateur
        ]);
    }

    /**
     * Traiter la modification du profil
     */
    public function modifierPost()
    {
        $this->checkAuth();
        
        if (!$this->validate([
            'nom' => 'required|min_length[2]|max_length[100]',
            'prenom' => 'required|min_length[2]|max_length[100]',
            'telephone' => 'permit_empty|regex_match[/^[0-9+\-\s\(\)]*$/]',
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userId = session()->get('user_id');
        
        $this->utilisateurModel->update($userId, [
            'nom' => $this->request->getPost('nom'),
            'prenom' => $this->request->getPost('prenom'),
            'telephone' => $this->request->getPost('telephone'),
        ]);

        // Mettre à jour la session
        session()->set([
            'user_nom' => $this->request->getPost('nom'),
            'user_prenom' => $this->request->getPost('prenom'),
        ]);

        return redirect()->to('profil')->with('success', 'Profil mis à jour');
    }

    /**
     * Afficher le formulaire des informations de santé
     */
    public function sante()
    {
        $this->checkAuth();
        
        $userId = session()->get('user_id');
        $sante = $this->santeModel->where('utilisateur_id', $userId)->first();

        if (!$sante) {
            $sante = [
                'taille' => 0,
                'poids' => 0,
                'objectif' => 'imc_ideal',
                'poids_cible' => 0
            ];
        }

        return view('profil/sante', [
            'sante' => $sante
        ]);
    }

    /**
     * Traiter la modification des informations de santé
     */
    public function santePost()
    {
        $this->checkAuth();
        
        if (!$this->validate([
            'taille' => 'required|decimal|greater_than[0]|less_than[300]',
            'poids' => 'required|decimal|greater_than[0]|less_than[300]',
            'objectif' => 'required|in_list[augmenter_poids,reduire_poids,imc_ideal]',
            'poids_cible' => 'permit_empty|decimal|greater_than[0]|less_than[300]',
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userId = session()->get('user_id');
        $sante = $this->santeModel->where('utilisateur_id', $userId)->first();

        $data = [
            'taille' => $this->request->getPost('taille'),
            'poids' => $this->request->getPost('poids'),
            'objectif' => $this->request->getPost('objectif'),
            'poids_cible' => $this->request->getPost('poids_cible') ?: null,
        ];

        if ($sante) {
            $this->santeModel->update($sante['id'], $data);
        } else {
            $data['utilisateur_id'] = $userId;
            $this->santeModel->insert($data);
        }

        return redirect()->to('profil')->with('success', 'Informations de santé mises à jour');
    }
}
