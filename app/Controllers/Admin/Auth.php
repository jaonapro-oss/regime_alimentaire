<?php

namespace App\Controllers\Admin;

use App\Models\AdministrateurModel;
use App\Controllers\BaseController;

class Auth extends BaseController
{
    protected $adminModel;

    public function __construct()
    {
        $this->adminModel = new AdministrateurModel();
    }

    /**
     * Afficher la page de connexion admin
     */
    public function login()
    {
        if (session()->get('admin_id')) {
            return redirect()->to('admin');
        }

        return view('admin/auth/login');
    }

    /**
     * Traiter la connexion admin
     */
    public function loginPost()
    {
        if (!$this->validate([
            'email' => 'required|valid_email',
            'mot_de_passe' => 'required|min_length[6]',
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $email = $this->request->getPost('email');
        $motDePasse = $this->request->getPost('mot_de_passe');

        $admin = $this->adminModel->where('email', $email)->first();

        if (!$admin || !password_verify($motDePasse, $admin['mot_de_passe'])) {
            return redirect()->back()->with('error', 'Email ou mot de passe incorrect');
        }

        if (!$admin['actif']) {
            return redirect()->back()->with('error', 'Compte désactivé');
        }

        // Mettre à jour la dernière connexion
        $this->adminModel->update($admin['id'], [
            'derniere_connexion' => date('Y-m-d H:i:s'),
        ]);

        // Connecter l'admin
        session()->set([
            'admin_id' => $admin['id'],
            'admin_nom' => $admin['nom'],
            'admin_email' => $admin['email'],
            'admin_role' => $admin['role'],
        ]);

        return redirect()->to('admin')->with('success', 'Connexion réussie !');
    }

    /**
     * Déconnecter l'admin
     */
    public function logout()
    {
        session()->remove(['admin_id', 'admin_nom', 'admin_email', 'admin_role']);
        return redirect()->to('admin/login')->with('success', 'Vous avez été déconnecté');
    }
}
