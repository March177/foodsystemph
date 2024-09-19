<?php
include 'db/config.php';

header('Content-Type: application/json');

$sql = "SELECT DISTINCT category FROM categories"; // Adjust table and column names as needed
$result = $conn->query($sql);

$categories = [];
while ($row = $result->fetch_assoc()) {
    $categories[] = $row['category'];
}

echo json_encode($categories);
?>
