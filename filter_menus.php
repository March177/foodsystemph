<?php
// filter_menus.php

include 'db/config.php';

// Retrieve filter parameters
$menu = isset($_POST['menu']) ? $_POST['menu'] : '';
$category = isset($_POST['category']) ? $_POST['category'] : '';
$subcategory = isset($_POST['subcategory']) ? $_POST['subcategory'] : '';
$price = isset($_POST['price']) ? $_POST['price'] : '';

// Build the SQL query based on filters
$query = "SELECT * FROM menu WHERE 1=1";

if ($menu) {
    $query .= " AND menu_name = '$menu'";
}
if ($category) {
    $query .= " AND category = '$category'";
}
if ($subcategory) {
    $query .= " AND subcategory = '$subcategory'";
}
if ($price) {
    $query .= " AND price = '$price'";
}

// Execute the query
$result = mysqli_query($conn, $query);

// Generate the table rows based on the filtered results
$output = '';
while ($row = mysqli_fetch_assoc($result)) {
    $output .= '<tr>';
    $output .= '<td><label class="checkboxs"><input type="checkbox" /><span class="checkmarks"></span></label></td>';
    $output .= '<td class="productimgname"><a href="javascript:void(0);" class="product-img"><img src="' . htmlspecialchars($row['image_path']) . '" alt="product" onerror="this.src=\'assets/img/default.jpg\';" /></a><a href="javascript:void(0);">' . htmlspecialchars($row['menu_name']) . '</a></td>';
    $output .= '<td>' . htmlspecialchars($row['code']) . '</td>';
    $output .= '<td>' . htmlspecialchars($row['category']) . '</td>';
    $output .= '<td>' . htmlspecialchars($row['subcategory']) . '</td>';
    $output .= '<td>' . htmlspecialchars($row['price']) . '</td>';
    $output .= '<td>' . htmlspecialchars($row['created_by']) . '</td>';
    $output .= '<td><a class="me-3" href="product-details.php"><img src="assets/img/icons/eye.svg" alt="img" /></a><a class="me-3" href="editproduct.php?id=' . htmlspecialchars($row['id']) . '"><img src="assets/img/icons/edit.svg" alt="img" /></a><a class="me-3 confirm-text" href="javascript:void(0);" data-id="' . htmlspecialchars($row['id']) . '"><img src="assets/img/icons/delete.svg" alt="img" /></a></td>';
    $output .= '</tr>';
}

echo $output;
?>
