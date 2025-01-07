<?php
// Database connection
$host = "localhost";
$user = "root";
$pass = "";
$db = "rumahdanvilla";

// Set zona waktu ke Jakarta (WIB)
date_default_timezone_set("Asia/Jakarta");


$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ambil ID dari URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Query untuk mendapatkan detail properti berdasarkan ID
$sql = "SELECT * FROM properties WHERE id = $id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    // Ambil data properti
    $title = isset($row['title']) ? $row['title'] : 'No Title';
    $images = isset($row['images']) ? $row['images'] : 'img/default.jpg'; // Default image jika tidak ada
    $location = isset($row['location']) ? $row['location'] : 'Unknown location';
    $price = isset($row['price']) ? $row['price'] : 'Contact for price';
    $description = isset($row['description']) ? $row['description'] : 'No description available';
    $propertyType = isset($row['propertyType']) ? $row['propertyType'] : 'Not specified';
    $guestrooms = isset($row['guestrooms']) ? $row['guestrooms'] : 'N/A';
    $bedrooms = isset($row['bedrooms']) ? $row['bedrooms'] : 'N/A';
    $beds = isset($row['beds']) ? $row['beds'] : 'N/A';
    $bathrooms = isset($row['bathrooms']) ? $row['bathrooms'] : 'N/A';
    $amenities = isset($row['amenities']) ? $row['amenities'] : 'No amenities listed';
    $map_location = isset($row['map_location']) ? $row['map_location'] : '<p>Map not available</p>'; // Menggunakan kolom map_location

    // Include template
    include 'renting_template.php';
} else {
    echo "<p>Property not found.</p>";
}

$conn->close();
?>
