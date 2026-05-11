<?php

namespace App\Controllers\Admin;

use App\Models\UtilisateurModel;
use App\Models\InformationSanteModel;
use App\Models\RegimeModel;
use App\Models\ActiviteSportiveModel;
use App\Models\ProgrammeUtilisateurModel;
use App\Controllers\BaseController;

class Utilisateurs extends BaseController
{
    protected $utilisateurModel;
    protected $santeModel;
    protected $programmeModel;
    protected $regimeModel;
    protected $activiteModel;

    public function __construct()
    {
        $this->utilisateurModel = new UtilisateurModel();
        $this->santeModel = new InformationSanteModel();
        $this->programmeModel = new ProgrammeUtilisateurModel();
        $this->regimeModel = new RegimeModel();
        $this->activiteModel = new ActiviteSportiveModel();
    }

    protected function checkAdminAuth()
    {
        if (!session()->get('admin_id')) {
            return redirect()->to('admin/login');
        }
    }

    /**
     * Lister les utilisateurs
     */
    public function index()
    {
        $this->checkAdminAuth();
        
        $utilisateurs = $this->utilisateurModel
            ->orderBy('date_inscription', 'DESC')
            ->findAll();

        return view('admin/utilisateurs/index', [
            'utilisateurs' => $utilisateurs
        ]);
    }

    /**
     * Voir les détails d'un utilisateur
     */
    public function voir($id)
    {
        $this->checkAdminAuth();
        
        $utilisateur = $this->utilisateurModel->find($id);
        if (!$utilisateur) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Utilisateur non trouvé');
        }

        $sante = $this->santeModel->where('utilisateur_id', $id)->first();
        $programmes = $this->programmeModel->where('utilisateur_id', $id)->findAll();

        foreach ($programmes as &$programme) {
            $programme['regime'] = $this->regimeModel->find($programme['regime_id']);
            $programme['activite'] = $programme['activite_sportive_id'] ?
                $this->activiteModel->find($programme['activite_sportive_id']) : null;
        }

        return view('admin/utilisateurs/voir', [
            'utilisateur' => $utilisateur,
            'sante' => $sante,
            'programmes' => $programmes
        ]);
    }

    /**
     * Activer/désactiver Gold pour un utilisateur
     */
    public function toggleGold($id)
    {
        $this->checkAdminAuth();
        
        $utilisateur = $this->utilisateurModel->find($id);
        if (!$utilisateur) {
            return $this->response->setJSON(['success' => false, 'message' => 'Utilisateur non trouvé']);
        }

        $newStatus = !$utilisateur['est_gold'];
        
        $this->utilisateurModel->update($id, [
            'est_gold' => $newStatus,
            'date_abonnement_gold' => $newStatus ? date('Y-m-d H:i:s') : null,
        ]);

        return $this->response->setJSON(['success' => true, 'est_gold' => $newStatus]);
    }
}
