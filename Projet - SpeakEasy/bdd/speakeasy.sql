-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : dim. 04 juin 2023 à 23:53
-- Version du serveur : 8.0.31
-- Version de PHP : 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `speakeasy`
--

-- --------------------------------------------------------

--
-- Structure de la table `amis`
--

DROP TABLE IF EXISTS `amis`;
CREATE TABLE IF NOT EXISTS `amis` (
  `id_utilisateur` int NOT NULL,
  `id_ami` int NOT NULL,
  PRIMARY KEY (`id_utilisateur`,`id_ami`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `amis`
--

INSERT INTO `amis` (`id_utilisateur`, `id_ami`) VALUES
(1, 2),
(1, 3),
(2, 1),
(3, 1);

-- --------------------------------------------------------

--
-- Structure de la table `associer`
--

DROP TABLE IF EXISTS `associer`;
CREATE TABLE IF NOT EXISTS `associer` (
  `id_serveur` int NOT NULL,
  `id_utilisateur` int NOT NULL,
  PRIMARY KEY (`id_utilisateur`,`id_serveur`),
  KEY `id_serveur` (`id_serveur`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `associer`
--

INSERT INTO `associer` (`id_serveur`, `id_utilisateur`) VALUES
(1, 1),
(3, 1),
(4, 1),
(1, 2),
(3, 2),
(1, 3);

-- --------------------------------------------------------

--
-- Structure de la table `integration`
--

DROP TABLE IF EXISTS `integration`;
CREATE TABLE IF NOT EXISTS `integration` (
  `id_utilisateur` int NOT NULL AUTO_INCREMENT,
  `id_serveur` int NOT NULL,
  PRIMARY KEY (`id_utilisateur`,`id_serveur`),
  KEY `id_serveur` (`id_serveur`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `invite`
--

DROP TABLE IF EXISTS `invite`;
CREATE TABLE IF NOT EXISTS `invite` (
  `id_rdv` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `id_serveur` int NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id_rdv`,`id_serveur`),
  KEY `id_serveur` (`id_serveur`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `lecture`
--

DROP TABLE IF EXISTS `lecture`;
CREATE TABLE IF NOT EXISTS `lecture` (
  `id_utilisateur` int NOT NULL,
  `id_message` int NOT NULL,
  PRIMARY KEY (`id_utilisateur`,`id_message`),
  KEY `id_message` (`id_message`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `messages`
--

DROP TABLE IF EXISTS `messages`;
CREATE TABLE IF NOT EXISTS `messages` (
  `id_message` int NOT NULL AUTO_INCREMENT,
  `contenu_message` varchar(144) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `date_message` date DEFAULT NULL,
  `heure_message` time DEFAULT NULL,
  `piece_jointe_message` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_utilisateur` int NOT NULL,
  `id_ami` int NOT NULL,
  `id_serveur` int NOT NULL,
  PRIMARY KEY (`id_message`),
  KEY `id_utilisateur` (`id_utilisateur`)
) ENGINE=MyISAM AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `messages`
--

INSERT INTO `messages` (`id_message`, `contenu_message`, `date_message`, `heure_message`, `piece_jointe_message`, `id_utilisateur`, `id_ami`, `id_serveur`) VALUES
(33, 'ff', '2023-06-04', '12:29:55', NULL, 3, 0, 1),
(32, 'vgv', '2023-06-04', '12:29:33', NULL, 1, 0, 1),
(31, 'ff', '2023-06-04', '12:27:29', NULL, 1, 0, 1),
(30, 'gdfg', '2023-06-04', '12:27:11', NULL, 2, 0, 1),
(29, 'f', '2023-06-04', '12:24:13', NULL, 2, 0, 1),
(28, 'ggt', '2023-06-04', '12:23:45', NULL, 1, 0, 1),
(27, 'gdtgt', '2023-06-04', '12:23:30', NULL, 1, 0, 1),
(26, ' n vhf', '2023-06-04', '12:23:19', NULL, 2, 0, 1),
(25, 'gdfwg', '2023-06-04', '12:23:15', NULL, 2, 0, 1),
(24, 'gdfd', '2023-06-04', '12:20:56', NULL, 1, 0, 1),
(23, 'dsgsgds', '2023-06-04', '12:08:22', NULL, 2, 0, 1),
(34, 'ff', '2023-06-04', '12:33:05', NULL, 3, 0, 1),
(35, 'df', '2023-06-04', '12:33:17', NULL, 1, 0, 1),
(36, 'rgr', '2023-06-04', '12:34:27', NULL, 2, 0, 1),
(37, 'f', '2023-06-04', '12:34:44', NULL, 3, 0, 1),
(38, 'zgr', '2023-06-04', '12:36:33', NULL, 3, 0, 1),
(39, 'fff', '2023-06-04', '12:36:47', NULL, 1, 0, 1),
(40, 'coucou', '2023-06-04', '20:54:21', NULL, 1, 0, 3),
(41, 'gvsdgzg', '2023-06-04', '21:32:04', NULL, 1, 0, 4),
(42, 'gzgzg', '2023-06-04', '21:32:07', NULL, 1, 0, 4),
(43, 'hthh', '2023-06-04', '21:32:13', NULL, 1, 0, 4);

-- --------------------------------------------------------

--
-- Structure de la table `pieces_jointes`
--

DROP TABLE IF EXISTS `pieces_jointes`;
CREATE TABLE IF NOT EXISTS `pieces_jointes` (
  `id_piece_jointe` int NOT NULL AUTO_INCREMENT,
  `nom_piece_jointe` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_message` int NOT NULL,
  PRIMARY KEY (`id_piece_jointe`),
  KEY `id_message` (`id_message`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `planification`
--

DROP TABLE IF EXISTS `planification`;
CREATE TABLE IF NOT EXISTS `planification` (
  `id_utilisateur` int NOT NULL,
  `id_rdv` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_utilisateur`,`id_rdv`),
  KEY `id_rdv` (`id_rdv`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `rendez_vous`
--

DROP TABLE IF EXISTS `rendez_vous`;
CREATE TABLE IF NOT EXISTS `rendez_vous` (
  `id_rdv` int NOT NULL AUTO_INCREMENT,
  `titre_rdv` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `date_rdv` date DEFAULT NULL,
  `heure_rdv` time DEFAULT NULL,
  PRIMARY KEY (`id_rdv`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `role`
--

DROP TABLE IF EXISTS `role`;
CREATE TABLE IF NOT EXISTS `role` (
  `id_role` int NOT NULL AUTO_INCREMENT,
  `libelle_role` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_role`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `role`
--

INSERT INTO `role` (`id_role`, `libelle_role`) VALUES
(1, 'Admin'),
(2, 'Enseignant'),
(3, 'Utilisateur');

-- --------------------------------------------------------

--
-- Structure de la table `serveurs`
--

DROP TABLE IF EXISTS `serveurs`;
CREATE TABLE IF NOT EXISTS `serveurs` (
  `id_serveur` int NOT NULL AUTO_INCREMENT,
  `nom_serveur` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id_serveur`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `serveurs`
--

INSERT INTO `serveurs` (`id_serveur`, `nom_serveur`) VALUES
(1, 'ds'),
(2, 'dsdffs'),
(3, 'a+'),
(4, 'coucou'),
(6, 'new'),
(7, 'lerangdufond');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

DROP TABLE IF EXISTS `utilisateur`;
CREATE TABLE IF NOT EXISTS `utilisateur` (
  `id_utilisateur` int NOT NULL AUTO_INCREMENT,
  `nom_utilisateur` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `prenom_utilisateur` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email_utilisateur` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `mdp_utilisateur` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_role` int DEFAULT NULL,
  `image_profil_utilisateur` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `reset_token` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `reset_token_created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_utilisateur`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id_utilisateur`, `nom_utilisateur`, `prenom_utilisateur`, `email_utilisateur`, `mdp_utilisateur`, `id_role`, `image_profil_utilisateur`, `reset_token`, `reset_token_created_at`) VALUES
(1, 'Maxime', 'Falcetta', 'maxime.falcetta@gmail.com', '$2y$10$zu5QXHNUYNBQlui6V6XpVOsUDLbgEJs35QwtaeUic34NrKgLK27lm', 1, NULL, 'Szac1i8c1Z5hzXDfLiR1Y86k87I28h7I', '2023-06-05 01:22:29'),
(2, 'Messi', 'Lionnel', 'leo.messi@email.fr', '$2y$10$HH8BwxT4JHjdiQfVY5u8z.dEJu/UByCQnrwwxtWmE3YjDJtbOz8.e', 3, NULL, NULL, NULL),
(3, 'Mbappé', 'Kylian', 'KMbappe@email.fr', '$2y$10$0zFDrIGDJq1GImn7B1FTZ.QBheiPnsnMLtevOoU9cXMylGzNPAq1W', 2, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur_role`
--

DROP TABLE IF EXISTS `utilisateur_role`;
CREATE TABLE IF NOT EXISTS `utilisateur_role` (
  `id_utilisateur` int NOT NULL,
  `id_role` int NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id_utilisateur`,`id_role`),
  KEY `id_role` (`id_role`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateur_role`
--

INSERT INTO `utilisateur_role` (`id_utilisateur`, `id_role`) VALUES
(1, 1),
(2, 3),
(3, 2);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
