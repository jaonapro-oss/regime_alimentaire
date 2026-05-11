<?php

namespace App\Controllers;

use App\Models\UtilisateurModel;
use App\Models\InformationSanteModel;
use App\Models\RegimeModel;
use App\Models\ActiviteSportiveModel;
use App\Models\ProgrammeUtilisateurModel;

class Objectifs extends BaseController
{
    protected $utilisateurModel;
    protected $santeModel;
    protected $regimeModel;
    protected $activiteModel;
    protected $programmeModel;

    public function __construct()
    {
        $this->utilisateurModel = new UtilisateurModel();
        $this->santeModel = new InformationSanteModel();
        $this->regimeModel = new RegimeModel();
        $this->activiteModel = new ActiviteSportiveModel();
        $this->programmeModel = new ProgrammeUtilisateurModel();
    }

    protected function checkAuth()
    {
        if (!session()->get('user_id')) {
            return redirect()->to('/login');
        }
    }

    /**
     * Afficher les objectifs disponibles
     */
    public function index()
    {
        $this->checkAuth();
        
        return view('objectifs/index', [
            'objectifs' => [
                'augmenter_poids' => 'Augmenter mon poids',
                'reduire_poids' => 'Réduire mon poids',
                'imc_ideal' => 'Atteindre mon IMC idéal'
            ]
        ]);
    }

    /**
     * Traiter la sélection d'un objectif
     */
    public function choisir()
    {
        $this->checkAuth();
        
        $objectif = $this->request->getPost('objectif');
        
        if (!in_array($objectif, ['augmenter_poids', 'reduire_poids', 'imc_ideal'])) {
            return redirect()->back()->with('error', 'Objectif invalide');
        }

        $userId = session()->get('user_id');
        $sante = $this->santeModel->where('utilisateur_id', $userId)->first();

        if (!$sante) {
            return redirect()->to('profil/sante')->with('error', 'Veuillez compléter vos informations de santé');
        }

        // Stocker l'objectif en session
        session()->set([
            'objectif_choisi' => $objectif,
            'objectif_poids_actuel' => $sante['poids'],
            'objectif_taille' => $sante['taille'],
            'objectif_poids_cible' => $sante['poids_cible'],
        ]);

        return redirect()->to('objectifs/suggestions');
    }

    /**
     * Afficher les suggestions de régimes
     */
    public function suggestions()
    {
        $this->checkAuth();
        
        $objectif = session()->get('objectif_choisi');
        if (!$objectif) {
            return redirect()->to('objectifs');
        }

        $userId = session()->get('user_id');
        $sante = $this->santeModel->where('utilisateur_id', $userId)->first();
        $utilisateur = $this->utilisateurModel->find($userId);
        $activites = $this->activiteModel->getActifs();

        $poidsCible = $sante['poids_cible'];
        if ($objectif === 'imc_ideal' && empty($poidsCible)) {
            $poidsIdeal = $this->santeModel->calculerPoidsIdeal($sante['taille'], $utilisateur['genre']);
            $poidsCible = round(($poidsIdeal['min'] + $poidsIdeal['max']) / 2, 2);
        }

        if (empty($poidsCible)) {
            if ($objectif === 'augmenter_poids') {
                $poidsCible = $sante['poids'] + 5;
            } elseif ($objectif === 'reduire_poids') {
                $poidsCible = max(1, $sante['poids'] - 5);
            }
        }

        $regimes = $this->regimeModel->getSuggestions($objectif, $sante['poids'], $poidsCible);
        $suggestions = [];
        foreach ($regimes as $regime) {
            $duree = $this->regimeModel->calculerDuree($regime['id'], $sante['poids'], $poidsCible);
            $prix = $this->regimeModel->calculerPrix($regime['id'], $duree, $utilisateur['est_gold']);
            $suggestions[] = [
                'regime' => $regime,
                'duree' => $duree,
                'prix_total' => $prix['prix_total'],
                'prix_paye' => $prix['prix_paye'],
                'remise' => $prix['remise'],
            ];
        }

        return view('objectifs/suggestions', [
            'objectif' => $objectif,
            'sante' => $sante,
            'activites' => $activites,
            'suggestions' => $suggestions,
            'poids_cible' => $poidsCible,
            'utilisateur' => $utilisateur,
        ]);
    }

    /**
     * Afficher les détails d'un programme
     */
    public function detailProgramme($regimeId)
    {
        $this->checkAuth();
        
        $regime = $this->regimeModel->find($regimeId);
        if (!$regime) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Régime non trouvé');
        }

        $userId = session()->get('user_id');
        $sante = $this->santeModel->where('utilisateur_id', $userId)->first();
        $utilisateur = $this->utilisateurModel->find($userId);
        $activites = $this->activiteModel->getActifs();

        $poidsCible = $sante['poids_cible'];
        if (empty($poidsCible) && session()->get('objectif_choisi') === 'imc_ideal') {
            $poidsIdeal = $this->santeModel->calculerPoidsIdeal($sante['taille'], $utilisateur['genre']);
            $poidsCible = round(($poidsIdeal['min'] + $poidsIdeal['max']) / 2, 2);
        }

        if (empty($poidsCible)) {
            if (session()->get('objectif_choisi') === 'augmenter_poids') {
                $poidsCible = $sante['poids'] + 5;
            } elseif (session()->get('objectif_choisi') === 'reduire_poids') {
                $poidsCible = max(1, $sante['poids'] - 5);
            }
        }

        $dureeRecommandee = $this->regimeModel->calculerDuree($regimeId, $sante['poids'], $poidsCible);
        $prixEstimate = $this->regimeModel->calculerPrix($regimeId, $dureeRecommandee, $utilisateur['est_gold']);

        return view('objectifs/detail_programme', [
            'regime' => $regime,
            'activites' => $activites,
            'objectif' => session()->get('objectif_choisi'),
            'sante' => $sante,
            'utilisateur' => $utilisateur,
            'poids_cible' => $poidsCible,
            'duree_recommandee' => $dureeRecommandee,
            'prix_estimate' => $prixEstimate,
        ]);
    }

    /**
     * Souscrire à un programme
     */
    public function souscrire()
    {
        $this->checkAuth();
        
        if (!$this->validate([
            'regime_id' => 'required|integer',
            'activite_sportive_id' => 'permit_empty|integer',
            'duree_semaines' => 'required|integer|greater_than[0]|less_than_equal_to[52]',
        ])) {
            return redirect()->back()->with('errors', $this->validator->getErrors());
        }

        $userId = session()->get('user_id');
        $regimeId = $this->request->getPost('regime_id');
        $activiteId = $this->request->getPost('activite_sportive_id') ?: null;
        $dureeWeeks = $this->request->getPost('duree_semaines');

        $regime = $this->regimeModel->find($regimeId);
        if (!$regime) {
            return redirect()->back()->with('error', 'Régime non trouvé');
        }

        $utilisateur = $this->utilisateurModel->find($userId);
        $sante = $this->santeModel->where('utilisateur_id', $userId)->first();
        $objectif = session()->get('objectif_choisi');
        $poidsCible = session()->get('objectif_poids_cible');

        if (empty($poidsCible) && $objectif === 'imc_ideal') {
            $poidsIdeal = $this->santeModel->calculerPoidsIdeal($sante['taille'], $utilisateur['genre']);
            $poidsCible = round(($poidsIdeal['min'] + $poidsIdeal['max']) / 2, 2);
        }

        if (empty($poidsCible)) {
            if ($objectif === 'augmenter_poids') {
                $poidsCible = $sante['poids'] + 5;
            } elseif ($objectif === 'reduire_poids') {
                $poidsCible = max(1, $sante['poids'] - 5);
            }
        }

        $prixTotal = $regime['prix_base_semaine'] * $dureeWeeks;
        $remise = $utilisateur['est_gold'] ? 15 : 0;
        $prixPaye = $prixTotal * (1 - $remise / 100);

        $dateDebut = date('Y-m-d');
        $dateFin = date('Y-m-d', strtotime("+$dureeWeeks weeks", strtotime($dateDebut)));

        $programme = [
            'utilisateur_id' => $userId,
            'regime_id' => $regimeId,
            'activite_sportive_id' => $activiteId,
            'duree_semaines' => $dureeWeeks,
            'prix_total' => $prixTotal,
            'prix_paye' => $prixPaye,
            'remise_appliquee' => $remise,
            'date_debut' => $dateDebut,
            'date_fin' => $dateFin,
            'statut' => 'en_cours',
            'poids_initial' => $sante['poids'],
            'poids_final' => $poidsCible ?: null,
        ];

        if (!$this->programmeModel->insert($programme)) {
            return redirect()->back()->with('error', 'Erreur lors de la création du programme');
        }

        return redirect()->to('objectifs/mes-programmes')->with('success', 'Programme créé avec succès !');
    }

    /**
     * Afficher mes programmes
     */
    public function mesProgrammes()
    {
        $this->checkAuth();
        
        $userId = session()->get('user_id');
        $programmes = $this->programmeModel->where('utilisateur_id', $userId)->findAll();

        // Charger les relations
        foreach ($programmes as &$programme) {
            $programme['regime'] = $this->regimeModel->find($programme['regime_id']);
            if ($programme['activite_sportive_id']) {
                $programme['activite'] = $this->activiteModel->find($programme['activite_sportive_id']);
            }
        }

        return view('objectifs/mes_programmes', [
            'programmes' => $programmes
        ]);
    }

    /**
     * Exporter un programme en PDF
     */
    public function exporterPdf($programmeId)
    {
        $this->checkAuth();
        
        $userId = session()->get('user_id');
        $programme = $this->programmeModel->find($programmeId);

        if (!$programme || $programme['utilisateur_id'] != $userId) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Programme non trouvé');
        }

        $programme['regime'] = $this->regimeModel->find($programme['regime_id']);
        $programme['activite'] = $programme['activite_sportive_id'] ? 
            $this->activiteModel->find($programme['activite_sportive_id']) : null;
        
        $utilisateur = $this->utilisateurModel->find($userId);

        // Note: Dompdf ou autre bibliothèque PDF à intégrer
        return view('objectifs/export_pdf', [
            'programme' => $programme,
            'utilisateur' => $utilisateur
        ]);
    }
}
