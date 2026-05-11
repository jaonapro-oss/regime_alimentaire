-- MySQL dump 10.13  Distrib 8.0.45, for Linux (x86_64)
--
-- Host: localhost    Database: regime_alimentaire
-- ------------------------------------------------------
-- Server version	8.0.45-0ubuntu0.24.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `activities`
--

DROP TABLE IF EXISTS `activities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `activities` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `nom` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `calories_hour` int NOT NULL COMMENT 'Calories burned per hour',
  `intensite` enum('low','medium','high') COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activities`
--

LOCK TABLES `activities` WRITE;
/*!40000 ALTER TABLE `activities` DISABLE KEYS */;
INSERT INTO `activities` VALUES (1,'Course à pied','Running ou jogging en extérieur',500,'high','2026-05-10 18:31:56'),(2,'Natation','Nage en piscine, tous styles',400,'medium','2026-05-10 18:31:56'),(3,'Musculation','Entraînement avec poids et haltères',350,'high','2026-05-10 18:31:56'),(4,'Marche rapide','Marche à allure soutenue',200,'low','2026-05-10 18:31:56'),(5,'Yoga','Pratique du yoga et étirements',150,'low','2026-05-10 18:31:56');
/*!40000 ALTER TABLE `activities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `codes`
--

DROP TABLE IF EXISTS `codes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `codes` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `montant` decimal(10,2) NOT NULL COMMENT 'Recharge amount in Ariary',
  `is_used` tinyint(1) NOT NULL DEFAULT '0',
  `used_by` int unsigned DEFAULT NULL,
  `used_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  KEY `codes_used_by_foreign` (`used_by`),
  CONSTRAINT `codes_used_by_foreign` FOREIGN KEY (`used_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `codes`
--

LOCK TABLES `codes` WRITE;
/*!40000 ALTER TABLE `codes` DISABLE KEYS */;
INSERT INTO `codes` VALUES (1,'CODE-B58E6D77',5000.00,0,NULL,NULL,'2026-05-10 18:31:56'),(2,'CODE-66EF18A7',20000.00,0,NULL,NULL,'2026-05-10 18:31:56'),(3,'CODE-7A7F77CF',50000.00,0,NULL,NULL,'2026-05-10 18:31:56'),(4,'CODE-807A668F',20000.00,0,NULL,NULL,'2026-05-10 18:31:56'),(5,'CODE-DFC47A44',20000.00,0,NULL,NULL,'2026-05-10 18:31:56'),(6,'CODE-4CAF7146',20000.00,0,NULL,NULL,'2026-05-10 18:31:56'),(7,'CODE-09583E38',50000.00,0,NULL,NULL,'2026-05-10 18:31:56'),(8,'CODE-83B83E80',20000.00,0,NULL,NULL,'2026-05-10 18:31:56'),(9,'CODE-985E29BD',50000.00,0,NULL,NULL,'2026-05-10 18:31:56'),(10,'CODE-B73C0F8B',10000.00,0,NULL,NULL,'2026-05-10 18:31:56'),(11,'CODE-F3C5EB18',5000.00,0,NULL,NULL,'2026-05-10 18:31:56'),(12,'CODE-BCDC2E42',5000.00,0,NULL,NULL,'2026-05-10 18:31:56'),(13,'CODE-D8A857B4',20000.00,0,NULL,NULL,'2026-05-10 18:31:56'),(14,'CODE-0C00B53B',10000.00,0,NULL,NULL,'2026-05-10 18:31:56'),(15,'CODE-C9D6D563',10000.00,0,NULL,NULL,'2026-05-10 18:31:56');
/*!40000 ALTER TABLE `codes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `health_info`
--

DROP TABLE IF EXISTS `health_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `health_info` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL,
  `poids` decimal(5,2) NOT NULL COMMENT 'Weight in kg',
  `taille` decimal(3,2) NOT NULL COMMENT 'Height in meters',
  `age` int NOT NULL,
  `sexe` enum('M','F','Other') COLLATE utf8mb4_general_ci NOT NULL,
  `imc` decimal(4,2) DEFAULT NULL COMMENT 'Calculated BMI',
  `imc_category` enum('underweight','normal','overweight','obese') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `health_info_user_id_foreign` (`user_id`),
  CONSTRAINT `health_info_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `health_info`
--

LOCK TABLES `health_info` WRITE;
/*!40000 ALTER TABLE `health_info` DISABLE KEYS */;
INSERT INTO `health_info` VALUES (1,1,65.50,1.68,25,'F',23.21,'normal',NULL),(2,2,82.00,1.75,30,'M',26.78,'overweight',NULL),(3,3,58.00,1.65,22,'M',21.30,'normal',NULL);
/*!40000 ALTER TABLE `health_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `version` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `class` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `group` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `namespace` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `time` int NOT NULL,
  `batch` int unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2026-05-10-204728','App\\Database\\Migrations\\CreateUsersTable','default','App',1778437677,1),(2,'2026-05-10-204729','App\\Database\\Migrations\\CreateHealthInfoTable','default','App',1778437677,1),(3,'2026-05-10-204730','App\\Database\\Migrations\\CreateActivitiesTable','default','App',1778437677,1),(4,'2026-05-10-204730','App\\Database\\Migrations\\CreateCodesTable','default','App',1778437677,1),(5,'2026-05-10-204730','App\\Database\\Migrations\\CreateObjectivesTable','default','App',1778437677,1),(6,'2026-05-10-204730','App\\Database\\Migrations\\CreateRegimesTable','default','App',1778437677,1),(7,'2026-05-10-204730','App\\Database\\Migrations\\CreateSubscriptionsTable','default','App',1778437677,1),(8,'2026-05-10-204730','App\\Database\\Migrations\\CreateWalletTable','default','App',1778437677,1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `objectives`
--

DROP TABLE IF EXISTS `objectives`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `objectives` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL,
  `objectif_type` enum('gain','loss','ideal_imc') COLLATE utf8mb4_general_ci NOT NULL,
  `poids_cible` decimal(5,2) DEFAULT NULL COMMENT 'Target weight in kg',
  `imc_cible` decimal(4,2) DEFAULT NULL COMMENT 'Target BMI',
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `objectives_user_id_foreign` (`user_id`),
  CONSTRAINT `objectives_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `objectives`
--

LOCK TABLES `objectives` WRITE;
/*!40000 ALTER TABLE `objectives` DISABLE KEYS */;
/*!40000 ALTER TABLE `objectives` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `regimes`
--

DROP TABLE IF EXISTS `regimes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `regimes` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `nom` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `duree_jours` int NOT NULL COMMENT 'Duration in days',
  `prix` decimal(10,2) NOT NULL COMMENT 'Price in Ariary',
  `variation_poids` decimal(5,2) NOT NULL COMMENT 'Expected weight change in kg (can be negative)',
  `pct_viande` int NOT NULL COMMENT 'Percentage of meat',
  `pct_poisson` int NOT NULL COMMENT 'Percentage of fish',
  `pct_volaille` int NOT NULL COMMENT 'Percentage of poultry',
  `objectif` enum('gain','loss','maintain') COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `regimes`
--

LOCK TABLES `regimes` WRITE;
/*!40000 ALTER TABLE `regimes` DISABLE KEYS */;
INSERT INTO `regimes` VALUES (1,'Regime Prise de Masse','Programme intensif pour gagner du muscle et du poids sainement',90,45000.00,5.00,40,30,30,'gain','2026-05-10 18:31:56'),(2,'Regime Minceur Express','Perte de poids rapide et efficace',60,35000.00,-8.00,20,40,40,'loss','2026-05-10 18:31:56'),(3,'Regime Equilibre','Maintien du poids idéal',30,25000.00,0.00,33,33,34,'maintain','2026-05-10 18:31:56'),(4,'Regime Sportif Gain','Prise de poids avec activité sportive intensive',120,55000.00,7.00,50,25,25,'gain','2026-05-10 18:31:56'),(5,'Regime Detox Minceur','Perte de poids douce et détoxification',45,30000.00,-5.00,15,50,35,'loss','2026-05-10 18:31:56');
/*!40000 ALTER TABLE `regimes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subscriptions`
--

DROP TABLE IF EXISTS `subscriptions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `subscriptions` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL,
  `type` enum('free','gold') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'free',
  `discount_pct` int NOT NULL DEFAULT '0' COMMENT '15 for gold, 0 for free',
  `start_date` datetime NOT NULL,
  `end_date` datetime DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `subscriptions_user_id_foreign` (`user_id`),
  CONSTRAINT `subscriptions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subscriptions`
--

LOCK TABLES `subscriptions` WRITE;
/*!40000 ALTER TABLE `subscriptions` DISABLE KEYS */;
INSERT INTO `subscriptions` VALUES (1,1,'free',0,'2026-05-10 18:31:56',NULL,1),(2,2,'gold',15,'2026-05-10 18:31:56',NULL,1),(3,3,'free',0,'2026-05-10 18:31:56',NULL,1);
/*!40000 ALTER TABLE `subscriptions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `nom` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `prenom` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'alice@test.com','$2y$10$JT6/r9.KGHuke0vLjiWhTOdnMpZ3nN/WuHJPqnDlkB80ihyIW98z.','Dupont','Alice',1,'2026-05-10 18:31:55',NULL),(2,'bob@test.com','$2y$10$7yQOX2JjnstulxCZ6PiFr.pxfWEK2iRmpM1L54C85lH/9hTpv3qlC','Martin','Bob',1,'2026-05-10 18:31:55',NULL),(3,'charlie@test.com','$2y$10$eOl6CJdys6T2n8oRSe/0juM1TlueYig5BwipnhSsUVyUSUJ3gsDwG','Ratsimbazafy','Charlie',1,'2026-05-10 18:31:55',NULL),(4,'admin@test.com','$2y$10$Hme9aGD2jexUCkgd3REt3uJnnR.NcAfZC2NiwHUQ9Ni509.9WQ34u','Admin','System',1,'2026-05-10 18:31:56',NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wallet`
--

DROP TABLE IF EXISTS `wallet`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `wallet` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL,
  `solde` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT 'Balance in Ariary',
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`),
  CONSTRAINT `wallet_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wallet`
--

LOCK TABLES `wallet` WRITE;
/*!40000 ALTER TABLE `wallet` DISABLE KEYS */;
INSERT INTO `wallet` VALUES (1,1,50000.00,NULL),(2,2,25000.00,NULL),(3,3,0.00,NULL),(4,4,100000.00,NULL);
/*!40000 ALTER TABLE `wallet` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-05-10 21:32:29
