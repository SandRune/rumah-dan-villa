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
$reason = $_POST['reason'] ?? 'No reason provided';

$sql = "SELECT r.*, u.id AS user_id, p.title AS property_name 
        FROM reservations r
        JOIN users u ON r.user_id = u.id
        JOIN properties p ON r.property_id = p.id
        WHERE r.id = $reservation_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $reservation = $result->fetch_assoc();
    $user_id = $reservation['user_id'];
    $property_name = $reservation['property_name'];
    $start_date = $reservation['start_date'];
    $end_date = $reservation['end_date'];

    if ($action === 'approve') {
        // Update status menjadi Approved
        $sql = "UPDATE reservations SET status = 'Approved' WHERE id = $reservation_id";
        $conn->query($sql);

        // Tambahkan pesan ke inbox pengguna
        $message = "Reservation approved! Your reservation for $property_name from $start_date to $end_date has been approved. Enjoy your stay!";
    } elseif ($action === 'reject') {
        // Update status menjadi Rejected
        $sql = "UPDATE reservations SET status = 'Rejected', receipt = CONCAT(receipt, '\nRejection Reason: ', '$reason') WHERE id = $reservation_id";
        $conn->query($sql);

        // Tambahkan pesan ke inbox pengguna
        $message = "Reservation rejected. Reason: $reason.";
    }

    // Simpan pesan ke tabel messages
    $sql = "INSERT INTO messages (user_id, reservation_id, message) VALUES ($user_id, $reservation_id, '$message')";
    $conn->query($sql);
}

$conn->close();
header("Location: admin_dashboard.php");
exit;
?>
