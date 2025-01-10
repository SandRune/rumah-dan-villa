<?php
function validateIframe($iframe) {
    return strpos($iframe, "<iframe") !== false && strpos($iframe, "</iframe>") !== false;
}

function uploadImages($files, $uploadDir) {
    $imageURLs = [];

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    foreach ($files["tmp_name"] as $key => $tmp_name) {
        $fileName = uniqid() . "_" . basename($files["name"][$key]);
        $filePath = $uploadDir . $fileName;

        if (move_uploaded_file($tmp_name, $filePath)) {
            $imageURLs[] = $filePath;
        } else {
            throw new Exception("Gagal mengunggah file: " . $files["name"][$key]);
        }
    }

    return $imageURLs;
}

function insertProperty($conn, $data) {
    $sql = "INSERT INTO properties (location, title, description, price, propertyType, guestrooms, bedrooms, beds, bathrooms, amenities, images, map_location)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "sssdsiisssss",
        $data["location"],
        $data["title"],
        $data["description"],
        $data["price"],
        $data["propertyType"],
        $data["guestrooms"],
        $data["bedrooms"],
        $data["beds"],
        $data["bathrooms"],
        $data["amenities"],
        $data["images"],
        $data["map_location"]
    );

    if (!$stmt->execute()) {
        throw new Exception("Gagal menyimpan properti: " . $stmt->error);
    }

    return $conn->insert_id;
}
?>
