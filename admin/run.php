<?php 
include '../include/db.php';
$lab = $_GET['lab'];
$conn->select_db($lab);

$conn->query("CREATE TABLE IF NOT EXISTS `{$lab}_systems` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cpu_id INT NOT NULL,
    ups_id INT NOT NULL,
    monitor_id INT NOT NULL,
    internet VARCHAR(10) NOT NULL,
    issue VARCHAR(255) NOT NULL,
    description TEXT,
    FOREIGN KEY (cpu_id) REFERENCES `{$lab}_cpus`(id) ON DELETE CASCADE,
    FOREIGN KEY (ups_id) REFERENCES `{$lab}_upss`(id) ON DELETE CASCADE,
    FOREIGN KEY (monitor_id) REFERENCES `{$lab}_monitors`(id) ON DELETE CASCADE
)");
?>