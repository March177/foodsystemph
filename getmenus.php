<?php

include 'db/config.php';

function get_filtered_menus($filter)
{
    global $conn;
    $query = "SELECT * FROM menu WHERE 1";

    // Check for status filter
    if (!empty($filter['status'])) {
        $query .= " AND status = '" . mysqli_real_escape_string($conn, $filter['status']) . "'";
    }

    // Check for menu name filter
    if (!empty($filter['menu_name'])) {
        $menuName = mysqli_real_escape_string($conn, $filter['menu_name']);
        $query .= " AND menu_name LIKE '%$menuName%'";
    }

    $result = mysqli_query($conn, $query);

    $rows = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
    } else {
        echo "Error: " . mysqli_error($conn);
    }
    return $rows;
}


$filter = [
    'status' => isset($_GET['status']) ? $_GET['status'] : '',
    'menu_name' => isset($_GET['menu_name']) ? $_GET['menu_name'] : ''
];

$rows = get_filtered_menus($filter);


if ($rows) {
    foreach ($rows as $row) {
        echo '<tr>';
        echo '<td><label class="checkboxs"><input type="checkbox" /><span class="checkmarks"></span></label></td>';
        echo '<td>' . htmlspecialchars($row['menu_name']) . '</td>';
        echo '<td>' . htmlspecialchars($row['description']) . '</td>';
        echo '<td>' . htmlspecialchars($row['status']) . '</td>';
        echo '<td>';
        echo '<a class="me-3" href="editmenu.php?id=' . $row['id'] . '"><img src="assets/img/icons/edit.svg" alt="img" /></a>';
        echo '<a class="me-3 confirm-text" href="javascript:void(0);" data-id="' . htmlspecialchars($row['id']) . '"><img src="assets/img/icons/delete.svg" alt="img" /></a>';
        echo '</td>';
        echo '</tr>';
    }
} else {
    echo '<tr><td colspan="5">No menu items found.</td></tr>';
}
