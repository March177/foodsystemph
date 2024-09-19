<?php
include 'db/config.php'; // Include your database configuration

// Fetch categories
$query = "SELECT DISTINCT category FROM menu";
$result = $conn->query($query);
$categories = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row['category'];
    }
}

// Fetch subcategories
$query = "SELECT DISTINCT subcategory FROM menu WHERE subcategory IS NOT NULL";
$result = $conn->query($query);
$subcategories = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $subcategories[] = $row['subcategory'];
    }
}

echo json_encode(['categories' => $categories, 'subcategories' => $subcategories]);
?>
