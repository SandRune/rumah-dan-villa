<?php
function isDateAvailable($conn, $property_id, $start_date, $end_date) {
    $sql = "SELECT * FROM reservations WHERE property_id = ? AND status = 'Approved' AND 
            (start_date <= ? AND end_date >= ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $property_id, $end_date, $start_date);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->num_rows === 0;
}

function createReservation($conn, $property_id, $user_id, $start_date, $end_date, $guests) {
    $sql = "INSERT INTO reservations (property_id, user_id, start_date, end_date, guests, status) 
            VALUES (?, ?, ?, ?, ?, 'Pending')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iissi", $property_id, $user_id, $start_date, $end_date, $guests);
    
    if ($stmt->execute()) {
        return true;
    }
    return false;
}
?>
