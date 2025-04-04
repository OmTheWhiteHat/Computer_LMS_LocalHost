CREATE DATABASE lab_B;
CREATE DATABASE lab_C;
CREATE DATABASE lab_D;

-- Lab Devices
CREATE TABLE lab_B.devices LIKE lab_A.devices;
CREATE TABLE lab_C.devices LIKE lab_A.devices;
CREATE TABLE lab_D.devices LIKE lab_A.devices;

-- Lab CPUS
CREATE TABLE lab_B.lab_B_cpus LIKE lab_A.lab_A_cpus;
CREATE TABLE lab_C.lab_C_cpus LIKE lab_A.lab_A_cpus;
CREATE TABLE lab_D.lab_D_cpus LIKE lab_A.lab_A_cpus;

-- Lab Issues
CREATE TABLE lab_B.lab_B_issues LIKE lab_A.lab_A_issues;
CREATE TABLE lab_C.lab_C_issues LIKE lab_A.lab_A_issues;
CREATE TABLE lab_D.lab_D_issues LIKE lab_A.lab_A_issues;

-- Lab Location
CREATE TABLE lab_B.lab_B_locations LIKE lab_A.lab_A_locations;
CREATE TABLE lab_C.lab_C_locations LIKE lab_A.lab_A_locations;
CREATE TABLE lab_D.lab_D_locations LIKE lab_A.lab_A_locations;

-- Lab Monitors
CREATE TABLE lab_B.lab_B_monitors LIKE lab_A.lab_A_monitors;
CREATE TABLE lab_C.lab_C_monitors LIKE lab_A.lab_A_monitors;
CREATE TABLE lab_D.lab_D_monitors LIKE lab_A.lab_A_monitors;

-- Lab QR Scans
CREATE TABLE lab_B.lab_B_qr_scans LIKE lab_A.lab_A_qr_scans;
CREATE TABLE lab_C.lab_C_qr_scans LIKE lab_A.lab_A_qr_scans;
CREATE TABLE lab_D.lab_D_qr_scans LIKE lab_A.lab_A_qr_scans;

-- Lab Stocks
CREATE TABLE lab_B.lab_B_stocks LIKE lab_A.lab_A_stocks;
CREATE TABLE lab_C.lab_C_stocks LIKE lab_A.lab_A_stocks;
CREATE TABLE lab_D.lab_D_stocks LIKE lab_A.lab_A_stocks;

-- Lab Systems
CREATE TABLE lab_B.lab_B_systems LIKE lab_A.lab_A_systems;
CREATE TABLE lab_C.lab_C_systems LIKE lab_A.lab_A_systems;
CREATE TABLE lab_D.lab_D_systems LIKE lab_A.lab_A_systems;

-- Lab UPS
CREATE TABLE lab_B.lab_B_upss LIKE lab_A.lab_A_upss;
CREATE TABLE lab_C.lab_C_upss LIKE lab_A.lab_A_upss;
CREATE TABLE lab_D.lab_D_upss LIKE lab_A.lab_A_upss;
