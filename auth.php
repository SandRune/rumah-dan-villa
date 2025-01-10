<?php
function authenticateUser($email, $password, $conn) {
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            return [
                "success" => true,
                "message" => "Login berhasil!",
                "user" => $user
            ];
        } else {
            return [
                "success" => false,
                "message" => "Password salah."
            ];
        }
    } else {
        return [
            "success" => false,
            "message" => "Email tidak terdaftar. Silakan Sign Up."
        ];
    }
}
?>
