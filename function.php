<?php
include '../db/config.php'; // Adjust the path as necessary

// Check if the ID is set
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Connect to the database
    $conn = mysqli_connect("localhost", "root", "", "restaurant_db");

    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Prepare the SQL statement to delete the product
    $sql = "DELETE FROM menu WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        $result = mysqli_stmt_execute($stmt);

        if ($result) {
            echo "Product deleted successfully.";
        } else {
            echo "Error deleting product: " . mysqli_error($conn);
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "Error preparing statement: " . mysqli_error($conn);
    }

    mysqli_close($conn);
} else {
    echo "No product ID specified.";
}
?>
