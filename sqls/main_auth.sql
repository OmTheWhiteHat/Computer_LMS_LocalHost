-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 21, 2025 at 07:02 AM
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
-- Database: `main_auth`
--

-- --------------------------------------------------------

--
-- Table structure for table `labs`
--

CREATE TABLE `labs` (
  `id` int(11) NOT NULL,
  `lab_name` varchar(100) NOT NULL,
  `database_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `labs`
--

INSERT INTO `labs` (`id`, `lab_name`, `database_name`) VALUES
(1, 'CS-01', 'lab_A'),
(2, 'CS-02', 'lab_B'),
(3, 'CAD', 'lab_C'),
(4, 'MAT', 'lab_D');

-- --------------------------------------------------------

--
-- Table structure for table `system_logs`
--

CREATE TABLE `system_logs` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `action` varchar(50) NOT NULL,
  `ip_address` varchar(50) DEFAULT NULL,
  `timestamp` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system_logs`
--

INSERT INTO `system_logs` (`id`, `username`, `action`, `ip_address`, `timestamp`) VALUES
(1, 'admin', 'QR Login', '::1', '2025-03-08 10:16:00'),
(2, 'admin', 'QR Login', '::1', '2025-03-08 10:16:30'),
(3, 'admin', 'Login', '::1', '2025-03-08 10:16:38'),
(4, 'admin', 'Login', '::1', '2025-03-08 10:16:44'),
(5, 'admin', 'QR Login', '::1', '2025-03-08 10:16:50'),
(6, 'admin', 'QR Login', '::1', '2025-03-08 10:19:38'),
(7, 'admin', 'Login', '::1', '2025-03-08 10:21:50'),
(8, 'admin', 'Login', '::1', '2025-03-08 10:22:25'),
(9, 'debu', 'Login', '::1', '2025-03-08 10:23:13'),
(10, 'debu', 'Login', '::1', '2025-03-08 10:23:31'),
(11, 'sourav', 'QR Login', '::1', '2025-03-08 10:29:01'),
(12, 'sourav', 'Login', '::1', '2025-03-08 10:29:15'),
(13, 'sourav', 'Login', '::1', '2025-03-08 10:30:49'),
(14, 'sourav', 'QR Login', '::1', '2025-03-08 10:30:55'),
(15, 'admin', 'Login', '::1', '2025-03-08 10:32:03'),
(16, 'debu', 'QR Login', '::1', '2025-03-08 11:20:54'),
(17, 'admin', 'QR Login', '::1', '2025-03-19 05:03:43'),
(18, 'admin', 'Login', '::1', '2025-03-21 06:14:57');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `rmail` varchar(255) DEFAULT NULL,
  `qr_code` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password_hash`, `rmail`, `qr_code`) VALUES
(1, 'admin', '$2y$10$o7010FYzGXbwiRFfIFRKfuFN0giH8evDLW.bdoMng4H40TIRILhAy', 'admin@gmail.com', 'user_admin_67cc0abe4d5c8'),
(2, 'debu', '$2y$10$UXiSlX.P7PJls7BonG06fewvgU2pgSkQjH.IbJGqQ/6DWFv.BVZjO', 'debu@chuta.com', 'user_debu_67cc0c8de5039'),
(3, 'sourav', '$2y$10$YYEIRq3gVKvACfiGTjI.MuVoZ0zA7wStKc7mFajID2oAgpDCHtNOW', 'sourav@gmail.com', 'user_sourav_67cc0dd5dd136');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `labs`
--
ALTER TABLE `labs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `lab_name` (`lab_name`),
  ADD UNIQUE KEY `database_name` (`database_name`),
  ADD UNIQUE KEY `unique_lab_name` (`lab_name`);

--
-- Indexes for table `system_logs`
--
ALTER TABLE `system_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `qr_code` (`qr_code`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `labs`
--
ALTER TABLE `labs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `system_logs`
--
ALTER TABLE `system_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
