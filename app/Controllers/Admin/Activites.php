<?php

namespace App\Controllers\Admin;

use App\Models\ActiviteSportiveModel;
use App\Controllers\BaseController;

class Activites extends BaseController
{
    protected $activiteModel;

    public function __construct()
    {
        $this->activiteModel = new ActiviteSportiveModel();
    }

    protected function checkAdminAuth()
    {
        if (!session()->get('admin_id')) {
            return redirect()->to('admin/login');
        }
    }

    /**
     * Lister les activités
     */
    public function index()
    {
        $this->checkAdminAuth();
        
        $activites = $this->activiteModel->orderBy('date_creation', 'DESC')->findAll();

        return view('admin/activites/index', [
            'activites' => $activites
        ]);
    }

    /**
     * Afficher le formulaire d'ajout
     */
    public function ajouter()
    {
        $this->checkAdminAuth();
        
        return view('admin/activites/ajouter');
    }

    /**
     * Traiter l'ajout
     */
    public function ajouterPost()
    {
        $this->checkAdminAuth();
        
        if (!$this->validate([
            'nom' => 'required|min_length[3]|max_length[150]',
            'calories_brulees_heure' => 'required|integer|greater_than[0]',
            'niveau_difficulte' => 'required|in_list[facile,moyen,difficile]',
            'duree_recommandee_minutes' => 'required|integer|greater_than[0]',
            'frequence_semaine' => 'required|integer|greater_than[0]',
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'nom' => $this->request->getPost('nom'),
            'description' => $this->request->getPost('description'),
            'calories_brulees_heure' => $this->request->getPost('calories_brulees_heure'),
            'niveau_difficulte' => $this->request->getPost('niveau_difficulte'),
            'duree_recommandee_minutes' => $this->request->getPost('duree_recommandee_minutes'),
            'frequence_semaine' => $this->request->getPost('frequence_semaine'),
            'actif' => true,
        ];

        if ($this->activiteModel->insert($data)) {
            return redirect()->to('admin/activites')->with('success', 'Activité créée');
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
        
        $activite = $this->activiteModel->find($id);
        if (!$activite) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Activité non trouvée');
        }

        return view('admin/activites/modifier', [
            'activite' => $activite
        ]);
    }

    /**
     * Traiter la modification
     */
    public function modifierPost($id)
    {
        $this->checkAdminAuth();
        
        $activite = $this->activiteModel->find($id);
        if (!$activite) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Activité non trouvée');
        }

        if (!$this->validate([
            'nom' => 'required|min_length[3]|max_length[150]',
            'calories_brulees_heure' => 'required|integer|greater_than[0]',
            'niveau_difficulte' => 'required|in_list[facile,moyen,difficile]',
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'nom' => $this->request->getPost('nom'),
            'description' => $this->request->getPost('description'),
            'calories_brulees_heure' => $this->request->getPost('calories_brulees_heure'),
            'niveau_difficulte' => $this->request->getPost('niveau_difficulte'),
            'duree_recommandee_minutes' => $this->request->getPost('duree_recommandee_minutes') ?: 30,
            'frequence_semaine' => $this->request->getPost('frequence_semaine') ?: 3,
        ];

        if ($this->activiteModel->update($id, $data)) {
            return redirect()->to('admin/activites')->with('success', 'Activité modifiée');
        } else {
            return redirect()->back()->with('error', 'Erreur lors de la modification');
        }
    }

    /**
     * Supprimer une activité
     */
    public function supprimer($id)
    {
        $this->checkAdminAuth();
        
        if ($this->activiteModel->delete($id)) {
            return redirect()->back()->with('success', 'Activité supprimée');
        } else {
            return redirect()->back()->with('error', 'Erreur lors de la suppression');
        }
    }
}
