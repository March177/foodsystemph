<?php

include 'db/config.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $c_id = intval($_POST['c_id']);
    $category_name = mysqli_real_escape_string($conn, $_POST['category_name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);


    if (empty($category_name) || empty($status)) {
        echo 'Please fill in all required fields.';
        exit;
    }


    $query = "UPDATE categories SET category_name = '$category_name', description = '$description', status = '$status' WHERE c_id = $c_id";


    if (mysqli_query($conn, $query)) {
        echo 'success';
    } else {
        echo 'Database error: ' . mysqli_error($conn);
    }
} else {
    echo 'Invalid request method.';
}
