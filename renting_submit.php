<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Content-Type: application/json");
    echo json_encode([
        "success" => false,
        "message" => "Access denied. Please log in first."
    ]);
    exit();
}

// Konfigurasi database
$host = "localhost";
$user = "root";
$pass = "";
$db = "rumahdanvilla";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    // Jika belum login, redirect ke halaman login
    header("Location: login.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $location = $conn->real_escape_string($_POST["location"]);
    $title = $conn->real_escape_string($_POST["title"]);
    $description = $conn->real_escape_string($_POST["description"]);
    $price = isset($_POST["price"]) ? floatval(str_replace([".", ","], "", $_POST["price"])) : 0;
    $propertyType = $conn->real_escape_string($_POST["propertyType"]);
    $guestrooms = intval($_POST["guestrooms"]);
    $bedrooms = intval($_POST["bedrooms"]);
    $beds = intval($_POST["beds"]);
    $bathrooms = intval($_POST["bathrooms"]);
    $amenities = isset($_POST["amenities"]) ? implode(", ", $_POST["amenities"]) : "No amenities listed";
    $mapIframe = $_POST["mapIframe"]; // Jangan gunakan htmlspecialchars di sini

    // Validasi iframe
    if (strpos($mapIframe, "<iframe") === false || strpos($mapIframe, "</iframe>") === false) {
        die("Invalid iframe input.");
    }

    $imageURLs = [];
    $uploadDir = "uploads/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    if (!empty($_FILES["propertyPictures"]["name"][0])) {
        foreach ($_FILES["propertyPictures"]["tmp_name"] as $key => $tmp_name) {
            $fileName = uniqid() . "_" . basename($_FILES["propertyPictures"]["name"][$key]);
            $filePath = $uploadDir . $fileName;

            if (move_uploaded_file($tmp_name, $filePath)) {
                $imageURLs[] = $filePath;
            } else {
                die("Gagal mengunggah file: " . $_FILES["propertyPictures"]["name"][$key]);
            }
        }
    }

    $images = implode(", ", $imageURLs);
    $firstImage = explode(", ", $images)[0];

    $sql = "INSERT INTO properties (location, title, description, price, propertyType, guestrooms, bedrooms, beds, bathrooms, amenities, images, map_location)
            VALUES ('$location', '$title', '$description', '$price', '$propertyType', '$guestrooms', '$bedrooms', '$beds', '$bathrooms', '$amenities', '$images', '$mapIframe')";

    if ($conn->query($sql) === TRUE) {
        $templateFile = "renting_template.html";
        if (!file_exists($templateFile)) {
            die("Template file $templateFile tidak ditemukan!");
        }

        $template = file_get_contents($templateFile);

        $template = str_replace("{{title}}", $title, $template);
        $template = str_replace("{{location}}", $location, $template);
        $template = str_replace("{{description}}", $description, $template);
        $template = str_replace("{{price}}", number_format($price, 0, ",", "."), $template);
        $template = str_replace("{{propertyType}}", $propertyType, $template);
        $template = str_replace("{{guestrooms}}", $guestrooms, $template);
        $template = str_replace("{{bedrooms}}", $bedrooms, $template);
        $template = str_replace("{{beds}}", $beds, $template);
        $template = str_replace("{{bathrooms}}", $bathrooms, $template);
        $template = str_replace("{{amenities}}", $amenities, $template);
        $template = str_replace("{{mapIframe}}", $mapIframe, $template); // Jangan encode lagi di sini
        $template = str_replace("{{images}}", $firstImage, $template);

        $newPageName = "reservation-" . uniqid() . ".html";
        if (!file_put_contents($newPageName, $template)) {
            die("Gagal membuat halaman reservasi baru.");
        }

        header("Location: $newPageName");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }

    $conn->close();
}
?>
