<?php
function insertCategory($conn, $category_name, $description, $status) {
    $sql = "INSERT INTO categories (category_name, description, status) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $category_name,  $description, $status);
    return $stmt->execute();
}
?>
