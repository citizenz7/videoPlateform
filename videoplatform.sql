-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : mar. 27 août 2024 à 12:19
-- Version du serveur : 10.11.8-MariaDB-0ubuntu0.24.04.1
-- Version de PHP : 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `videoplatform`
--

-- --------------------------------------------------------

--
-- Structure de la table `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Déchargement des données de la table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20240826141036', '2024-08-26 14:10:40', 22),
('DoctrineMigrations\\Version20240826141111', '2024-08-26 14:11:14', 16),
('DoctrineMigrations\\Version20240826141722', '2024-08-26 14:17:26', 76),
('DoctrineMigrations\\Version20240826154305', '2024-08-26 15:43:07', 41),
('DoctrineMigrations\\Version20240826154517', '2024-08-26 15:45:19', 60),
('DoctrineMigrations\\Version20240826161709', '2024-08-26 16:17:12', 15),
('DoctrineMigrations\\Version20240826161732', '2024-08-26 16:17:34', 14),
('DoctrineMigrations\\Version20240826163540', '2024-08-26 16:35:42', 13),
('DoctrineMigrations\\Version20240827045756', '2024-08-27 04:58:01', 51),
('DoctrineMigrations\\Version20240827073733', '2024-08-27 07:37:38', 12),
('DoctrineMigrations\\Version20240827112553', '2024-08-27 11:25:58', 14),
('DoctrineMigrations\\Version20240827113758', '2024-08-27 11:38:00', 14);

-- --------------------------------------------------------

--
-- Structure de la table `messenger_messages`
--

CREATE TABLE `messenger_messages` (
  `id` bigint(20) NOT NULL,
  `body` longtext NOT NULL,
  `headers` longtext NOT NULL,
  `queue_name` varchar(190) NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `available_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `delivered_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(180) NOT NULL,
  `roles` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT '(DC2Type:json)' CHECK (json_valid(`roles`)),
  `password` varchar(255) NOT NULL,
  `firstname` varchar(255) DEFAULT NULL,
  `lastname` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `email`, `roles`, `password`, `firstname`, `lastname`, `image`, `active`) VALUES
(1, 'citizenz7@protonmail.com', '[\"ROLE_ADMIN\"]', '$2y$13$9rs61oTKecD/PS/jWHeExOj4kgO1FdssFVc6Cgu9BF1lP2acHMB6O', 'Olivier', 'Prieur', 'me_jpg_75-1724758584.jpg', 1),
(2, 'shadrak@protonmail.com', '[\"ROLE_USER\"]', '$2y$13$XlbSGLnH6/bxKyKXER1GeO9MyCoBsYRSj2nOIYURS8vUNTZAiJ/mG', 'Gérald', 'Lopez', 'maccaron-1724758618.jpg', 1),
(3, 'tornzen@protonmail.com', '[]', '$2y$13$e/t4KfqHx7oxEw000BNfmuD4qRJy4T.vFI3q42MUElX8jBTYUNVTq', 'Stéphane', 'Duplessis', '4418-1724758637.jpg', 1);

-- --------------------------------------------------------

--
-- Structure de la table `user_videos_watched`
--

CREATE TABLE `user_videos_watched` (
  `user_id` int(11) NOT NULL,
  `video_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user_videos_watched`
--

INSERT INTO `user_videos_watched` (`user_id`, `video_id`) VALUES
(1, 1),
(1, 3),
(1, 4),
(2, 1),
(2, 3),
(2, 4),
(3, 1),
(3, 3);

-- --------------------------------------------------------

--
-- Structure de la table `video`
--

CREATE TABLE `video` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `duration` double NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `video`
--

INSERT INTO `video` (`id`, `title`, `url`, `duration`, `image`) VALUES
(1, 'Calcio 1', 'videoplayback.mp4', 144, 'calcio1-1724750250.png'),
(3, 'Calcio 2', 'videoplayback.mp4', 149, 'calcio2-1724750242.png'),
(4, 'Foot Thauvin 1', 'foot-thauv-1-1724752602.mp4', 145, 'thauv1-1724752602.png');

-- --------------------------------------------------------

--
-- Structure de la table `video_progress`
--

CREATE TABLE `video_progress` (
  `id` int(11) NOT NULL,
  `progress` double NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `video_id` int(11) DEFAULT NULL,
  `last_watched_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `video_progress`
--

INSERT INTO `video_progress` (`id`, `progress`, `user_id`, `video_id`, `last_watched_at`) VALUES
(4, 52.360498, 1, 1, '2024-08-27 10:31:52'),
(5, 144.428117, 2, 3, '2024-08-27 11:12:19'),
(6, 144.428117, 2, 1, '2024-08-27 11:16:24'),
(7, 144.428117, 3, 1, '2024-08-27 08:56:50'),
(8, 144.428117, 3, 3, '2024-08-27 09:07:57'),
(9, 144.428117, 1, 3, '2024-08-27 09:23:55'),
(10, 145.078276, 1, 4, '2024-08-27 10:54:44'),
(11, 145.078276, 2, 4, '2024-08-27 11:08:46'),
(12, 43.230952, 3, 4, '2024-08-27 11:17:45');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Index pour la table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  ADD KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  ADD KEY `IDX_75EA56E016BA31DB` (`delivered_at`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_IDENTIFIER_EMAIL` (`email`);

--
-- Index pour la table `user_videos_watched`
--
ALTER TABLE `user_videos_watched`
  ADD PRIMARY KEY (`user_id`,`video_id`),
  ADD KEY `IDX_95D12ED2A76ED395` (`user_id`),
  ADD KEY `IDX_95D12ED229C1004E` (`video_id`);

--
-- Index pour la table `video`
--
ALTER TABLE `video`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `video_progress`
--
ALTER TABLE `video_progress`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_8A83C0FAA76ED395` (`user_id`),
  ADD KEY `IDX_8A83C0FA29C1004E` (`video_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `video`
--
ALTER TABLE `video`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `video_progress`
--
ALTER TABLE `video_progress`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `user_videos_watched`
--
ALTER TABLE `user_videos_watched`
  ADD CONSTRAINT `FK_95D12ED229C1004E` FOREIGN KEY (`video_id`) REFERENCES `video` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_95D12ED2A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `video_progress`
--
ALTER TABLE `video_progress`
  ADD CONSTRAINT `FK_8A83C0FA29C1004E` FOREIGN KEY (`video_id`) REFERENCES `video` (`id`),
  ADD CONSTRAINT `FK_8A83C0FAA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
