-- Create main authentication database
CREATE DATABASE main_auth;
USE main_auth;

-- Users table for authentication
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL
);

-- Labs table to store lab information
CREATE TABLE labs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    lab_name VARCHAR(100) UNIQUE NOT NULL,
    database_name VARCHAR(100) UNIQUE NOT NULL
);

CREATE TABLE system_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    action VARCHAR(50) NOT NULL,
    ip_address VARCHAR(50),
    timestamp DATETIME DEFAULT CURRENT_TIMESTAMP
);


-- Example lab databases (lab_A, lab_B, lab_C)
CREATE DATABASE lab_A;
CREATE DATABASE lab_B;
CREATE DATABASE lab_C;
CREATE DATABASE lab_D;

-- Create tables for each lab database
-- Devices Tables
CREATE TABLE lab_A.lab_A_cpus (
    id INT AUTO_INCREMENT PRIMARY KEY,
    model VARCHAR(100) NOT NULL,
    serial_number VARCHAR(100) NOT NULL UNIQUE
);
CREATE TABLE lab_A.lab_A_upss (
    id INT AUTO_INCREMENT PRIMARY KEY,
    model VARCHAR(100) NOT NULL,
    serial_number VARCHAR(100) NOT NULL UNIQUE
);
CREATE TABLE lab_A.lab_A_monitors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    model VARCHAR(100) NOT NULL,
    serial_number VARCHAR(100) NOT NULL UNIQUE
);
CREATE TABLE lab_A.devices (
  `id` int(11) NOT NULL,
  `device_name` varchar(255) NOT NULL,
  `working_condition` enum('Good','Needs Repair','Not Working') DEFAULT 'Good',
  `time_added` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE lab_B.lab_B_cpus LIKE lab_A.lab_A_cpus;
CREATE TABLE lab_B.lab_B_upss LIKE lab_A.lab_A_upss;
CREATE TABLE lab_B.lab_B_monitors LIKE lab_A.lab_A_monitors;
CREATE TABLE lab_B.devices LIKE lab_A.devices;

CREATE TABLE lab_C.lab_C_cpus LIKE lab_A.lab_A_cpus;
CREATE TABLE lab_C.lab_C_upss LIKE lab_A.lab_A_upss;
CREATE TABLE lab_C.lab_C_monitors LIKE lab_A.lab_A_monitors;
CREATE TABLE lab_C.devices LIKE lab_A.devices;

CREATE TABLE lab_D.lab_D_cpus LIKE lab_A.lab_A_cpus;
CREATE TABLE lab_D.lab_D_upss LIKE lab_A.lab_A_upss;
CREATE TABLE lab_D.lab_D_monitors LIKE lab_A.lab_A_monitors;
CREATE TABLE lab_D.devices LIKE lab_A.devices;

-- Lab Systems Table
CREATE TABLE lab_A.lab_A_systems (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cpu_id INT NOT NULL,
    ups_id INT NOT NULL,
    monitor_id INT NOT NULL,
    internet VARCHAR(10) NOT NULL,
    issue VARCHAR(100),
    description TEXT,
    FOREIGN KEY (cpu_id) REFERENCES lab_A.lab_A_cpus(id) ON DELETE CASCADE,
    FOREIGN KEY (ups_id) REFERENCES lab_A.lab_A_upss(id) ON DELETE CASCADE,
    FOREIGN KEY (monitor_id) REFERENCES lab_A.lab_A_monitors(id) ON DELETE CASCADE
);
CREATE TABLE lab_B.lab_B_systems LIKE lab_A.lab_A_systems;
CREATE TABLE lab_C.lab_C_systems LIKE lab_A.lab_A_systems;
CREATE TABLE lab_D.lab_D_systems LIKE lab_A.lab_A_systems;
-- Lab Stocks Table
CREATE TABLE lab_A.lab_A_stocks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    item_name VARCHAR(100) NOT NULL,
    quantity INT NOT NULL
);
CREATE TABLE lab_B.lab_B_stocks LIKE lab_A.lab_A_stocks;
CREATE TABLE lab_C.lab_C_stocks LIKE lab_A.lab_A_stocks;
CREATE TABLE lab_D.lab_D_stocks LIKE lab_A.lab_A_stocks;

-- Lab Issues Table
CREATE TABLE lab_A.lab_A_issues (
    id INT AUTO_INCREMENT PRIMARY KEY,
    description TEXT NOT NULL,
    status ENUM('Pending', 'Resolved') DEFAULT 'Pending'
);
CREATE TABLE lab_B.lab_B_issues LIKE lab_A.lab_A_issues;
CREATE TABLE lab_C.lab_C_issues LIKE lab_A.lab_A_issues;
CREATE TABLE lab_D.lab_D_issues LIKE lab_A.lab_A_issues;

-- Lab System Logs Table
CREATE TABLE lab_A.lab_A_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    activity TEXT NOT NULL,
    username VARCHAR(50)
);
CREATE TABLE lab_B.lab_B_logs LIKE lab_A.lab_A_logs;
CREATE TABLE lab_C.lab_C_logs LIKE lab_A.lab_A_logs;
CREATE TABLE lab_D.lab_D_logs LIKE lab_A.lab_A_logs;