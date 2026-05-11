-- ============================================
-- Base de données : alimentation
-- Projet : Système de régime alimentaire
-- ============================================

DROP DATABASE IF EXISTS alimentation;
CREATE DATABASE alimentation CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE alimentation;

-- ============================================
-- Table : utilisateurs
-- ============================================
CREATE TABLE utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL,
    genre ENUM('homme', 'femme') NOT NULL,
    date_naissance DATE NOT NULL,
    telephone VARCHAR(20),
    est_gold BOOLEAN DEFAULT FALSE,
    date_abonnement_gold DATETIME NULL,
    solde_portemonnaie DECIMAL(10,2) DEFAULT 0.00,
    date_inscription DATETIME DEFAULT CURRENT_TIMESTAMP,
    date_modification DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email)
) ENGINE=InnoDB;

-- ============================================
-- Table : informations_sante
-- ============================================
CREATE TABLE informations_sante (
    id INT AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id INT NOT NULL,
    taille DECIMAL(5,2) NOT NULL COMMENT 'Taille en cm',
    poids DECIMAL(5,2) NOT NULL COMMENT 'Poids en kg',
    imc DECIMAL(5,2) GENERATED ALWAYS AS (poids / ((taille/100) * (taille/100))) STORED,
    objectif ENUM('augmenter_poids', 'reduire_poids', 'imc_ideal') NOT NULL,
    poids_cible DECIMAL(5,2) NULL,
    date_enregistrement DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE,
    INDEX idx_utilisateur (utilisateur_id)
) ENGINE=InnoDB;

-- ============================================
-- Table : regimes
-- ============================================
CREATE TABLE regimes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(150) NOT NULL,
    description TEXT,
    pourcentage_viande DECIMAL(5,2) DEFAULT 0.00,
    pourcentage_poisson DECIMAL(5,2) DEFAULT 0.00,
    pourcentage_volaille DECIMAL(5,2) DEFAULT 0.00,
    pourcentage_legumes DECIMAL(5,2) DEFAULT 0.00,
    pourcentage_fruits DECIMAL(5,2) DEFAULT 0.00,
    pourcentage_cereales DECIMAL(5,2) DEFAULT 0.00,
    calories_jour INT NOT NULL COMMENT 'Calories par jour',
    variation_poids_semaine DECIMAL(5,2) NOT NULL COMMENT 'Variation de poids par semaine en kg (+ ou -)',
    prix_base_semaine DECIMAL(10,2) NOT NULL COMMENT 'Prix de base par semaine',
    actif BOOLEAN DEFAULT TRUE,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    date_modification DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_actif (actif)
) ENGINE=InnoDB;

-- ============================================
-- Table : activites_sportives
-- ============================================
CREATE TABLE activites_sportives (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(150) NOT NULL,
    description TEXT,
    calories_brulees_heure INT NOT NULL COMMENT 'Calories brûlées par heure',
    niveau_difficulte ENUM('facile', 'moyen', 'difficile') NOT NULL,
    duree_recommandee_minutes INT NOT NULL,
    frequence_semaine INT NOT NULL COMMENT 'Nombre de fois par semaine',
    actif BOOLEAN DEFAULT TRUE,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    date_modification DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_actif (actif)
) ENGINE=InnoDB;

-- ============================================
-- Table : codes_portemonnaie
-- ============================================
CREATE TABLE codes_portemonnaie (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) UNIQUE NOT NULL,
    montant DECIMAL(10,2) NOT NULL,
    est_utilise BOOLEAN DEFAULT FALSE,
    utilisateur_id INT NULL,
    date_utilisation DATETIME NULL,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    date_expiration DATETIME NULL,
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE SET NULL,
    INDEX idx_code (code),
    INDEX idx_utilise (est_utilise)
) ENGINE=InnoDB;

-- ============================================
-- Table : programmes_utilisateur
-- ============================================
CREATE TABLE programmes_utilisateur (
    id INT AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id INT NOT NULL,
    regime_id INT NOT NULL,
    activite_sportive_id INT NULL,
    duree_semaines INT NOT NULL,
    prix_total DECIMAL(10,2) NOT NULL,
    prix_paye DECIMAL(10,2) NOT NULL,
    remise_appliquee DECIMAL(5,2) DEFAULT 0.00 COMMENT 'Remise en pourcentage',
    date_debut DATE NOT NULL,
    date_fin DATE NOT NULL,
    statut ENUM('en_cours', 'termine', 'annule') DEFAULT 'en_cours',
    poids_initial DECIMAL(5,2) NOT NULL,
    poids_final DECIMAL(5,2) NULL,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE,
    FOREIGN KEY (regime_id) REFERENCES regimes(id) ON DELETE RESTRICT,
    FOREIGN KEY (activite_sportive_id) REFERENCES activites_sportives(id) ON DELETE SET NULL,
    INDEX idx_utilisateur (utilisateur_id),
    INDEX idx_statut (statut)
) ENGINE=InnoDB;

-- ============================================
-- Table : administrateurs
-- ============================================
CREATE TABLE administrateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL,
    role ENUM('super_admin', 'admin') DEFAULT 'admin',
    actif BOOLEAN DEFAULT TRUE,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    derniere_connexion DATETIME NULL,
    INDEX idx_email (email)
) ENGINE=InnoDB;

-- ============================================
-- Table : parametres_systeme
-- ============================================
CREATE TABLE parametres_systeme (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cle VARCHAR(100) UNIQUE NOT NULL,
    valeur TEXT NOT NULL,
    description TEXT,
    type_donnee ENUM('string', 'number', 'boolean', 'json') DEFAULT 'string',
    date_modification DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================
-- INSERTION DES DONNÉES DE TEST
-- ============================================

-- Administrateurs (mot de passe: 123456)
INSERT INTO administrateurs (nom, email, mot_de_passe, role) VALUES
('Admin Principal', 'admin@alimentation.com', '$2y$10$ylubLiZeSYMNqedazuhb2.hZtNs/6VaVMWNF2ld4bg/tzPoyCTSp.', 'super_admin'),
('Admin Secondaire', 'admin2@alimentation.com', '$2y$10$ylubLiZeSYMNqedazuhb2.hZtNs/6VaVMWNF2ld4bg/tzPoyCTSp.', 'admin');

-- Utilisateurs (mot de passe: 123456)
INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, genre, date_naissance, telephone, est_gold, solde_portemonnaie) VALUES
('Rakoto', 'Jean', 'jean.rakoto@email.com', '$2y$10$ylubLiZeSYMNqedazuhb2.hZtNs/6VaVMWNF2ld4bg/tzPoyCTSp.', 'homme', '1990-05-15', '0341234567', TRUE, 50000.00),
('Rasoa', 'Marie', 'marie.rasoa@email.com', '$2y$10$ylubLiZeSYMNqedazuhb2.hZtNs/6VaVMWNF2ld4bg/tzPoyCTSp.', 'femme', '1995-08-22', '0347654321', FALSE, 25000.00),
('Randria', 'Paul', 'paul.randria@email.com', '$2y$10$ylubLiZeSYMNqedazuhb2.hZtNs/6VaVMWNF2ld4bg/tzPoyCTSp.', 'homme', '1988-03-10', '0349876543', TRUE, 75000.00),
('Rasoamalala', 'Sophie', 'sophie.rasoamalala@email.com', '$2y$10$ylubLiZeSYMNqedazuhb2.hZtNs/6VaVMWNF2ld4bg/tzPoyCTSp.', 'femme', '1992-11-30', '0342345678', FALSE, 10000.00),
('Andrianina', 'Luc', 'luc.andrianina@email.com', '$2y$10$ylubLiZeSYMNqedazuhb2.hZtNs/6VaVMWNF2ld4bg/tzPoyCTSp.', 'homme', '1985-07-18', '0348765432', FALSE, 30000.00);

-- Informations de santé
INSERT INTO informations_sante (utilisateur_id, taille, poids, objectif, poids_cible) VALUES
(1, 175.00, 85.00, 'reduire_poids', 75.00),
(2, 165.00, 55.00, 'augmenter_poids', 60.00),
(3, 180.00, 90.00, 'imc_ideal', 80.00),
(4, 160.00, 70.00, 'reduire_poids', 58.00),
(5, 170.00, 65.00, 'imc_ideal', 68.00);

-- Régimes alimentaires
INSERT INTO regimes (nom, description, pourcentage_viande, pourcentage_poisson, pourcentage_volaille, pourcentage_legumes, pourcentage_fruits, pourcentage_cereales, calories_jour, variation_poids_semaine, prix_base_semaine) VALUES
('Régime Méditerranéen', 'Riche en légumes, fruits, poisson et huile d''olive. Idéal pour la santé cardiovasculaire.', 10.00, 30.00, 15.00, 25.00, 15.00, 5.00, 2000, -0.50, 45000.00),
('Régime Hyperprotéiné', 'Riche en protéines pour la prise de masse musculaire et perte de graisse.', 35.00, 20.00, 25.00, 10.00, 5.00, 5.00, 2200, -0.80, 55000.00),
('Régime Végétarien Équilibré', 'Sans viande, riche en légumes, fruits et céréales complètes.', 0.00, 0.00, 0.00, 40.00, 30.00, 30.00, 1800, -0.40, 35000.00),
('Régime Prise de Masse', 'Hypercalorique pour augmenter le poids et la masse musculaire.', 25.00, 15.00, 30.00, 15.00, 5.00, 10.00, 3000, 0.60, 60000.00),
('Régime Détox', 'Faible en calories, riche en fruits et légumes pour purifier l''organisme.', 5.00, 10.00, 10.00, 40.00, 30.00, 5.00, 1500, -0.70, 40000.00);

-- Activités sportives
INSERT INTO activites_sportives (nom, description, calories_brulees_heure, niveau_difficulte, duree_recommandee_minutes, frequence_semaine) VALUES
('Course à pied', 'Excellent pour le cardio et la perte de poids. Peut se pratiquer en extérieur ou sur tapis.', 600, 'moyen', 45, 3),
('Natation', 'Sport complet qui sollicite tous les muscles. Idéal pour les articulations.', 500, 'moyen', 60, 2),
('Musculation', 'Renforcement musculaire avec poids. Parfait pour la prise de masse.', 400, 'difficile', 60, 4),
('Yoga', 'Améliore la flexibilité, réduit le stress. Convient à tous les niveaux.', 200, 'facile', 60, 3),
('Cyclisme', 'Excellent pour le cardio et les jambes. Peut se pratiquer en salle ou extérieur.', 550, 'moyen', 50, 3);

-- Codes porte-monnaie
INSERT INTO codes_portemonnaie (code, montant, date_expiration) VALUES
('WELCOME2026', 20000.00, '2026-12-31 23:59:59'),
('GOLD50K', 50000.00, '2026-12-31 23:59:59'),
('PROMO10K', 10000.00, '2026-08-31 23:59:59'),
('BONUS30K', 30000.00, '2026-12-31 23:59:59'),
('START15K', 15000.00, '2026-09-30 23:59:59'),
('HEALTH25K', 25000.00, '2026-12-31 23:59:59'),
('FIT40K', 40000.00, '2026-12-31 23:59:59'),
('DIET20K', 20000.00, '2026-10-31 23:59:59'),
('SPORT35K', 35000.00, '2026-12-31 23:59:59'),
('WELLNESS45K', 45000.00, '2026-12-31 23:59:59'),
('SUMMER30K', 30000.00, '2026-07-31 23:59:59'),
('WINTER25K', 25000.00, '2026-12-31 23:59:59'),
('SPRING20K', 20000.00, '2026-06-30 23:59:59'),
('AUTUMN15K', 15000.00, '2026-11-30 23:59:59'),
('NEWYEAR50K', 50000.00, '2026-12-31 23:59:59');

-- Paramètres système
INSERT INTO parametres_systeme (cle, valeur, description, type_donnee) VALUES
('prix_abonnement_gold', '100000', 'Prix de l''abonnement Gold en Ariary', 'number'),
('remise_gold_pourcentage', '15', 'Pourcentage de remise pour les membres Gold', 'number'),
('imc_ideal_min', '18.5', 'IMC idéal minimum', 'number'),
('imc_ideal_max', '24.9', 'IMC idéal maximum', 'number'),
('imc_surpoids_min', '25', 'IMC surpoids minimum', 'number'),
('imc_obesite_min', '30', 'IMC obésité minimum', 'number'),
('duree_min_programme', '4', 'Durée minimum d''un programme en semaines', 'number'),
('duree_max_programme', '52', 'Durée maximum d''un programme en semaines', 'number');

-- Programmes utilisateur (exemples)
INSERT INTO programmes_utilisateur (utilisateur_id, regime_id, activite_sportive_id, duree_semaines, prix_total, prix_paye, remise_appliquee, date_debut, date_fin, poids_initial) VALUES
(1, 2, 1, 8, 440000.00, 374000.00, 15.00, '2026-04-01', '2026-05-27', 85.00),
(3, 1, 2, 12, 540000.00, 459000.00, 15.00, '2026-03-15', '2026-06-07', 90.00);

-- ============================================
-- VUES UTILES
-- ============================================

-- Vue : Statistiques utilisateurs
CREATE VIEW v_stats_utilisateurs AS
SELECT 
    u.id,
    u.nom,
    u.prenom,
    u.email,
    u.genre,
    u.est_gold,
    u.solde_portemonnaie,
    i.taille,
    i.poids,
    i.imc,
    i.objectif,
    CASE 
        WHEN i.imc < 18.5 THEN 'Insuffisance pondérale'
        WHEN i.imc BETWEEN 18.5 AND 24.9 THEN 'Poids normal'
        WHEN i.imc BETWEEN 25 AND 29.9 THEN 'Surpoids'
        ELSE 'Obésité'
    END AS categorie_imc,
    COUNT(p.id) AS nombre_programmes
FROM utilisateurs u
LEFT JOIN informations_sante i ON u.id = i.utilisateur_id
LEFT JOIN programmes_utilisateur p ON u.id = p.utilisateur_id
GROUP BY u.id, u.nom, u.prenom, u.email, u.genre, u.est_gold, u.solde_portemonnaie, i.taille, i.poids, i.imc, i.objectif;

-- Vue : Statistiques régimes
CREATE VIEW v_stats_regimes AS
SELECT 
    r.id,
    r.nom,
    r.calories_jour,
    r.variation_poids_semaine,
    r.prix_base_semaine,
    COUNT(p.id) AS nombre_utilisations,
    AVG(p.duree_semaines) AS duree_moyenne,
    SUM(p.prix_paye) AS revenu_total
FROM regimes r
LEFT JOIN programmes_utilisateur p ON r.id = p.regime_id
GROUP BY r.id, r.nom, r.calories_jour, r.variation_poids_semaine, r.prix_base_semaine;

-- ============================================
-- PROCÉDURES STOCKÉES
-- ============================================

DELIMITER //

-- Procédure : Calculer le prix d'un programme
CREATE PROCEDURE sp_calculer_prix_programme(
    IN p_regime_id INT,
    IN p_duree_semaines INT,
    IN p_est_gold BOOLEAN,
    OUT p_prix_total DECIMAL(10,2),
    OUT p_prix_paye DECIMAL(10,2),
    OUT p_remise DECIMAL(5,2)
)
BEGIN
    DECLARE v_prix_base DECIMAL(10,2);
    DECLARE v_remise_gold DECIMAL(5,2);
    
    -- Récupérer le prix de base du régime
    SELECT prix_base_semaine INTO v_prix_base
    FROM regimes
    WHERE id = p_regime_id;
    
    -- Calculer le prix total
    SET p_prix_total = v_prix_base * p_duree_semaines;
    
    -- Appliquer la remise Gold si applicable
    IF p_est_gold THEN
        SELECT CAST(valeur AS DECIMAL(5,2)) INTO v_remise_gold
        FROM parametres_systeme
        WHERE cle = 'remise_gold_pourcentage';
        
        SET p_remise = v_remise_gold;
        SET p_prix_paye = p_prix_total * (1 - (v_remise_gold / 100));
    ELSE
        SET p_remise = 0;
        SET p_prix_paye = p_prix_total;
    END IF;
END //

DELIMITER ;

-- ============================================
-- INDEX SUPPLÉMENTAIRES POUR PERFORMANCE
-- ============================================

CREATE INDEX idx_programmes_dates ON programmes_utilisateur(date_debut, date_fin);
CREATE INDEX idx_codes_expiration ON codes_portemonnaie(date_expiration);
CREATE INDEX idx_sante_objectif ON informations_sante(objectif);

-- ============================================
-- FIN DU SCRIPT
-- ============================================
