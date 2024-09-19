<?php
function getCategories() {
    global $conn; // Assuming $conn is your database connection variable
    $query = "SELECT category_name, description, status FROM categories";
    $result = mysqli_query($conn, $query);
    
    $categories = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $categories[] = $row;
    }
    
    return $categories;
}
?>
