<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit;
}

// Koneksi database
$host = "localhost";
$user = "root";
$pass = "";
$db = "rumahdanvilla";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ambil reservasi dengan status "Pending" termasuk email user
$sql = "SELECT r.id, r.property_id, r.start_date, r.end_date, r.guests, r.total_price, p.title, u.email 
        FROM reservations r
        JOIN properties p ON r.property_id = p.id
        JOIN users u ON r.user_id = u.id
        WHERE r.status = 'Pending'";
$reservations = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link href="img/favicon.ico" rel="icon">
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Inter:wght@700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
    <div class="container-xxl bg-white p-0">
        <!-- Navbar Start -->
        <nav class="navbar navbar-expand-lg bg-white navbar-light shadow sticky-top p-0">
            <div class="d-flex justify-content-between align-items-center w-100">
                <!-- Housing n' Villas di kiri -->
                <a href="index.html" class="navbar-brand d-flex align-items-center text-center py-0 px-4 px-lg-5">
                    <h1 class="m-0 text-primary">Housing n' Villas</h1>
                </a>

                <!-- Administration di kanan -->
                <a class="navbar-brand d-flex align-items-center text-center py-0 px-4 px-lg-5 ms-auto">
                    <h1 class="m-0 text-primary">Administration</h1>
                </a>
            </div>
            <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
        </nav>
        <!-- Navbar End -->

        <div class="container mt-5">
            <h1 class="text-center">Admin Dashboard</h1>
            <table class="table mt-3">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Email (pembeli)</th>
                        <th>Property</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Guests</th>
                        <th>Total Price</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $reservations->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td><?php echo $row['title']; ?></td>
                            <td><?php echo $row['start_date']; ?></td>
                            <td><?php echo $row['end_date']; ?></td>
                            <td><?php echo $row['guests']; ?></td>
                            <td>Rp. <?php echo number_format($row['total_price'], 0, ',', '.'); ?></td>
                            <td>
                                <!-- Approve Form -->
                                <form action="admin_process.php" method="POST" class="d-inline">
                                    <input type="hidden" name="reservation_id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" name="action" value="approve" class="btn btn-success">Approve</button>
                                </form>
                                <!-- Reject Form -->
                                <form action="admin_process.php" method="POST" class="d-inline">
                                    <input type="hidden" name="reservation_id" value="<?php echo $row['id']; ?>">
                                    <textarea name="reason" rows="1" placeholder="Reason" class="form-control mb-2" required></textarea>
                                    <button type="submit" name="action" value="reject" class="btn btn-danger">Reject</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
