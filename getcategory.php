<?php


function get_filtered_categories($filter)
{
    global $conn;


    $query = "SELECT * FROM categories WHERE 1=1";


    if (!empty($filter['category_name'])) {
        $query .= " AND category_name LIKE '%" . mysqli_real_escape_string($conn, $filter['category_name']) . "%'";
    }

    if (!empty($filter['description'])) {
        $query .= " AND description LIKE '%" . mysqli_real_escape_string($conn, $filter['description']) . "%'";
    }

    if (!empty($filter['status'])) {
        $query .= " AND status = '" . mysqli_real_escape_string($conn, $filter['status']) . "'";
    }


    $result = mysqli_query($conn, $query);


    $categories = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $categories[] = $row;
    }

    return $categories;
}
