-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 05, 2025 at 05:56 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+05:30";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lab_a`
--

-- --------------------------------------------------------

--
-- Table structure for table `devices`
--

CREATE TABLE `devices` (
  `id` int(11) NOT NULL,
  `device_name` varchar(255) NOT NULL,
  `working_condition` enum('Good','Needs Repair','Not Working') DEFAULT 'Good',
  `time_added` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `devices`
--

INSERT INTO `devices` (`id`, `device_name`, `working_condition`, `time_added`) VALUES
(0, 'MOUSE', 'Good', '2025-03-05 04:47:23');

-- --------------------------------------------------------

--
-- Table structure for table `lab_a_cpus`
--

CREATE TABLE `lab_a_cpus` (
  `id` int(11) NOT NULL,
  `cpu_id` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lab_a_issues`
--

CREATE TABLE `lab_a_issues` (
  `id` int(11) NOT NULL,
  `device_type` varchar(20) DEFAULT NULL,
  `description` text NOT NULL,
  `status` enum('Pending','Resolved') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lab_a_logs`
--

CREATE TABLE `lab_a_logs` (
  `id` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `activity` text NOT NULL,
  `username` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lab_a_monitors`
--

CREATE TABLE `lab_a_monitors` (
  `id` int(11) NOT NULL,
  `monitor_id` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lab_a_stocks`
--

CREATE TABLE `lab_a_stocks` (
  `id` int(11) NOT NULL,
  `item_name` varchar(100) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lab_a_systems`
--

CREATE TABLE `lab_a_systems` (
  `id` int(11) NOT NULL,
  `cpu_id` int(11) NOT NULL,
  `ups_id` int(11) NOT NULL,
  `monitor_id` int(11) NOT NULL,
  `internet` varchar(10) NOT NULL,
  `issue` varchar(255) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lab_a_upss`
--

CREATE TABLE `lab_a_upss` (
  `id` int(11) NOT NULL,
  `ups_id` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `lab_a_cpus`
--
ALTER TABLE `lab_a_cpus`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cpu_id` (`cpu_id`);

--
-- Indexes for table `lab_a_issues`
--
ALTER TABLE `lab_a_issues`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lab_a_logs`
--
ALTER TABLE `lab_a_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lab_a_monitors`
--
ALTER TABLE `lab_a_monitors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `monitor_id` (`monitor_id`);

--
-- Indexes for table `lab_a_stocks`
--
ALTER TABLE `lab_a_stocks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lab_a_systems`
--
ALTER TABLE `lab_a_systems`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cpu_id` (`cpu_id`),
  ADD KEY `ups_id` (`ups_id`),
  ADD KEY `monitor_id` (`monitor_id`);

--
-- Indexes for table `lab_a_upss`
--
ALTER TABLE `lab_a_upss`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ups_id` (`ups_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `lab_a_cpus`
--
ALTER TABLE `lab_a_cpus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lab_a_issues`
--
ALTER TABLE `lab_a_issues`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lab_a_logs`
--
ALTER TABLE `lab_a_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lab_a_monitors`
--
ALTER TABLE `lab_a_monitors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lab_a_stocks`
--
ALTER TABLE `lab_a_stocks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lab_a_systems`
--
ALTER TABLE `lab_a_systems`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lab_a_upss`
--
ALTER TABLE `lab_a_upss`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `lab_a_systems`
--
ALTER TABLE `lab_a_systems`
  ADD CONSTRAINT `lab_a_systems_ibfk_1` FOREIGN KEY (`cpu_id`) REFERENCES `lab_a_cpus` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lab_a_systems_ibfk_2` FOREIGN KEY (`ups_id`) REFERENCES `lab_a_upss` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lab_a_systems_ibfk_3` FOREIGN KEY (`monitor_id`) REFERENCES `lab_a_monitors` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
