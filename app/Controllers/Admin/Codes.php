<?php

namespace App\Controllers\Admin;

use App\Models\CodePortemonnaieModel;
use App\Controllers\BaseController;

class Codes extends BaseController
{
    protected $codeModel;

    public function __construct()
    {
        $this->codeModel = new CodePortemonnaieModel();
    }

    protected function checkAdminAuth()
    {
        if (!session()->get('admin_id')) {
            return redirect()->to('admin/login');
        }
    }

    /**
     * Lister les codes
     */
    public function index()
    {
        $this->checkAdminAuth();
        
        $codes = $this->codeModel->orderBy('date_creation', 'DESC')->findAll();

        return view('admin/codes/index', [
            'codes' => $codes
        ]);
    }

    /**
     * Afficher le formulaire d'ajout
     */
    public function ajouter()
    {
        $this->checkAdminAuth();
        
        return view('admin/codes/ajouter');
    }

    /**
     * Traiter l'ajout
     */
    public function ajouterPost()
    {
        $this->checkAdminAuth();
        
        if (!$this->validate([
            'code' => 'required|min_length[5]|max_length[50]|is_unique[codes_portemonnaie.code]',
            'montant' => 'required|decimal|greater_than[0]',
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'code' => strtoupper(trim($this->request->getPost('code'))),
            'montant' => $this->request->getPost('montant'),
            'date_expiration' => $this->request->getPost('date_expiration') ?: null,
            'est_utilise' => false,
        ];

        if ($this->codeModel->insert($data)) {
            return redirect()->to('admin/codes')->with('success', 'Code créé');
        } else {
            return redirect()->back()->withInput()->with('error', 'Erreur lors de la création');
        }
    }

    /**
     * Valider un code (le marquer comme utilisé)
     */
    public function valider($id)
    {
        $this->checkAdminAuth();
        
        $code = $this->codeModel->find($id);
        if (!$code) {
            return $this->response->setJSON(['success' => false, 'message' => 'Code non trouvé']);
        }

        if ($code['est_utilise']) {
            return $this->response->setJSON(['success' => false, 'message' => 'Code déjà utilisé']);
        }

        $this->codeModel->update($id, [
            'est_utilise' => true,
            'date_utilisation' => date('Y-m-d H:i:s'),
        ]);

        return $this->response->setJSON(['success' => true, 'message' => 'Code validé']);
    }

    /**
     * Supprimer un code
     */
    public function supprimer($id)
    {
        $this->checkAdminAuth();
        
        if ($this->codeModel->delete($id)) {
            return redirect()->back()->with('success', 'Code supprimé');
        } else {
            return redirect()->back()->with('error', 'Erreur lors de la suppression');
        }
    }
}
