<?php
include_once "../config/dbconnect.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $email_status = $_POST['email_status'];
    $registration_status = $_POST['registration_status'];

    $sql = "UPDATE event_registrations SET email_status = ?, registration_status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssi', $email_status, $registration_status, $id);

    if ($stmt->execute()) {
        echo "Participant status updated successfully.";
    } else {
        echo "Error updating participant status.";
    }

    $stmt->close();
    $conn->close();
}
?>