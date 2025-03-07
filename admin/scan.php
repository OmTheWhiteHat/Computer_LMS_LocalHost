<?php
session_start();
include '../include/db.php';

if (!isset($_SESSION['username']) || !isset($_GET['lab'])) {
    header("Location: ../public/login.php");
    exit();
}

$lab = $_GET['lab'];
$conn->select_db($lab);

// Create table to store scanned QR data
$conn->query("CREATE TABLE IF NOT EXISTS `{$lab}_qr_scans` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    system_id INT NOT NULL,
    scan_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (system_id) REFERENCES `{$lab}_systems`(id) ON DELETE CASCADE
)");

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['qr_data'])) {
    $system_id = intval($_POST['qr_data']);
    
    // Insert scan record
    $conn->query("INSERT INTO `{$lab}_qr_scans` (system_id) VALUES ('$system_id')");
    
    // Fetch system details
    $result = $conn->query("SELECT s.*, c.cpu_id, u.ups_id, m.monitor_id FROM `{$lab}_systems` s
                            JOIN `{$lab}_cpus` c ON s.cpu_id = c.id
                            JOIN `{$lab}_upss` u ON s.ups_id = u.id
                            JOIN `{$lab}_monitors` m ON s.monitor_id = m.id
                            WHERE s.id = '$system_id'");
    
    $system = $result->fetch_assoc();
    echo json_encode($system);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scan QR Code - <?php echo htmlspecialchars($lab); ?></title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/instascan/1.0.0/instascan.min.js"></script>
</head>
<body>
    <h2>Scan QR Code for System Information</h2>
    <button id="startScan" style="margin-bottom: 10px;">Start Scanning</button>
    <video id="preview" style="width: 100%; max-width: 500px; display: none;"></video>
    <h3>System Information:</h3>
    <div id="system_info"></div>
    <a href="lab_panel.php?lab=<?php echo $lab; ?>" class="back-btn"><i class="fas fa-arrow-left"></i> Back to Lab Panel</a>
    <script>
        let scanner = new Instascan.Scanner({ video: document.getElementById('preview') });
        scanner.addListener('scan', function (content) {
            fetch('', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'qr_data=' + encodeURIComponent(content)
            })
            .then(response => response.json())
            .then(data => {
                if (data) {
                    document.getElementById('system_info').innerHTML = `<p><strong>CPU ID:</strong> ${data.cpu_id}</p>
                                                                        <p><strong>UPS ID:</strong> ${data.ups_id}</p>
                                                                        <p><strong>Monitor ID:</strong> ${data.monitor_id}</p>
                                                                        <p><strong>Internet:</strong> ${data.internet}</p>
                                                                        <p><strong>Issue:</strong> ${data.issue}</p>
                                                                        <p><strong>Description:</strong> ${data.description}</p>`;
                } else {
                    document.getElementById('system_info').innerHTML = '<p style="color: red;">System not found.</p>';
                }
            });
        });

        document.getElementById('startScan').addEventListener('click', function () {
            Instascan.Camera.getCameras().then(function (cameras) {
                if (cameras.length > 0) {
                    document.getElementById('preview').style.display = 'block';
                    scanner.start(cameras[0]);
                } else {
                    alert('No cameras found.');
                }
            }).catch(function (e) {
                console.error(e);
            });
        });
    </script>
</body>
</html>
