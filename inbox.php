<?php
// Pastikan user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

$user_id = $_SESSION['user_id'];

// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "rumahdanvilla");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ambil pesan dari inbox
$sql = "SELECT * FROM inbox WHERE user_id = $user_id ORDER BY created_at DESC";
$result = $conn->query($sql);

$messages = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inbox</title>
    
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

        <div class="container mt-5">
            <h2>Inbox Messages</h2>
            <?php if (!empty($messages)): ?>
                <ul class="list-group">
                    <?php foreach ($messages as $message): ?>
                        <li class="list-group-item">
                            <p><?php echo $message['message']; ?></p>
                            <small class="text-muted">Sent on: <?php echo $message['created_at']; ?></small>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No messages yet.</p>
            <?php endif; ?>
        </div>

        <!-- Footer Start -->
        <div class="container-fluid bg-dark text-white-50 footer pt-5 mt-5 wow fadeIn" data-wow-delay="0.1s">
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
                    <div class="col-lg-3 col-md-6">
                        <h5 class="text-white mb-4">Contact</h5>
                        <p class="mb-2"><i class="fa fa-map-marker-alt me-3"></i>123 Street, New York, USA</p>
                        <p class="mb-2"><i class="fa fa-phone-alt me-3"></i>+012 345 67890</p>
                        <p class="mb-2"><i class="fa fa-envelope me-3"></i>info@example.com</p>
                        <div class="d-flex pt-2">
                            <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-twitter"></i></a>
                            <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-facebook-f"></i></a>
                            <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-youtube"></i></a>
                            <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <h5 class="text-white mb-4">Newsletter</h5>
                        <p>Dolor amet sit justo amet elitr clita ipsum elitr est.</p>
                        <div class="position-relative mx-auto" style="max-width: 400px;">
                            <input class="form-control bg-transparent w-100 py-3 ps-4 pe-5" type="text" placeholder="Your email">
                            <button type="button" class="btn btn-primary py-2 position-absolute top-0 end-0 mt-2 me-2">SignUp</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="copyright">
                    <div class="row">
                        <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                            &copy; <a class="border-bottom" href="#">HouseingnVilla</a>, All Right Reserved. 
						</div>

                        <div class="col-md-6 text-center text-md-end">
                            <div class="footer-menu">
                                <a href="">Home</a>
                                <a href="">Cookies</a>
                                <a href="">Help</a>
                                <a href="">FQAs</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer End -->


        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
    </div>
</body>
</html>
