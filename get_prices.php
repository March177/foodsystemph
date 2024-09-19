<?php

include 'db/config.php';


$prices = [];

$menu = isset($_GET['menu']) ? $_GET['menu'] : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';
$subcategory = isset($_GET['subcategory']) ? $_GET['subcategory'] : '';

$query = "SELECT DISTINCT price FROM menu WHERE 1=1";

if ($menu) {
    $query .= " AND menu_name = '" . mysqli_real_escape_string($conn, $menu) . "'";
}

if ($category) {
    $query .= " AND category = '" . mysqli_real_escape_string($conn, $category) . "'";
}

if ($subcategory) {
    $query .= " AND subcategory = '" . mysqli_real_escape_string($conn, $subcategory) . "'";
}


$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_assoc($result)) {
    $prices[] = $row['price'];
}

echo json_encode($prices);
