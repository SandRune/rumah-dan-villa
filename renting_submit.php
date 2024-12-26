<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Konfigurasi database
$host = "localhost";
$user = "root";
$pass = "";
$db = "rumahdanvilla";

// Koneksi ke database
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari formulir
    $location = $conn->real_escape_string($_POST["location"]);
    $title = $conn->real_escape_string($_POST["title"]);
    $description = $conn->real_escape_string($_POST["description"]);
    $price = isset($_POST["price"]) ? floatval(str_replace([".", ","], "", $_POST["price"])) : 0;
    $propertyType = $conn->real_escape_string($_POST["propertyType"]);
    $guestrooms = intval($_POST["guestrooms"]);
    $bedrooms = intval($_POST["bedrooms"]);
    $beds = intval($_POST["beds"]);
    $bathrooms = intval($_POST["bathrooms"]);
    $amenities = isset($_POST["amenities"]) ? implode(", ", $_POST["amenities"]) : "";
    $mapIframe = $conn->real_escape_string($_POST["mapIframe"]);

    // Proses upload file gambar
    $imageURLs = [];
    $uploadDir = "uploads/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true); // Buat folder jika belum ada
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

    // Simpan data ke database
    $sql = "INSERT INTO properties (location, title, description, price, propertyType, guestrooms, bedrooms, beds, bathrooms, amenities, images, map_location)
            VALUES ('$location', '$title', '$description', '$price', '$propertyType', '$guestrooms', '$bedrooms', '$beds', '$bathrooms', '$amenities', '$images', '$mapIframe')";

    if ($conn->query($sql) === TRUE) {
        // Buat halaman reservasi baru
        $templateFile = "renting_template.html";
        if (!file_exists($templateFile)) {
            die("Template file $templateFile tidak ditemukan!");
        }

        $template = file_get_contents($templateFile);

        // Ganti placeholder di template dengan data properti
        $template = str_replace("{{title}}", $title, $template);
        $template = str_replace("{{location}}", $location, $template);
        $template = str_replace("{{description}}", $description, $template);
        $template = str_replace("{{price}}", number_format($price, 0, ",", "."), $template);
        $template = str_replace("{{propertyType}}", $propertyType, $template);
        $template = str_replace("{{amenities}}", $amenities, $template);
        $template = str_replace("{{guestrooms}}", $guestrooms, $template);
        $template = str_replace("{{bedrooms}}", $bedrooms, $template);
        $template = str_replace("{{beds}}", $beds, $template);
        $template = str_replace("{{bathrooms}}", $bathrooms, $template);
        $template = str_replace("{{images}}", explode(", ", $images)[0], $template); // Gunakan gambar pertama
        $template = str_replace("{{mapIframe}}", $mapIframe, $template);

        $newPageName = "reservation-" . uniqid() . ".html";
        file_put_contents($newPageName, $template);

        // Tambahkan properti baru ke stays.html
        $staysFile = "stays.html";
        $newGalleryItem = "
            <div class='gallery-item'>
                <a href='$newPageName'>
                    <img src='" . explode(", ", $images)[0] . "' alt='$title'>
                    <div class='gallery-text'>
                        <div class='hotel-info'>
                            <span class='hotel-name'>$title</span>
                            <span class='hotel-rating'>⭐5.0</span>
                        </div>
                        <p>$description</p>
                    </div>
                </a>
            </div>
        ";

        // Sisipkan galeri baru di stays.html
        if (file_exists($staysFile)) {
            $staysContent = file_get_contents($staysFile);
            $staysContent = str_replace("<!-- NEW_PROPERTIES_HERE -->", $newGalleryItem . "\n<!-- NEW_PROPERTIES_HERE -->", $staysContent);
            file_put_contents($staysFile, $staysContent);
        }

        // Redirect ke halaman reservasi baru
        header("Location: $newPageName");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }

    $conn->close();
}
?>
