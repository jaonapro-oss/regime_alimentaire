<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class InitialDataSeeder extends Seeder
{
    public function run()
    {
        // ============================================
        // Administrateur
        // ============================================
        $this->db->table('administrateurs')->insert([
            'nom' => 'Admin',
            'email' => 'admin@nutrition-pro.fr',
            'mot_de_passe' => password_hash('admin123', PASSWORD_DEFAULT),
            'role' => 'super_admin',
            'actif' => true,
        ]);

        // ============================================
        // Utilisateurs
        // ============================================
        $utilisateurs = [
            [
                'nom' => 'Dupont',
                'prenom' => 'Jean',
                'email' => 'jean.dupont@email.com',
                'mot_de_passe' => password_hash('password123', PASSWORD_DEFAULT),
                'genre' => 'homme',
                'date_naissance' => '1990-05-15',
                'telephone' => '0612345678',
                'est_gold' => false,
                'solde_portemonnaie' => 50.00,
            ],
            [
                'nom' => 'Martin',
                'prenom' => 'Marie',
                'email' => 'marie.martin@email.com',
                'mot_de_passe' => password_hash('password123', PASSWORD_DEFAULT),
                'genre' => 'femme',
                'date_naissance' => '1995-08-22',
                'telephone' => '0687654321',
                'est_gold' => true,
                'date_abonnement_gold' => date('Y-m-d H:i:s'),
                'solde_portemonnaie' => 25.00,
            ],
            [
                'nom' => 'Bernard',
                'prenom' => 'Pierre',
                'email' => 'pierre.bernard@email.com',
                'mot_de_passe' => password_hash('password123', PASSWORD_DEFAULT),
                'genre' => 'homme',
                'date_naissance' => '1988-03-10',
                'telephone' => '0623456789',
                'est_gold' => false,
                'solde_portemonnaie' => 100.00,
            ],
            [
                'nom' => 'Durand',
                'prenom' => 'Sophie',
                'email' => 'sophie.durand@email.com',
                'mot_de_passe' => password_hash('password123', PASSWORD_DEFAULT),
                'genre' => 'femme',
                'date_naissance' => '1992-11-07',
                'telephone' => '0634567890',
                'est_gold' => true,
                'date_abonnement_gold' => date('Y-m-d H:i:s'),
                'solde_portemonnaie' => 75.00,
            ],
            [
                'nom' => 'Lefevre',
                'prenom' => 'Nicolas',
                'email' => 'nicolas.lefevre@email.com',
                'mot_de_passe' => password_hash('password123', PASSWORD_DEFAULT),
                'genre' => 'homme',
                'date_naissance' => '1994-07-19',
                'telephone' => '0645678901',
                'est_gold' => false,
                'solde_portemonnaie' => 150.00,
            ],
        ];

        $this->db->table('utilisateurs')->insertBatch($utilisateurs);

        // ============================================
        // Informations de santé pour les utilisateurs
        // ============================================
        $utilisateurIds = range(1, 5);
        $infos_sante = [
            ['utilisateur_id' => 1, 'taille' => 180, 'poids' => 85, 'objectif' => 'reduire_poids', 'poids_cible' => 75],
            ['utilisateur_id' => 2, 'taille' => 165, 'poids' => 62, 'objectif' => 'imc_ideal', 'poids_cible' => null],
            ['utilisateur_id' => 3, 'taille' => 175, 'poids' => 70, 'objectif' => 'augmenter_poids', 'poids_cible' => 80],
            ['utilisateur_id' => 4, 'taille' => 168, 'poids' => 58, 'objectif' => 'imc_ideal', 'poids_cible' => null],
            ['utilisateur_id' => 5, 'taille' => 182, 'poids' => 92, 'objectif' => 'reduire_poids', 'poids_cible' => 80],
        ];

        $this->db->table('informations_sante')->insertBatch($infos_sante);

        // ============================================
        // Régimes
        // ============================================
        $regimes = [
            [
                'nom' => 'Régime Protéiné',
                'description' => 'Riche en protéines pour une prise de masse musculaire',
                'pourcentage_viande' => 35,
                'pourcentage_poisson' => 25,
                'pourcentage_volaille' => 25,
                'pourcentage_legumes' => 10,
                'pourcentage_fruits' => 5,
                'pourcentage_cereales' => 0,
                'calories_jour' => 2500,
                'variation_poids_semaine' => 0.5,
                'prix_base_semaine' => 25.00,
                'actif' => true,
            ],
            [
                'nom' => 'Régime Minceur',
                'description' => 'Faible en calories pour une perte de poids progressive',
                'pourcentage_viande' => 20,
                'pourcentage_poisson' => 20,
                'pourcentage_volaille' => 20,
                'pourcentage_legumes' => 30,
                'pourcentage_fruits' => 10,
                'pourcentage_cereales' => 0,
                'calories_jour' => 1500,
                'variation_poids_semaine' => -0.7,
                'prix_base_semaine' => 20.00,
                'actif' => true,
            ],
            [
                'nom' => 'Régime Équilibré',
                'description' => 'Régime équilibré pour maintenir un poids santé',
                'pourcentage_viande' => 25,
                'pourcentage_poisson' => 20,
                'pourcentage_volaille' => 20,
                'pourcentage_legumes' => 20,
                'pourcentage_fruits' => 10,
                'pourcentage_cereales' => 5,
                'calories_jour' => 2000,
                'variation_poids_semaine' => 0,
                'prix_base_semaine' => 22.00,
                'actif' => true,
            ],
            [
                'nom' => 'Régime Gain Musculaire',
                'description' => 'Spécialisé pour la prise de masse avec activité physique',
                'pourcentage_viande' => 40,
                'pourcentage_poisson' => 20,
                'pourcentage_volaille' => 20,
                'pourcentage_legumes' => 12,
                'pourcentage_fruits' => 8,
                'pourcentage_cereales' => 0,
                'calories_jour' => 3000,
                'variation_poids_semaine' => 0.8,
                'prix_base_semaine' => 30.00,
                'actif' => true,
            ],
            [
                'nom' => 'Régime Détox',
                'description' => 'Régime léger à base de fruits et légumes',
                'pourcentage_viande' => 10,
                'pourcentage_poisson' => 10,
                'pourcentage_volaille' => 10,
                'pourcentage_legumes' => 40,
                'pourcentage_fruits' => 30,
                'pourcentage_cereales' => 0,
                'calories_jour' => 1200,
                'variation_poids_semaine' => -0.9,
                'prix_base_semaine' => 18.00,
                'actif' => true,
            ],
        ];

        $this->db->table('regimes')->insertBatch($regimes);

        // ============================================
        // Activités Sportives
        // ============================================
        $activites = [
            [
                'nom' => 'Course à Pied',
                'description' => 'Courir régulièrement pour améliorer l\'endurance',
                'calories_brulees_heure' => 600,
                'niveau_difficulte' => 'moyen',
                'duree_recommandee_minutes' => 45,
                'frequence_semaine' => 3,
                'actif' => true,
            ],
            [
                'nom' => 'Musculation',
                'description' => 'Renforcement musculaire avec poids et haltères',
                'calories_brulees_heure' => 400,
                'niveau_difficulte' => 'moyen',
                'duree_recommandee_minutes' => 60,
                'frequence_semaine' => 4,
                'actif' => true,
            ],
            [
                'nom' => 'Yoga',
                'description' => 'Séance de yoga pour la flexibilité et le bien-être',
                'calories_brulees_heure' => 200,
                'niveau_difficulte' => 'facile',
                'duree_recommandee_minutes' => 60,
                'frequence_semaine' => 2,
                'actif' => true,
            ],
            [
                'nom' => 'Natation',
                'description' => 'Nager pour un exercice complet sans impact',
                'calories_brulees_heure' => 500,
                'niveau_difficulte' => 'moyen',
                'duree_recommandee_minutes' => 45,
                'frequence_semaine' => 3,
                'actif' => true,
            ],
            [
                'nom' => 'Cyclisme',
                'description' => 'Faire du vélo pour renforcer les jambes',
                'calories_brulees_heure' => 550,
                'niveau_difficulte' => 'moyen',
                'duree_recommandee_minutes' => 60,
                'frequence_semaine' => 2,
                'actif' => true,
            ],
        ];

        $this->db->table('activites_sportives')->insertBatch($activites);

        // ============================================
        // Codes Porte-Monnaie
        // ============================================
        $codes = [
            ['code' => 'CODE001', 'montant' => 10.00, 'est_utilise' => false],
            ['code' => 'CODE002', 'montant' => 20.00, 'est_utilise' => false],
            ['code' => 'CODE003', 'montant' => 15.00, 'est_utilise' => true, 'utilisateur_id' => 1, 'date_utilisation' => date('Y-m-d H:i:s')],
            ['code' => 'CODE004', 'montant' => 25.00, 'est_utilise' => false],
            ['code' => 'CODE005', 'montant' => 30.00, 'est_utilise' => true, 'utilisateur_id' => 2, 'date_utilisation' => date('Y-m-d H:i:s')],
            ['code' => 'CODE006', 'montant' => 50.00, 'est_utilise' => false],
            ['code' => 'CODE007', 'montant' => 15.00, 'est_utilise' => false],
            ['code' => 'CODE008', 'montant' => 20.00, 'est_utilise' => false],
            ['code' => 'CODE009', 'montant' => 10.00, 'est_utilise' => false],
            ['code' => 'CODE010', 'montant' => 35.00, 'est_utilise' => false],
            ['code' => 'CODE011', 'montant' => 25.00, 'est_utilise' => false],
            ['code' => 'CODE012', 'montant' => 40.00, 'est_utilise' => false],
            ['code' => 'CODE013', 'montant' => 15.00, 'est_utilise' => false],
            ['code' => 'CODE014', 'montant' => 20.00, 'est_utilise' => false],
            ['code' => 'CODE015', 'montant' => 50.00, 'est_utilise' => false],
        ];

        $this->db->table('codes_portemonnaie')->insertBatch($codes);

        // ============================================
        // Paramètres système
        // ============================================
        $parametres = [
            ['cle' => 'prix_gold', 'valeur' => '99.99', 'type_donnee' => 'number', 'description' => 'Prix de l\'abonnement Gold'],
            ['cle' => 'remise_gold', 'valeur' => '15', 'type_donnee' => 'number', 'description' => 'Pourcentage de remise Gold'],
            ['cle' => 'app_name', 'valeur' => 'Nutrition Pro', 'type_donnee' => 'string', 'description' => 'Nom de l\'application'],
        ];

        $this->db->table('parametres_systeme')->insertBatch($parametres);

        echo "Données de test créées avec succès !";
    }
}
