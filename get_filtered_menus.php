<?php
include 'db/config.php';

$menu = isset($_GET['menu']) ? $_GET['menu'] : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';
$subcategory = isset($_GET['subcategory']) ? $_GET['subcategory'] : '';
$price = isset($_GET['price']) ? $_GET['price'] : '';

$query = "SELECT * FROM menu WHERE 1";

if ($menu !== '') {
    $query .= " AND menu_name = '" . mysqli_real_escape_string($conn, $menu) . "'";
}
if ($category !== '') {
    $query .= " AND category = '" . mysqli_real_escape_string($conn, $category) . "'";
}
if ($subcategory !== '') {
    $query .= " AND subcategory = '" . mysqli_real_escape_string($conn, $subcategory) . "'";
}
if ($price !== '') {
    $query .= " AND price = '" . mysqli_real_escape_string($conn, $price) . "'";
}

$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_assoc($result)) {

    $imagePath = !empty($row['image']) && file_exists('img/menu/' . htmlspecialchars($row['image']))
        ? 'assets/img/menu/' . htmlspecialchars($row['image'])
        : 'assets/img/product/noimage.png';

    echo "<tr>
            <td>
                <label class='checkboxs'>
                    <input type='checkbox' />
                    <span class='checkmarks'></span>
                </label>
            </td>
            <td class='productimgname'>
                <a href='javascript:void(0);' class='product-img'>
                    <img src='{$imagePath}' alt='product' />
                </a>
                <a href='javascript:void(0);'>{$row['menu_name']}</a>
            </td>
            <td>{$row['code']}</td>
            <td>{$row['category']}</td>
            <td>{$row['subcategory']}</td>
            <td>{$row['price']}</td>
            <td>{$row['created_by']}</td>
            <td>
                <a class='me-3' href='product-details.html'>
                    <img src='assets/img/icons/eye.svg' alt='img' />
                </a>
                <a class='me-3' href='editproduct.php?id={$row['id']}'>
                    <img src='assets/img/icons/edit.svg' alt='img' />
                </a>
                <a class='me-3 confirm-text' href='javascript:void(0);' data-id='{$row['id']}'>
                    <img src='assets/img/icons/delete.svg' alt='img' />
                </a>
            </td>
        </tr>";
}
