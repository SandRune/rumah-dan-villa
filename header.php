<?php
// Mulai sesi
session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Koneksi database
    $host = "localhost";
    $user = "root";
    $pass = "";
    $db = "rumahdanvilla";

    $conn = new mysqli($host, $user, $pass, $db);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Hitung jumlah pesan di inbox
    $sql = "SELECT COUNT(*) AS unread_count FROM inbox WHERE user_id = $user_id";
    $result = $conn->query($sql);
    $unread_count = $result->fetch_assoc()['unread_count'];

    $conn->close();
}
?>
