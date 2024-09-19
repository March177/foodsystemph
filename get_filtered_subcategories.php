<?php
function get_filtered_subcategories($filter)
{

    include 'db/config.php';

    $query = "SELECT * FROM subcategories";
    $conditions = [];


    if (!empty($filter['status'])) {
        $status = mysqli_real_escape_string($conn, $filter['status']);
        $conditions[] = "status = '$status'";
    }


    if (!empty($conditions)) {
        $query .= " WHERE " . implode(' AND ', $conditions);
    }

    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }

    $output = '';
    while ($row = mysqli_fetch_assoc($result)) {
        $output .= '<tr>';
        $output .= '<td><label class="checkboxs"><input type="checkbox" /><span class="checkmarks"></span></label></td>';
        $output .= '<td>' . htmlspecialchars($row['subcategory_name']) . '</td>';
        $output .= '<td>' . htmlspecialchars($row['description']) . '</td>';
        $output .= '<td>' . htmlspecialchars($row['status']) . '</td>';
        $output .= '<td>
                        <a class="me-3" href="editsubcategory.php?id=' . $row['id'] . '">
                            <img src="assets/img/icons/edit.svg" alt="img" />
                        </a>
                        <a class="me-3 confirm-text" href="javascript:void(0);" data-id="' . $row['id'] . '">
                            <img src="assets/img/icons/delete.svg" alt="img" />
                        </a>
                    </td>';
        $output .= '</tr>';
    }


    mysqli_free_result($result);


    mysqli_close($conn);

    return $output;
}
