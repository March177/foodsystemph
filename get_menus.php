<?php

include 'db/config.php';


function getMenuItems()
{
    global $conn;

    $query = "SELECT 
                menu.menu_name, 
                menu.code, 
                menu.price, 
                menu.created_by, 
                menu.image_path, 
                menu.description, 
                menu.discount_type, 
                menu.status, 
                categories.category_name, 
                subcategories.subcategory_name 
              FROM menu
              LEFT JOIN categories ON menu.category_id = categories.id
              LEFT JOIN subcategories ON menu.subcategory_id = subcategories.id";

    $result = mysqli_query($conn, $query);
    $menuItems = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $menuItems[] = $row;
    }
    return $menuItems;
}


function getMenus()
{
    global $conn;
    $query = "SELECT DISTINCT menu_name FROM menu";
    $result = mysqli_query($conn, $query);
    $menus = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $menus[] = $row['menu_name'];
    }
    return $menus;
}


function getCategories()
{
    global $conn;
    $query = "SELECT DISTINCT category_name FROM menu";
    $result = mysqli_query($conn, $query);
    $categories = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $categories[] = $row['category_name'];
    }
    return $categories;
}

function getSubCategories($category)
{
    global $conn;
    $category = mysqli_real_escape_string($conn, $category);
    $query = "SELECT DISTINCT subcategory_name FROM menu WHERE category_name = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 's', $category);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $subcategories = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $subcategories[] = $row['subcategory_name'];
    }
    mysqli_stmt_close($stmt);
    return $subcategories;
}


function getPrices($menu, $category, $subcategory)
{
    global $conn;
    $menu = mysqli_real_escape_string($conn, $menu);
    $category = mysqli_real_escape_string($conn, $category);
    $subcategory = mysqli_real_escape_string($conn, $subcategory);
    $query = "SELECT DISTINCT price FROM menu WHERE menu_name = ? AND category_name = ? AND subcategory_name = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'sss', $menu, $category, $subcategory);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $prices = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $prices[] = $row['price'];
    }
    mysqli_stmt_close($stmt);
    return $prices;
}


if (isset($_GET['action'])) {
    $action = $_GET['action'];
    switch ($action) {
        case 'get_subcategories':
            $category = $_GET['category'];
            $subcategories = getSubCategories($category);
            echo json_encode(['subcategories' => $subcategories]);
            break;
        case 'get_prices':
            $menu = $_GET['menu'];
            $category = $_GET['category'];
            $subcategory = $_GET['subcategory'];
            $prices = getPrices($menu, $category, $subcategory);
            echo json_encode(['prices' => $prices]);
            break;
    }
    exit;
}
