<?php

namespace App\Controllers;

use App\Models\UtilisateurModel;
use App\Models\ParametreSystemeModel;

class Gold extends BaseController
{
    protected $utilisateurModel;
    protected $parametreModel;

    public function __construct()
    {
        $this->utilisateurModel = new UtilisateurModel();
        $this->parametreModel = new ParametreSystemeModel();
    }

    protected function checkAuth()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('/login');
        }
    }

    /**
     * Afficher la page d'abonnement Gold
     */
    public function index()
    {
        $this->checkAuth();
        
        $userId = session()->get('user_id');
        $utilisateur = $this->utilisateurModel->find($userId);

        // Obtenir le prix Gold depuis les paramètres
        $prixGold = $this->parametreModel->getValeur('prix_gold', 99.99);
        $remiseGold = 15; // Remise fixe en pourcentage

        return view('gold/index', [
            'utilisateur' => $utilisateur,
            'prixGold' => $prixGold,
            'remiseGold' => $remiseGold,
            'estGold' => $utilisateur['est_gold']
        ]);
    }

    /**
     * Souscrire à l'abonnement Gold
     */
    public function souscrire()
    {
        $this->checkAuth();
        
        $userId = session()->get('user_id');
        $utilisateur = $this->utilisateurModel->find($userId);

        // Vérifier s'il n'est pas déjà Gold
        if ($utilisateur['est_gold']) {
            return redirect()->back()->with('error', 'Vous avez déjà un abonnement Gold');
        }

        // Obtenir le prix Gold
        $prixGold = $this->parametreModel->getValeur('prix_gold', 99.99);

        // Vérifier le solde du porte-monnaie
        if ($utilisateur['solde_portemonnaie'] < $prixGold) {
            return redirect()->back()->with('error', 'Solde insuffisant. Il vous manque ' . ($prixGold - $utilisateur['solde_portemonnaie']) . '€');
        }

        // Effectuer le paiement
        $nouveauSolde = $utilisateur['solde_portemonnaie'] - $prixGold;
        
        $this->utilisateurModel->update($userId, [
            'est_gold' => true,
            'date_abonnement_gold' => date('Y-m-d H:i:s'),
            'solde_portemonnaie' => $nouveauSolde,
        ]);

        // Mettre à jour la session
        session()->set([
            'user_est_gold' => true,
        ]);

        return redirect()->to('/')->with('success', 'Bienvenue dans le club Gold ! Vous bénéficiez maintenant d\'une remise de 15% sur tous les régimes.');
    }
}
