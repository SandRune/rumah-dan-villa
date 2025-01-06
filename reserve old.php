<?php
// Database connection
$host = "localhost";
$user = "root";
$pass = "";
$db = "rumahdanvilla";

$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ambil ID properti dari URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Ambil detail properti berdasarkan ID
$sql = "SELECT * FROM properties WHERE id = $id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    die("Property not found.");
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Reserve <?php echo $row['title']; ?></title>
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
        <h1>Reserve: <?php echo $row['title']; ?></h1>
        <form action="process_reservation.php" method="POST">
            <!-- Properti yang Dipilih -->
            <div class="form-section">
                <h3>Selected Property</h3>
                <p><strong><?php echo $row['title']; ?></strong></p>
                <p><?php echo $row['description']; ?></p>
                <input type="hidden" name="property_id" value="<?php echo $row['id']; ?>">
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

            <!-- Total Harga -->
            <div class="form-section">
                <h3>Total Price Per Night</h3>
                <p id="total-price">Rp. 0</p>
                <input type="hidden" id="price_per_night" value="<?php echo $row['price']; ?>">
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
