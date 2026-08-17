-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 16, 2026 at 11:25 PM
-- Server version: 5.7.24
-- PHP Version: 8.3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wdp_a3`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `category_name`, `description`) VALUES
(1, 'Adventure', 'Games focused on exploration, quests and adventure'),
(2, 'Animals', 'Games featuring animals, wildlife or nature'),
(3, 'Card Game', 'Games where cards are a major component of play'),
(4, 'City Building', 'Games focused on constructing and developing cities or settlements'),
(5, 'Economic', 'Games involving money, industry, markets or resource management'),
(6, 'Fantasy', 'Games featuring fantasy worlds, characters or themes'),
(7, 'Medical', 'Games involving medicine, diseases or healthcare themes'),
(8, 'Science Fiction', 'Games featuring futuristic, space or science-fiction themes'),
(9, 'Territory Building', 'Games involving control, development or expansion of territory'),
(10, 'Trains', 'Games featuring trains, railways or transportation');

-- --------------------------------------------------------

--
-- Table structure for table `collections`
--

CREATE TABLE `collections` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `game_id` int(11) NOT NULL,
  `date_added` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `collections`
--

INSERT INTO `collections` (`id`, `user_id`, `game_id`, `date_added`) VALUES
(1, 1, 2, '2026-08-16');

-- --------------------------------------------------------

--
-- Table structure for table `games`
--

CREATE TABLE `games` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `date_published` date NOT NULL,
  `description` text NOT NULL,
  `category_id` int(11) NOT NULL DEFAULT '1',
  `min_players` int(11) NOT NULL DEFAULT '1',
  `max_players` int(11) NOT NULL DEFAULT '4',
  `min_play_time` int(11) NOT NULL DEFAULT '30',
  `max_play_time` int(11) NOT NULL DEFAULT '60',
  `image_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `games`
--

INSERT INTO `games` (`id`, `name`, `date_published`, `description`, `category_id`, `min_players`, `max_players`, `min_play_time`, `max_play_time`, `image_url`) VALUES
(1, 'Brass: Birmingham', '2018-01-01', 'Build networks, develop industries and manage resources during the Industrial Revolution in Birmingham.', 5, 2, 4, 60, 120, 'brass-birmingham-removebg-preview.png'),
(2, 'Ark Nova', '2021-01-01', 'Plan and build a modern zoo while supporting conservation projects and managing animals, resources and attractions.', 2, 1, 4, 90, 150, 'ark-nova-removebg-preview.png'),
(3, 'Pandemic Legacy: Season 1', '2015-01-01', 'Work together to fight diseases around the world while the story and game rules develop throughout the campaign.', 7, 2, 4, 60, 60, 'pandemic-legacy-removebg-preview.png'),
(4, 'Gloomhaven', '2017-01-01', 'Explore a dark fantasy world, fight monsters and complete quests using strategic card-based combat.', 1, 1, 4, 60, 120, 'gloomhaven-removebg-preview.png'),
(5, 'Dune: Imperium – Uprising', '2023-01-01', 'Deploy agents, build your deck and compete for control of Arrakis through political influence and strategic battles.', 8, 1, 6, 60, 120, 'duneimperium-uprising-removebg-preview.png'),
(6, 'Dune: Imperium', '2020-01-01', 'Use political influence, strategic planning and combat to gain control in the universe of Dune.', 8, 1, 4, 60, 120, 'dune-imperium-removebg-preview.png'),
(7, 'Twilight Imperium: Fourth Edition', '2017-01-01', 'Build an intergalactic civilization through exploration, trade, technology, diplomacy and warfare.', 8, 3, 6, 240, 480, 'twilight-imperium-removebg-preview.png'),
(8, 'War of the Ring: Second Edition', '2011-01-01', 'Experience the epic conflict between the Free Peoples and Sauron in the world of Middle-earth.', 6, 2, 4, 120, 180, 'war-of-the-ring-removebg-preview.png'),
(9, 'Terraforming Mars', '2016-01-01', 'Compete with rival corporations to develop Mars, manage resources and make the planet habitable.', 8, 1, 5, 120, 180, 'terraforming-mars-removebg-preview.png'),
(10, 'Star Wars: Rebellion', '2016-01-01', 'Play as the Rebel Alliance or Galactic Empire in a strategic struggle across the Star Wars galaxy.', 8, 2, 4, 180, 240, 'starwars-rebellion-removebg-preview.png');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `game_id` int(11) NOT NULL,
  `rating` tinyint(1) NOT NULL,
  `title` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `recommend` tinyint(1) NOT NULL DEFAULT '0',
  `play_count` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `role_name` varchar(50) NOT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `role_name`, `description`) VALUES
(1, 'Admin', 'Administrator with full access to manage games, categories and users'),
(2, 'User', 'Regular user who can browse games, add games to their collection and write reviews');

-- --------------------------------------------------------

--
-- Table structure for table `security_logs`
--

CREATE TABLE `security_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `login_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `browser` varchar(150) DEFAULT NULL,
  `remember_me` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role_id` int(11) NOT NULL DEFAULT '2',
  `first_name` varchar(100) NOT NULL DEFAULT '',
  `last_name` varchar(100) NOT NULL DEFAULT '',
  `remember_token` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role_id`, `first_name`, `last_name`, `remember_token`, `created_at`) VALUES
(1, 'testON', 'test@gmail.com', '$2y$10$iGv44LwEOzymGDobboHoS.U8xLf/rNR7ngAMuYLmkqzXDcaBxOg4i', 2, 'test', 'test', NULL, '2026-08-16 00:15:05'),
(2, 'admin', 'admin@gainme.com', '$2y$10$Ae6eEB13gwhbQqcW9fhShO4b4DyMJzOEOHBiuwsB8ln9QGkUuQfwC', 1, 'Admin', 'User', NULL, '2026-08-16 17:05:02');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_category_name` (`category_name`);

--
-- Indexes for table `collections`
--
ALTER TABLE `collections`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_user_game` (`user_id`,`game_id`),
  ADD KEY `idx_collections_user` (`user_id`),
  ADD KEY `idx_collections_game` (`game_id`);

--
-- Indexes for table `games`
--
ALTER TABLE `games`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_games_category` (`category_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_review_user_game` (`user_id`,`game_id`),
  ADD KEY `idx_reviews_user_id` (`user_id`),
  ADD KEY `idx_reviews_game_id` (`game_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_role_name` (`role_name`);

--
-- Indexes for table `security_logs`
--
ALTER TABLE `security_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_security_user` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_users_role` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `collections`
--
ALTER TABLE `collections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `games`
--
ALTER TABLE `games`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `security_logs`
--
ALTER TABLE `security_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `collections`
--
ALTER TABLE `collections`
  ADD CONSTRAINT `fk_collections_game` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_collections_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `games`
--
ALTER TABLE `games`
  ADD CONSTRAINT `fk_games_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `fk_reviews_game` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_reviews_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `security_logs`
--
ALTER TABLE `security_logs`
  ADD CONSTRAINT `fk_security_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
