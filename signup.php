<?php
header('Content-Type: application/json'); // Ensure JSON response

// Database credentials
$servername = "localhost";
$username = "root"; // Or your custom user
$password = ""; // Or your custom password
$dbname = "rumahdanvilla";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Database connection failed: " . $conn->connect_error]);
    exit;
}

// Handle signup form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["email"]) && isset($_POST["password"])) {
        $email = $_POST["email"];
        $password = password_hash($_POST["password"], PASSWORD_BCRYPT);

        $sql = "INSERT INTO users (email, password) VALUES ('$email', '$password')";

        if ($conn->query($sql) === TRUE) {
            echo json_encode(["success" => true, "message" => "Signup successful!"]);
        } else {
            echo json_encode(["success" => false, "message" => "Signup failed: " . $conn->error]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Invalid form data!"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method!"]);
}

$conn->close();
?>
