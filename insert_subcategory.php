<?php
function insertSubcategory($conn, $category_id, $subcategory_name, $description, $status) {
    $sql = "INSERT INTO subcategories (category_id, subcategory_name, description, status) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isss", $category_id, $subcategory_name, $description, $status);
    return $stmt->execute();
}
?>
