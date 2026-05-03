-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 03, 2026 at 08:39 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `evento`
--

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `title` varchar(180) NOT NULL,
  `description` text NOT NULL,
  `event_date` datetime NOT NULL,
  `location` varchar(150) NOT NULL,
  `category` varchar(80) NOT NULL,
  `image` varchar(255) NOT NULL,
  `capacity` int(11) NOT NULL DEFAULT 100,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `title`, `description`, `event_date`, `location`, `category`, `image`, `capacity`, `created_at`) VALUES
(1, 'AI Frontier Summit', 'Deep dives into enterprise AI deployment and product innovation.', '2026-06-10 09:00:00', 'New York, USA', 'AI', 'https://images.unsplash.com/photo-1521737604893-d14cc237f11d?auto=format&fit=crop&w=1200&q=80', 300, '2026-05-03 13:13:30'),
(2, 'CloudScale Expo', 'Modern cloud architecture, cost optimization and platform engineering.', '2026-06-18 10:00:00', 'Austin, USA', 'Cloud', 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?auto=format&fit=crop&w=1200&q=80', 250, '2026-05-03 13:13:30');

-- --------------------------------------------------------

--
-- Table structure for table `newsletter`
--

CREATE TABLE `newsletter` (
  `id` int(11) NOT NULL,
  `email` varchar(190) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `newsletter`
--

INSERT INTO `newsletter` (`id`, `email`, `created_at`) VALUES
(1, 'aaa@gmail.com', '2026-05-03 14:54:58');

-- --------------------------------------------------------

--
-- Table structure for table `registrations`
--

CREATE TABLE `registrations` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `event_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `full_name` varchar(150) DEFAULT NULL,
  `email` varchar(190) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `profile_type` enum('student','freelancer','entrepreneur') DEFAULT NULL,
  `cv` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `registrations`
--

INSERT INTO `registrations` (`id`, `user_id`, `event_id`, `created_at`, `full_name`, `email`, `phone`, `address`, `profile_type`, `cv`) VALUES
(4, 9, 1, '2026-05-03 15:01:13', NULL, NULL, NULL, NULL, NULL, NULL),
(5, 9, 2, '2026-05-03 15:01:16', NULL, NULL, NULL, NULL, NULL, NULL),
(8, 10, 2, '2026-05-03 17:23:41', NULL, NULL, NULL, NULL, NULL, NULL),
(9, 10, 1, '2026-05-03 17:37:54', NULL, NULL, NULL, NULL, NULL, NULL),
(11, NULL, 2, '2026-05-03 17:56:40', 'kmar', 'kmar@bentsalmocuha.com', '12354678', 'Lac 2, Tunis', 'student', NULL),
(12, NULL, 2, '2026-05-03 17:58:25', 'kmar', 'kmar@bentsalmocuha.com', '12354678', 'Lac 2, Tunis', 'student', NULL),
(14, NULL, 1, '2026-05-03 18:16:24', 'Aziz', 'contact@hamzakhlaf.com', '21999898', 'Lac 2, Tunis', 'student', 'uploads/cv_1777832184_7825d1bd.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `sponsors`
--

CREATE TABLE `sponsors` (
  `id` int(11) NOT NULL,
  `name` varchar(120) NOT NULL,
  `company` varchar(150) NOT NULL,
  `email` varchar(190) NOT NULL,
  `budget` decimal(12,2) NOT NULL,
  `event_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sponsors`
--

INSERT INTO `sponsors` (`id`, `name`, `company`, `email`, `budget`, `event_id`, `created_at`) VALUES
(1, 'test', 'ffff', 'contact@hamzakhlaf.com', 1000.00, 2, '2026-05-03 14:41:51');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(120) NOT NULL,
  `email` varchar(190) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('user','admin') NOT NULL DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `cv` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password_hash`, `role`, `created_at`, `cv`) VALUES
(8, 'Admin', 'admin@eventotn.com', '$2y$10$eeSpX04MtqQo.t6uYwQ46efXXU1KqFJXWqPXiVgFOpX.dE2MdwzOC', 'admin', '2026-05-03 14:26:41', NULL),
(9, 'hamza', 'hamza.khlaf@esen.tn', '$2y$10$MKwK51Eeh6.9L7ok3.jrsO91SE9d23/Od.CBOUHrxrpAZeP2e074K', 'user', '2026-05-03 14:59:19', NULL),
(10, 'Test1', 'test@hkh.com', '$2y$10$HM.QTfG.X4BoaT9a4wPNReFAUbmIMyXlosdr3HuAs5GlzxwEH7Rd2', 'user', '2026-05-03 17:23:23', 'uploads/cv_1777830329.pdf'),
(11, 'salmoucha', 'salma@mezyena.com', '$2y$10$nhGsmcG/teXRdt1ocRgwOuHbD//NuKY4519HtBUlZhicy28JtPsGe', 'user', '2026-05-03 17:55:09', 'uploads/cv_1777830930.pdf'),
(12, 'yyy', 'yyy@test.com', '$2y$10$/LKx5IkW2UxL/d0Y5JxDUexMVnuEIJ3tR7qfHtR.Ay3X89S5Wvt4.', 'user', '2026-05-03 18:34:18', 'uploads/cv_1777833273.pdf');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `newsletter`
--
ALTER TABLE `newsletter`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `registrations`
--
ALTER TABLE `registrations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_registration` (`user_id`,`event_id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `sponsors`
--
ALTER TABLE `sponsors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `newsletter`
--
ALTER TABLE `newsletter`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `registrations`
--
ALTER TABLE `registrations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `sponsors`
--
ALTER TABLE `sponsors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `registrations`
--
ALTER TABLE `registrations`
  ADD CONSTRAINT `registrations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `registrations_ibfk_2` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sponsors`
--
ALTER TABLE `sponsors`
  ADD CONSTRAINT `sponsors_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
