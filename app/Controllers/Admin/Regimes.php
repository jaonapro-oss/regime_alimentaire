<?php

namespace App\Controllers\Admin;

use App\Models\RegimeModel;
use App\Controllers\BaseController;

class Regimes extends BaseController
{
    protected $regimeModel;

    public function __construct()
    {
        $this->regimeModel = new RegimeModel();
    }

    protected function checkAdminAuth()
    {
        if (!session()->get('admin_id')) {
            return redirect()->to('admin/login');
        }
    }

    /**
     * Lister les régimes
     */
    public function index()
    {
        $this->checkAdminAuth();
        
        $regimes = $this->regimeModel->orderBy('date_creation', 'DESC')->findAll();

        return view('admin/regimes/index', [
            'regimes' => $regimes
        ]);
    }

    /**
     * Afficher le formulaire d'ajout
     */
    public function ajouter()
    {
        $this->checkAdminAuth();
        
        return view('admin/regimes/ajouter');
    }

    /**
     * Traiter l'ajout
     */
    public function ajouterPost()
    {
        $this->checkAdminAuth();
        
        if (!$this->validate([
            'nom' => 'required|min_length[3]|max_length[150]',
            'calories_jour' => 'required|integer|greater_than[0]',
            'variation_poids_semaine' => 'required|decimal',
            'prix_base_semaine' => 'required|decimal|greater_than[0]',
            'pourcentage_viande' => 'required|decimal|greater_than_equal_to[0]|less_than_equal_to[100]',
            'pourcentage_poisson' => 'required|decimal|greater_than_equal_to[0]|less_than_equal_to[100]',
            'pourcentage_volaille' => 'required|decimal|greater_than_equal_to[0]|less_than_equal_to[100]',
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'nom' => $this->request->getPost('nom'),
            'description' => $this->request->getPost('description'),
            'calories_jour' => $this->request->getPost('calories_jour'),
            'variation_poids_semaine' => $this->request->getPost('variation_poids_semaine'),
            'prix_base_semaine' => $this->request->getPost('prix_base_semaine'),
            'pourcentage_viande' => $this->request->getPost('pourcentage_viande'),
            'pourcentage_poisson' => $this->request->getPost('pourcentage_poisson'),
            'pourcentage_volaille' => $this->request->getPost('pourcentage_volaille'),
            'pourcentage_legumes' => $this->request->getPost('pourcentage_legumes') ?: 0,
            'pourcentage_fruits' => $this->request->getPost('pourcentage_fruits') ?: 0,
            'pourcentage_cereales' => $this->request->getPost('pourcentage_cereales') ?: 0,
            'actif' => true,
        ];

        if ($this->regimeModel->insert($data)) {
            return redirect()->to('admin/regimes')->with('success', 'Régime créé');
        } else {
            return redirect()->back()->withInput()->with('error', 'Erreur lors de la création');
        }
    }

    /**
     * Afficher le formulaire de modification
     */
    public function modifier($id)
    {
        $this->checkAdminAuth();
        
        $regime = $this->regimeModel->find($id);
        if (!$regime) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Régime non trouvé');
        }

        return view('admin/regimes/modifier', [
            'regime' => $regime
        ]);
    }

    /**
     * Traiter la modification
     */
    public function modifierPost($id)
    {
        $this->checkAdminAuth();
        
        $regime = $this->regimeModel->find($id);
        if (!$regime) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Régime non trouvé');
        }

        if (!$this->validate([
            'nom' => 'required|min_length[3]|max_length[150]',
            'calories_jour' => 'required|integer|greater_than[0]',
            'variation_poids_semaine' => 'required|decimal',
            'prix_base_semaine' => 'required|decimal|greater_than[0]',
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'nom' => $this->request->getPost('nom'),
            'description' => $this->request->getPost('description'),
            'calories_jour' => $this->request->getPost('calories_jour'),
            'variation_poids_semaine' => $this->request->getPost('variation_poids_semaine'),
            'prix_base_semaine' => $this->request->getPost('prix_base_semaine'),
            'pourcentage_viande' => $this->request->getPost('pourcentage_viande') ?: 0,
            'pourcentage_poisson' => $this->request->getPost('pourcentage_poisson') ?: 0,
            'pourcentage_volaille' => $this->request->getPost('pourcentage_volaille') ?: 0,
        ];

        if ($this->regimeModel->update($id, $data)) {
            return redirect()->to('admin/regimes')->with('success', 'Régime modifié');
        } else {
            return redirect()->back()->with('error', 'Erreur lors de la modification');
        }
    }

    /**
     * Supprimer un régime
     */
    public function supprimer($id)
    {
        $this->checkAdminAuth();
        
        if ($this->regimeModel->delete($id)) {
            return redirect()->back()->with('success', 'Régime supprimé');
        } else {
            return redirect()->back()->with('error', 'Erreur lors de la suppression');
        }
    }

    /**
     * Activer/désactiver un régime
     */
    public function toggleActif($id)
    {
        $this->checkAdminAuth();
        
        $regime = $this->regimeModel->find($id);
        if (!$regime) {
            return $this->response->setJSON(['success' => false, 'message' => 'Régime non trouvé']);
        }

        $this->regimeModel->update($id, [
            'actif' => !$regime['actif']
        ]);

        return $this->response->setJSON(['success' => true, 'actif' => !$regime['actif']]);
    }
}
