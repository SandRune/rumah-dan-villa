<?php
session_start(); // Mulai sesi PHP

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

// Set zona waktu ke Jakarta (WIB)
date_default_timezone_set("Asia/Jakarta");


// Ambil ID properti dari URL
$property_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Database connection
$host = "localhost";
$user = "root";
$pass = "";
$db = "rumahdanvilla";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ambil detail properti
$sql = "SELECT * FROM properties WHERE id = $property_id";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $property = $result->fetch_assoc();
} else {
    die("Property not found.");
}

// Ambil daftar tanggal yang sudah dipesan untuk properti ini
$sql = "SELECT start_date, end_date FROM reservations WHERE property_id = $property_id AND status = 'Approved'";
$reserved_dates = $conn->query($sql);
$reserved_ranges = [];
if ($reserved_dates->num_rows > 0) {
    while ($row = $reserved_dates->fetch_assoc()) {
        $reserved_ranges[] = [
            'start' => $row['start_date'],
            'end' => $row['end_date']
        ];
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Reserve <?php echo $property['title']; ?></title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <style>
        .container {
            max-width: 800px;
            margin: 30px auto;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .form-section {
            margin-bottom: 20px;
        }
        .form-section h3 {
            font-size: 1.5rem;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Reserve: <?php echo $property['title']; ?></h1>
        <form action="process_reservation.php" method="POST">
            <!-- Properti yang Dipilih -->
            <div class="form-section">
                <h3>Selected Property</h3>
                <p><strong><?php echo $property['title']; ?></strong></p>
                <p><?php echo $property['description']; ?></p>
                <input type="hidden" name="property_id" value="<?php echo $property['id']; ?>">
            </div>

            <!-- Pemilihan Tanggal -->
            <div class="form-section">
                <h3>Select Dates</h3>
                <label for="start_date">Start Date:</label>
                <input type="date" id="start_date" name="start_date" class="form-control" required>
                <label for="end_date" style="margin-top: 10px;">End Date:</label>
                <input type="date" id="end_date" name="end_date" class="form-control" required>
            </div>

            <!-- Jumlah Orang -->
            <div class="form-section">
                <h3>Number of Guests</h3>
                <label for="guests">Guests:</label>
                <input type="number" id="guests" name="guests" class="form-control" min="1" max="20" required>
            </div>

            <!-- total price -->
            <div class="form-section">
                <h3>Price Estimate</h3>
                <p id="price_estimate">Total Price: Rp. 0 (for 0 nights)</p>
            </div>


            <!-- Tombol Submit -->
            <button type="submit" class="btn btn-primary w-100">Submit Reservation</button>
        </form>
    </div>

    <script>
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    const priceEstimate = document.getElementById('price_estimate');

    const pricePerNight = <?php echo $property['price']; ?>; // Harga per malam dari database

    function calculatePrice() {
        const startDate = new Date(startDateInput.value);
        const endDate = new Date(endDateInput.value);

        // Validasi jika tanggal sudah dipilih
        if (startDate && endDate) {
            if (endDate < startDate) {
                // Pop-up jika End Date lebih kecil dari Start Date
                alert("End Date cannot be earlier than Start Date. Please select a valid date range.");
                endDateInput.value = ""; // Reset nilai End Date
                priceEstimate.textContent = "Total Price: Rp. 0 (for 0 nights)";
                return; // Keluar dari fungsi jika tanggal tidak valid
            }

            const totalDays = (endDate - startDate) / (1000 * 60 * 60 * 24) + 1; // Hitung jumlah hari
            const totalPrice = totalDays * pricePerNight; // Hitung total harga

            // Tampilkan estimasi harga
            priceEstimate.textContent = `Total Price: Rp. ${totalPrice.toLocaleString('id-ID')} (for ${totalDays} nights)`;
        } else {
            // Reset estimasi jika tanggal tidak valid
            priceEstimate.textContent = "Total Price: Rp. 0 (for 0 nights)";
        }
    }

    // Event listener untuk input tanggal
    startDateInput.addEventListener('change', calculatePrice);
    endDateInput.addEventListener('change', calculatePrice);
    </script>


</body>
</html>
