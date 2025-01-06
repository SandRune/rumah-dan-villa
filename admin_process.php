<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit;
}

$host = "localhost";
$user = "root";
$pass = "";
$db = "rumahdanvilla";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$reservation_id = $_POST['reservation_id'];
$action = $_POST['action'];

if ($action === 'approve') {
    $sql = "UPDATE reservations SET status = 'Approved' WHERE id = $reservation_id";
} elseif ($action === 'reject') {
    $reason = $_POST['reason'] ?? 'No reason provided';
    $sql = "UPDATE reservations SET status = 'Rejected', receipt = CONCAT(receipt, '\nRejection Reason: ', '$reason') WHERE id = $reservation_id";
}

if ($conn->query($sql) === TRUE) {
    header("Location: admin_dashboard.php");
} else {
    echo "Error updating reservation: " . $conn->error;
}

$conn->close();
?>
