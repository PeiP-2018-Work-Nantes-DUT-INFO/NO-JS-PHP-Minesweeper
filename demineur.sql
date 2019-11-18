-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le :  jeu. 31 oct. 2019 à 18:07
-- Version du serveur :  5.7.27-0ubuntu0.18.04.1
-- Version de PHP :  7.0.33-12+ubuntu18.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Structure de la table `joueurs`
--

CREATE TABLE `joueurs` (
  `pseudo` varchar(20) NOT NULL,
  `motDePasse` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `joueurs`
--

INSERT INTO `joueurs` (`pseudo`, `motDePasse`) VALUES
('titi', '$2y$10$3fYCvRwlOCXRZFzBtbQgbO91h9cDktrk4K6AXTnFDBHlRGFJF07X6'),
('toto', '$2y$10$TbGVRbipvfLrMmeosAo/XOKJYY1RzRdWk7RXxuSyeLvj40ousLof.'),
('tutu', '$2y$10$44nD.gj3c82Cub8/tbv3peAnm5riwsvR7dlgt2UWfeprH9Z2jtNEK');

-- --------------------------------------------------------

--
-- Structure de la table `parties`
--

CREATE TABLE `parties` (
  `pseudo` varchar(20) NOT NULL,
  `nbPartiesJouees` int(11) NOT NULL,
  `nbPartiesGagnees` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


--
-- Index pour la table `joueurs`
--
ALTER TABLE `joueurs`
  ADD PRIMARY KEY (`pseudo`);

--
-- Index pour la table `parties`
--
ALTER TABLE `parties`
  ADD PRIMARY KEY (`pseudo`);

--
-- Contraintes pour la table `parties`
--
ALTER TABLE `parties`
  ADD CONSTRAINT `pseudo` FOREIGN KEY (`pseudo`) REFERENCES `joueurs` (`pseudo`);
COMMIT;


