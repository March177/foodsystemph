<?php
// delete_menu.php
include 'db/config.php'; // Include database configuration

if (isset($_POST['id'])) {
    $id = intval($_POST['id']); // Convert to integer for security
    
    // Prepare the SQL statement to delete all categories
    $stmt = $conn->prepare("DELETE FROM categories");

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'All categories deleted successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error deleting categories: ' . $conn->error]);
    }
    
    $stmt->close(); // Close the statement
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}

$conn->close(); // Close the connection

?>
