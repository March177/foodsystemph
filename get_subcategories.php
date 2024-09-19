<?php

include 'db/config.php';

function getSubCategories($category)
{
    global $conn;
    $query = "SELECT DISTINCT subcategory FROM menu WHERE category = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 's', $category);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $subcategories = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $subcategories[] = $row['subcategory'];
    }
    return $subcategories;
}

if (isset($_GET['category'])) {
    $category = $_GET['category'];
    $subcategories = getSubCategories($category);
    echo json_encode($subcategories);
}
