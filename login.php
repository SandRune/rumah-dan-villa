<?php
session_start();
include 'database_connection.php'; // Ganti dengan koneksi database Anda

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Cek apakah email ada di database
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Email ditemukan, verifikasi password
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            // Login berhasil
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            echo json_encode([
                "success" => true,
                "message" => "Login berhasil!"
            ]);
        } else {
            // Password salah
            echo json_encode([
                "success" => false,
                "message" => "Password salah."
            ]);
        }
    } else {
        // Email tidak ditemukan
        echo json_encode([
            "success" => false,
            "message" => "Email tidak terdaftar. Silakan Sign Up."
        ]);
    }

    $stmt->close();
    $conn->close();
}
?>
