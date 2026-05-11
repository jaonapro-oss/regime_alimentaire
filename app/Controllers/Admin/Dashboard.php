<?php

namespace App\Controllers\Admin;

use App\Models\UtilisateurModel;
use App\Models\RegimeModel;
use App\Models\ActiviteSportiveModel;
use App\Models\CodePortemonnaieModel;
use App\Models\ProgrammeUtilisateurModel;
use App\Controllers\BaseController;

class Dashboard extends BaseController
{
    protected $utilisateurModel;
    protected $regimeModel;
    protected $activiteModel;
    protected $codeModel;
    protected $programmeModel;

    public function __construct()
    {
        $this->utilisateurModel = new UtilisateurModel();
        $this->regimeModel = new RegimeModel();
        $this->activiteModel = new ActiviteSportiveModel();
        $this->codeModel = new CodePortemonnaieModel();
        $this->programmeModel = new ProgrammeUtilisateurModel();
    }

    protected function checkAdminAuth()
    {
        if (!session()->get('admin_id')) {
            return redirect()->to('admin/login');
        }
    }

    /**
     * Afficher le tableau de bord
     */
    public function index()
    {
        $this->checkAdminAuth();
        
        // Statistiques
        $stats = [
            'total_utilisateurs' => $this->utilisateurModel->countAll(),
            'utilisateurs_gold' => $this->utilisateurModel->where('est_gold', true)->countAllResults(),
            'total_regimes' => $this->regimeModel->countAll(),
            'total_activites' => $this->activiteModel->countAll(),
            'codes_actifs' => $this->codeModel->where('est_utilise', false)->countAllResults(),
            'codes_utilises' => $this->codeModel->where('est_utilise', true)->countAllResults(),
            'programmes_en_cours' => $this->programmeModel->where('statut', 'en_cours')->countAllResults(),
            'programmes_termines' => $this->programmeModel->where('statut', 'termine')->countAllResults(),
        ];

        // Derniers utilisateurs
        $utilisateurs = $this->utilisateurModel
            ->orderBy('date_inscription', 'DESC')
            ->limit(10)
            ->findAll();

        // Régimes les plus populaires
        $regimesPopulaires = $this->regimeModel
            ->select('regimes.*, COUNT(programmes_utilisateur.id) as nb_souscriptions')
            ->join('programmes_utilisateur', 'programmes_utilisateur.regime_id = regimes.id', 'left')
            ->groupBy('regimes.id')
            ->orderBy('nb_souscriptions', 'DESC')
            ->limit(5)
            ->findAll();

        return view('admin/dashboard', [
            'stats' => $stats,
            'utilisateurs' => $utilisateurs,
            'regimesPopulaires' => $regimesPopulaires
        ]);
    }
}
