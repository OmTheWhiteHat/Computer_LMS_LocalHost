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

--
-- Dumping data for table `lab_a_issues`
--

INSERT INTO `lab_a_issues` (`id`, `device_type`, `description`, `status`) VALUES
(3, 'CPU', 'not working', 'Resolved'),
(4, 'CPU', 'not working', 'Resolved');

-- --------------------------------------------------------

--
-- Table structure for table `lab_a_locations`
--

CREATE TABLE `lab_a_locations` (
  `id` int(11) NOT NULL,
  `lab_name` varchar(255) NOT NULL,
  `building` varchar(255) NOT NULL,
  `floor` varchar(50) NOT NULL,
  `room_number` varchar(50) NOT NULL,
  `lab_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lab_a_locations`
--

INSERT INTO `lab_a_locations` (`id`, `lab_name`, `building`, `floor`, `room_number`, `lab_id`) VALUES
(1, 'CS-01', '1st floor', '1st floor', '101', 'lab_A');

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
-- Table structure for table `lab_a_qr_scans`
--

CREATE TABLE `lab_a_qr_scans` (
  `id` int(11) NOT NULL,
  `system_id` int(11) NOT NULL,
  `scan_time` timestamp NOT NULL DEFAULT current_timestamp()
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

--
-- Dumping data for table `lab_a_stocks`
--

INSERT INTO `lab_a_stocks` (`id`, `item_name`, `quantity`) VALUES
(1, 'CPU', 2);

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
-- Indexes for table `devices`
--
ALTER TABLE `devices`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `lab_a_locations`
--
ALTER TABLE `lab_a_locations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_lab_id` (`lab_id`);

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
-- Indexes for table `lab_a_qr_scans`
--
ALTER TABLE `lab_a_qr_scans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `system_id` (`system_id`);

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
-- AUTO_INCREMENT for table `devices`
--
ALTER TABLE `devices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lab_a_cpus`
--
ALTER TABLE `lab_a_cpus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lab_a_issues`
--
ALTER TABLE `lab_a_issues`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `lab_a_locations`
--
ALTER TABLE `lab_a_locations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
-- AUTO_INCREMENT for table `lab_a_qr_scans`
--
ALTER TABLE `lab_a_qr_scans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lab_a_stocks`
--
ALTER TABLE `lab_a_stocks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `lab_a_upss`
--
ALTER TABLE `lab_a_upss`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `lab_a_locations`
--
ALTER TABLE `lab_a_locations`
  ADD CONSTRAINT `fk_lab_id` FOREIGN KEY (`lab_id`) REFERENCES `main_auth`.`labs` (`database_name`) ON DELETE CASCADE;

--
-- Constraints for table `lab_a_qr_scans`
--
ALTER TABLE `lab_a_qr_scans`
  ADD CONSTRAINT `lab_a_qr_scans_ibfk_1` FOREIGN KEY (`system_id`) REFERENCES `lab_a_systems` (`id`) ON DELETE CASCADE;

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
