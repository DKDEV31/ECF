-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mar. 20 avr. 2021 à 09:16
-- Version du serveur :  8.0.21
-- Version de PHP : 8.0.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `banqio`
--

-- --------------------------------------------------------

--
-- Structure de la table `account`
--

DROP TABLE IF EXISTS `account`;
CREATE TABLE IF NOT EXISTS `account` (
  `id` int NOT NULL AUTO_INCREMENT,
  `client_id` int NOT NULL,
  `amount` double NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_7D3656A419EB6921` (`client_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `banker`
--

DROP TABLE IF EXISTS `banker`;
CREATE TABLE IF NOT EXISTS `banker` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `firstname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lastname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `adress` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` int NOT NULL,
  `birth_date` date NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_69567A0CE7927C74` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `banker`
--

INSERT INTO `banker` (`id`, `email`, `roles`, `password`, `firstname`, `lastname`, `adress`, `phone`, `birth_date`) VALUES
(3, 'Banker1@test.fr', '[\"ROLE_BANKER\"]', '$argon2id$v=19$m=65536,t=4,p=1$UTF2MHIuQ3lhcVRCWlhkZQ$0elTmd7HuJgVmFT2EK8Yet8P3nNfwoByY8RL85MFUD4', 'Banker1', 'BankerTest', 'test-31200-testCity', 101010101, '1996-11-11'),
(4, 'Banker2@test.fr', '[\"ROLE_BANKER\"]', '$argon2id$v=19$m=65536,t=4,p=1$SjRrZzZULnVuN2laaWtmZw$aFcdeEMRD6MXB3zr4AglO8lE5uWLt/YwDxSDSBKcnnc', 'Banker2', 'Bankertest', 'test-31200-testCity', 101010101, '1996-11-11'),
(5, 'Banker3@test.fr', '[\"ROLE_BANKER\"]', '$argon2id$v=19$m=65536,t=4,p=1$YjNkbzJPRzU4LllHdUJzbw$ILy9NCOcBp9XfVa0qQrtcNk8s5od+fKMakA2Lkbdqo4', 'Banker3', 'Bankertest', 'test-31200-testcity', 101010101, '1996-11-11'),
(6, 'Banker4@test.fr', '[\"ROLE_BANKER\"]', '$argon2id$v=19$m=65536,t=4,p=1$NjI1R3J5MWkxL09zU1RkVg$hXERK+NY2w3C5xtmSG++FL3QEa8t4GLXWLYehMXboCg', 'Banker4', 'Bankertest', 'test-31200-testcity', 101010101, '1996-11-11'),
(7, 'Banker5@test.fr', '[\"ROLE_BANKER\"]', '$argon2id$v=19$m=65536,t=4,p=1$LktTQVd3YmlEbWdMSGdBNA$tjmQXjxv1WU+Tsafit2TbRxsnh2Kre0N0+m6U5vl4/o', 'Banker5', 'Bankertest', 'test-31200-testcity', 101010101, '1996-11-11');

-- --------------------------------------------------------

--
-- Structure de la table `benefit`
--

DROP TABLE IF EXISTS `benefit`;
CREATE TABLE IF NOT EXISTS `benefit` (
  `id` int NOT NULL AUTO_INCREMENT,
  `account_id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bank_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5C8B001F9B6B5FBA` (`account_id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `client`
--

DROP TABLE IF EXISTS `client`;
CREATE TABLE IF NOT EXISTS `client` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `firstname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lastname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `adress` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` int NOT NULL,
  `birth_date` date NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_C7440455E7927C74` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
CREATE TABLE IF NOT EXISTS `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20210410085340', '2021-04-10 08:55:01', 477),
('DoctrineMigrations\\Version20210410161716', '2021-04-10 16:21:15', 2677),
('DoctrineMigrations\\Version20210411145450', '2021-04-11 14:55:33', 5913),
('DoctrineMigrations\\Version20210412122205', '2021-04-12 12:22:19', 1880),
('DoctrineMigrations\\Version20210412150041', '2021-04-12 15:00:52', 4351),
('DoctrineMigrations\\Version20210412155743', '2021-04-12 15:57:49', 3598),
('DoctrineMigrations\\Version20210412160625', '2021-04-12 16:06:33', 3692),
('DoctrineMigrations\\Version20210413091933', '2021-04-13 10:07:49', 3347),
('DoctrineMigrations\\Version20210413100923', '2021-04-13 10:09:56', 3277),
('DoctrineMigrations\\Version20210413142833', '2021-04-13 14:28:41', 2709),
('DoctrineMigrations\\Version20210414121549', '2021-04-14 12:16:03', 1076),
('DoctrineMigrations\\Version20210415085825', '2021-04-15 08:58:34', 547),
('DoctrineMigrations\\Version20210415090939', '2021-04-15 09:09:44', 2200);

-- --------------------------------------------------------

--
-- Structure de la table `request_account`
--

DROP TABLE IF EXISTS `request_account`;
CREATE TABLE IF NOT EXISTS `request_account` (
  `id` int NOT NULL AUTO_INCREMENT,
  `banker_id` int NOT NULL,
  `state` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_card` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `client_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_571224F338835980` (`banker_id`),
  KEY `IDX_571224F319EB6921` (`client_id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `request_benefit`
--

DROP TABLE IF EXISTS `request_benefit`;
CREATE TABLE IF NOT EXISTS `request_benefit` (
  `id` int NOT NULL AUTO_INCREMENT,
  `banker_id` int NOT NULL,
  `state` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bank_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `client_id` int NOT NULL,
  `account_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_76AF724838835980` (`banker_id`),
  KEY `IDX_76AF724819EB6921` (`client_id`),
  KEY `IDX_76AF72489B6B5FBA` (`account_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `request_delete`
--

DROP TABLE IF EXISTS `request_delete`;
CREATE TABLE IF NOT EXISTS `request_delete` (
  `id` int NOT NULL AUTO_INCREMENT,
  `banker_id` int NOT NULL,
  `client_id` int NOT NULL,
  `state` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `close_request` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_714BBF7C38835980` (`banker_id`),
  KEY `IDX_714BBF7C19EB6921` (`client_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `transfer`
--

DROP TABLE IF EXISTS `transfer`;
CREATE TABLE IF NOT EXISTS `transfer` (
  `id` int NOT NULL AUTO_INCREMENT,
  `account_id` int NOT NULL,
  `benefit_id` int DEFAULT NULL,
  `amount` double NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_4034A3C09B6B5FBA` (`account_id`),
  KEY `IDX_4034A3C0B517B89` (`benefit_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `account`
--
ALTER TABLE `account`
  ADD CONSTRAINT `FK_7D3656A419EB6921` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`);

--
-- Contraintes pour la table `benefit`
--
ALTER TABLE `benefit`
  ADD CONSTRAINT `FK_5C8B001F9B6B5FBA` FOREIGN KEY (`account_id`) REFERENCES `account` (`id`);

--
-- Contraintes pour la table `request_account`
--
ALTER TABLE `request_account`
  ADD CONSTRAINT `FK_571224F319EB6921` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`),
  ADD CONSTRAINT `FK_571224F338835980` FOREIGN KEY (`banker_id`) REFERENCES `banker` (`id`);

--
-- Contraintes pour la table `request_benefit`
--
ALTER TABLE `request_benefit`
  ADD CONSTRAINT `FK_76AF724819EB6921` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`),
  ADD CONSTRAINT `FK_76AF724838835980` FOREIGN KEY (`banker_id`) REFERENCES `banker` (`id`),
  ADD CONSTRAINT `FK_76AF72489B6B5FBA` FOREIGN KEY (`account_id`) REFERENCES `account` (`id`);

--
-- Contraintes pour la table `request_delete`
--
ALTER TABLE `request_delete`
  ADD CONSTRAINT `FK_714BBF7C19EB6921` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`),
  ADD CONSTRAINT `FK_714BBF7C38835980` FOREIGN KEY (`banker_id`) REFERENCES `banker` (`id`);

--
-- Contraintes pour la table `transfer`
--
ALTER TABLE `transfer`
  ADD CONSTRAINT `FK_4034A3C09B6B5FBA` FOREIGN KEY (`account_id`) REFERENCES `account` (`id`),
  ADD CONSTRAINT `FK_4034A3C0B517B89` FOREIGN KEY (`benefit_id`) REFERENCES `benefit` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
