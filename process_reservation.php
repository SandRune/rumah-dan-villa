<?php
session_start(); // Mulai sesi PHP

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

// Set zona waktu ke Jakarta (WIB)
date_default_timezone_set("Asia/Jakarta");

// Database connection
$host = "localhost";
$user = "root";
$pass = "";
$db = "rumahdanvilla";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ambil data dari form
$property_id = isset($_POST['property_id']) ? intval($_POST['property_id']) : 0;
$start_date = isset($_POST['start_date']) ? $_POST['start_date'] : '';
$end_date = isset($_POST['end_date']) ? $_POST['end_date'] : '';
$guests = isset($_POST['guests']) ? intval($_POST['guests']) : 0;

// Validasi data
if (empty($property_id) || empty($start_date) || empty($end_date) || $guests <= 0) {
    die("Invalid reservation details. Please go back and fill the form correctly.");
}

// Validasi tanggal akhir tidak lebih kecil dari tanggal awal
if (new DateTime($end_date) < new DateTime($start_date)) {
    echo "<script>
        alert('End Date cannot be earlier than Start Date. Please go back and correct your selection.');
        window.history.back(); // Kembali ke halaman sebelumnya
    </script>";
    exit;
}

// Validasi apakah tanggal bertabrakan dengan reservasi yang sudah ada
$sql = "SELECT * FROM reservations 
        WHERE property_id = $property_id 
        AND status IN ('Pending', 'Approved') 
        AND (
            (start_date <= '$end_date' AND end_date >= '$start_date')
        )";
$overlap_check = $conn->query($sql);

if ($overlap_check->num_rows > 0) {
    echo "<script>
        alert('The selected dates are already reserved. Please choose different dates.');
        window.history.back(); // Kembali ke halaman sebelumnya
    </script>";
    exit;
}

// Ambil detail properti dari database
$sql = "SELECT * FROM properties WHERE id = $property_id";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $property = $result->fetch_assoc();
    $price_per_night = $property['price'];
    $property_image = $property['images'];
} else {
    die("Property not found.");
}

// Hitung jumlah hari
$start_date_obj = new DateTime($start_date);
$end_date_obj = new DateTime($end_date);
$interval = $start_date_obj->diff($end_date_obj);
$total_days = $interval->days + 1; // Tambahkan 1 hari untuk menyertakan tanggal akhir

// Hitung total harga
$total_price = $total_days * $price_per_night;

// Simpan reservasi ke database
$user_id = $_SESSION['user_id'];
$sql = "INSERT INTO reservations (user_id, property_id, start_date, end_date, guests, total_price, status) 
        VALUES ($user_id, $property_id, '$start_date', '$end_date', $guests, $total_price, 'Pending')";

if ($conn->query($sql) !== TRUE) {
    die("Error saving reservation: " . $conn->error);
}

// Dapatkan ID reservasi terbaru dan waktu pembelian
$reservation_id = $conn->insert_id;
$purchase_date = date("Y-m-d H:i:s");

// Buat struk pembelian
$receipt = "Reservation ID: $reservation_id\n"
    . "Property: {$property['title']}\n"
    . "Location: {$property['location']}\n"
    . "Start Date: $start_date\n"
    . "End Date: $end_date\n"
    . "Guests: $guests\n"
    . "Total Price: Rp. " . number_format($total_price, 0, ',', '.') . "\n"
    . "Purchase Date: $purchase_date";

// Update struk di database
$sql = "UPDATE reservations SET receipt = '$receipt', purchase_date = '$purchase_date' WHERE id = $reservation_id";
$conn->query($sql);

$conn->close();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Reservation Confirmation</title>

    <!-- Favicon!!! -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Inter:wght@700;800&display=swap" rel="stylesheet">
    
    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">

    <style>
        .reservation-container {
            text-align: center;
            margin: 50px auto;
            padding: 20px;
            max-width: 600px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            background-color: #fff;
        }
        .reservation-image {
            width: 250px;
            height: 250px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .reservation-text {
            font-size: 1.2rem;
            margin-bottom: 10px;
            color: #333;
        }
        .reservation-receipt {
            white-space: pre-line;
            text-align: left;
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            font-family: monospace;
        }
        .dropdown-menu .btn {
            display: inline-flex;
            justify-content: center; /* Teks di tengah horizontal */
            align-items: center; /* Teks di tengah vertikal */
            width: 100px; /* Lebar tombol lebih kecil */
            height: 35px; /* Tinggi tombol lebih kecil */
            font-size: 12px; /* Ukuran teks lebih kecil */
            font-weight: bold;
            border-radius: 5px;
            margin: 5px; /* Jarak antar tombol */
            padding: 0; /* Menghapus padding default */
            text-align: center; /* Tambahan keamanan */
        }
    </style>
</head>
<body>
    <div class="container-xxl bg-white p-0">
        <!-- Navbar Start -->
        <nav class="navbar navbar-expand-lg bg-white navbar-light shadow sticky-top p-0">
            <a href="index.php" class="navbar-brand d-flex align-items-center text-center py-0 px-4 px-lg-5">
                <h1 class="m-0 text-primary">Housing n' Villas</h1>
            </a>
            <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav ms-auto p-4 p-lg-0">
                    <a href="index.php" class="nav-item nav-link">Home</a>
                    <a href="stays.php" class="nav-item nav-link">Stays</a>
                    <a href="inbox.php" class="nav-item nav-link">Inbox</a>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" id="profileDropdown" data-bs-toggle="dropdown">
                            Profile
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <!-- Jika belum login -->
                            <div id="not-logged-in">
                                <p class="dropdown-item text-center">Please Login or Register First to proceed!</p>
                                <div class="d-flex justify-content-around">
                                    <a href="signup.html" class="btn btn-primary btn-sm">Sign Up</a>
                                    <a href="login.html" class="btn btn-primary btn-sm">Login</a>
                                </div>
                            </div>
                            
            
                            <!-- Jika sudah login -->
                            <div id="logged-in">
                                <p class="dropdown-item text-center">Welcome, <?php echo htmlspecialchars($_SESSION['user_email']); ?>!</p>
                                <div class="d-flex justify-content-around">
                                    <a href="update_profile.php" class="btn btn-primary btn-sm">Update Profile</a>
                                    <a action="logout.php" class="btn btn-primary btn-sm">Logout</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <a href="renting.php" class="btn btn-primary rounded-0 py-4 px-lg-5 d-none d-lg-block">Try Renting<i class="fa fa-arrow-right ms-3"></i></a>
            </div>
            
        </nav>
        <!-- Navbar End -->

        <!-- Pesan Konfirmasi Reservasi -->
        <div class="reservation-container">
            <h1>Reservation Successful</h1>
            <img src="<?php echo $property_image; ?>" alt="Property Image" class="reservation-image">
            <p class="reservation-text">Peminapan: <?php echo $start_date; ?> - <?php echo $end_date; ?></p>
            <p class="reservation-text">Untuk <?php echo $guests; ?> orang</p>
            <p class="reservation-text">Total Price: Rp. <?php echo number_format($total_price, 0, ',', '.'); ?></p>
            <p class="reservation-text">Purchase Date: <?php echo $purchase_date; ?></p>
            <div class="reservation-receipt">
                <?php echo $receipt; ?>
            </div>
            <a href="stays.php" class="btn btn-primary mt-3">Back to Stays</a>
        </div>


        <!-- Footer Start -->
        <div class="container-fluid bg-dark text-white-50 footer pt-5 mt-5">
            <div class="container py-5">
                <div class="row g-5">
                    <div class="col-lg-3 col-md-6">
                        <h5 class="text-white mb-4">Company</h5>
                        <a class="btn btn-link text-white-50" href="">About Us</a>
                        <a class="btn btn-link text-white-50" href="">Contact Us</a>
                        <a class="btn btn-link text-white-50" href="">Our Services</a>
                        <a class="btn btn-link text-white-50" href="">Privacy Policy</a>
                        <a class="btn btn-link text-white-50" href="">Terms & Condition</a>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <h5 class="text-white mb-4">Quick Links</h5>
                        <a class="btn btn-link text-white-50" href="">About Us</a>
                        <a class="btn btn-link text-white-50" href="">Contact Us</a>
                        <a class="btn btn-link text-white-50" href="">Our Services</a>
                        <a class="btn btn-link text-white-50" href="">Privacy Policy</a>
                        <a class="btn btn-link text-white-50" href="">Terms & Condition</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer End -->
    </div>

    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
