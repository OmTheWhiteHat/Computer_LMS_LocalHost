<?php
session_start();
include '../include/db.php';

if (!isset($_SESSION['username']) || !isset($_GET['lab'])) {
    header("Location: ../public/login.php");
    exit();
}

$lab = $_GET['lab'];
$conn->select_db($lab);

// Create table if not exists
$conn->query("CREATE TABLE IF NOT EXISTS `{$lab}_locations` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    lab_name VARCHAR(255) NOT NULL,
    building VARCHAR(255) NOT NULL,
    floor VARCHAR(50) NOT NULL,
    room_number VARCHAR(50) NOT NULL
)");

// Fetch existing location data
$result = $conn->query("SELECT * FROM `{$lab}_locations` LIMIT 1");
$location = $result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $lab_name = $_POST['lab_name'];
    $building = $_POST['building'];
    $floor = $_POST['floor'];
    $room_number = $_POST['room_number'];
    
    if ($location) {
        $conn->query("UPDATE `{$lab}_locations` SET lab_name='$lab_name', building='$building', floor='$floor', room_number='$room_number' WHERE id='{$location['id']}'");
    } else {
        $conn->query("INSERT INTO `{$lab}_locations` (lab_name, building, floor, room_number) VALUES ('$lab_name', '$building', '$floor', '$room_number')");
    }
    header("Location: lab_location.php?lab=$lab");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab Location - <?php echo htmlspecialchars($lab); ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Lab Location Information</h2>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Lab Name:</label>
                <input type="text" name="lab_name" class="form-control" value="<?php echo htmlspecialchars($location['lab_name'] ?? ''); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Building:</label>
                <input type="text" name="building" class="form-control" value="<?php echo htmlspecialchars($location['building'] ?? ''); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Floor:</label>
                <input type="text" name="floor" class="form-control" value="<?php echo htmlspecialchars($location['floor'] ?? ''); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Room Number:</label>
                <input type="text" name="room_number" class="form-control" value="<?php echo htmlspecialchars($location['room_number'] ?? ''); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Save Location</button>
        </form>
        <div class="mt-3">
            <a href="lab_panel.php?lab=<?php echo $lab; ?>" class="btn btn-secondary">Back to Lab Panel</a>
        </div>
    </div>
</body>
</html>
