<?php

namespace App\Controllers;

use App\Models\UtilisateurModel;
use App\Models\CodePortemonnaieModel;

class Portemonnaie extends BaseController
{
    protected $utilisateurModel;
    protected $codeModel;

    public function __construct()
    {
        $this->utilisateurModel = new UtilisateurModel();
        $this->codeModel = new CodePortemonnaieModel();
    }

    protected function checkAuth()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('/login');
        }
    }

    /**
     * Afficher le porte-monnaie
     */
    public function index()
    {
        $this->checkAuth();
        
        $userId = session()->get('user_id');
        $utilisateur = $this->utilisateurModel->find($userId);
        
        // Obtenir l'historique des codes utilisés
        $codesUtilises = $this->codeModel
            ->where('utilisateur_id', $userId)
            ->where('est_utilise', true)
            ->orderBy('date_utilisation', 'DESC')
            ->findAll();

        return view('portemonnaie/index', [
            'solde' => $utilisateur['solde_portemonnaie'],
            'codesUtilises' => $codesUtilises
        ]);
    }

    /**
     * Ajouter un code au porte-monnaie
     */
    public function ajouterCode()
    {
        $this->checkAuth();
        
        if (!$this->validate([
            'code' => 'required|min_length[5]|max_length[50]'
        ])) {
            return redirect()->back()->with('errors', $this->validator->getErrors());
        }

        $code = trim(strtoupper($this->request->getPost('code')));
        $userId = session()->get('user_id');

        // Chercher le code
        $codeData = $this->codeModel->where('code', $code)->first();

        if (!$codeData) {
            return redirect()->back()->with('error', 'Code invalide ou non trouvé');
        }

        if ($codeData['est_utilise']) {
            return redirect()->back()->with('error', 'Ce code a déjà été utilisé');
        }

        // Vérifier la date d'expiration
        if ($codeData['date_expiration'] && strtotime($codeData['date_expiration']) < time()) {
            return redirect()->back()->with('error', 'Ce code a expiré');
        }

        // Utiliser le code
        $this->codeModel->update($codeData['id'], [
            'est_utilise' => true,
            'utilisateur_id' => $userId,
            'date_utilisation' => date('Y-m-d H:i:s'),
        ]);

        // Ajouter le montant au solde
        $utilisateur = $this->utilisateurModel->find($userId);
        $nouveauSolde = $utilisateur['solde_portemonnaie'] + $codeData['montant'];
        
        $this->utilisateurModel->update($userId, [
            'solde_portemonnaie' => $nouveauSolde,
        ]);

        return redirect()->back()->with('success', 'Code appliqué avec succès ! +' . $codeData['montant'] . '€');
    }
}
