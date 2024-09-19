<?php


include 'db/config.php';

if (isset($_POST['id'])) {
    $subcategory_id = $_POST['id'];


    $conn->begin_transaction();

    try {

        $delete_menu_query = "DELETE FROM menu WHERE sub_id = ?";
        $menu_stmt = $conn->prepare($delete_menu_query);
        $menu_stmt->bind_param('i', $subcategory_id);

        if (!$menu_stmt->execute()) {
            throw new Exception('Error deleting menu items.');
        }


        $delete_subcategory_query = "DELETE FROM subcategories WHERE sub_id = ?";
        $subcategory_stmt = $conn->prepare($delete_subcategory_query);
        $subcategory_stmt->bind_param('i', $subcategory_id);

        if (!$subcategory_stmt->execute()) {
            throw new Exception('Error deleting subcategory.');
        }

        $conn->commit();
        echo json_encode(['success' => true, 'message' => 'Subcategory and related menu items deleted successfully.']);
    } catch (Exception $e) {

        $conn->rollback();
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }


    $menu_stmt->close();
    $subcategory_stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid ID.']);
}

$conn->close();
