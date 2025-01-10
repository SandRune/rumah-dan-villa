<?php
function handleReservationAction($conn, $reservation_id, $action, $reason = 'No reason provided') {
    $sql = "SELECT r.*, u.id AS user_id, p.title AS property_name 
            FROM reservations r
            JOIN users u ON r.user_id = u.id
            JOIN properties p ON r.property_id = p.id
            WHERE r.id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $reservation_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        throw new Exception("Reservation not found.");
    }

    $reservation = $result->fetch_assoc();
    $user_id = $reservation['user_id'];
    $property_name = $reservation['property_name'];
    $start_date = $reservation['start_date'];
    $end_date = $reservation['end_date'];

    if ($action === 'approve') {
        // Update status menjadi Approved
        $sql = "UPDATE reservations SET status = 'Approved' WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $reservation_id);
        $stmt->execute();

        // Tambahkan pesan ke inbox pengguna
        $message = "Reservation approved! Your reservation for $property_name from $start_date to $end_date has been approved. Enjoy your stay!";
    } elseif ($action === 'reject') {
        // Update status menjadi Rejected
        $sql = "UPDATE reservations SET status = 'Rejected', receipt = CONCAT(receipt, '\nRejection Reason: ', ?) WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $reason, $reservation_id);
        $stmt->execute();

        // Tambahkan pesan ke inbox pengguna
        $message = "Reservation rejected. Reason: $reason.";
    } else {
        throw new Exception("Invalid action.");
    }

    // Simpan pesan ke tabel messages
    $sql = "INSERT INTO messages (user_id, reservation_id, message) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $user_id, $reservation_id, $message);
    $stmt->execute();

    return true;
}
?>
