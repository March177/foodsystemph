<?php
session_start();
include 'db/config.php'; // Include database configuration

if (isset($_POST['device_id']) && isset($_SESSION['admin_id'])) {
    $device_id = $_POST['device_id'];
    $admin_id = $_SESSION['admin_id'];

    // Remove the device session from the database
    $query = "DELETE FROM device_sessions WHERE admin_id = ? AND device_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("is", $admin_id, $device_id);
    $stmt->execute();
}

header("Location: device_management.php");
?>
