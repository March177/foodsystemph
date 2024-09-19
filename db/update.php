<?php
// Adjust the path based on the actual location of config.php
include __DIR__ . '/../db/config.php'; // Adjust path as necessary

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate inputs
    $menu_id = intval($_POST['id']);
    $menu_name = trim($_POST['menu_name']);
    $code = trim($_POST['code']);
    $category = trim($_POST['category']);
    $subcategory = trim($_POST['subcategory']);
    $description = trim($_POST['description']);
    $discount_type = trim($_POST['discount_type']);
    $price = trim($_POST['price']);
    $status = trim($_POST['status']);
    
    // Check if all required fields are present
    if (empty($menu_name) || empty($code) || empty($category) || empty($subcategory) || empty($description) || empty($price) || empty($status)) {
        echo "All fields are required.";
        exit();
    }

    if (!is_numeric($price) || $price <= 0) {
        echo "Price must be a positive number.";
        exit();
    }

    // Handle file upload
    $allowedTypes = ['image/jpeg', 'image/png'];
    $uploadDir = __DIR__ . '/../img/menu/';
    $fileName = basename($_FILES['image']['name']);
    $fileType = $_FILES['image']['type'];
    
    // Determine the image path
    $imagePath = trim($_POST['existing_image']);
    
    if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
        if (in_array($fileType, $allowedTypes)) {
            // Create directory if it does not exist
            if (!is_dir($uploadDir)) {
                if (!mkdir($uploadDir, 0755, true)) {
                    echo "Failed to create upload directory.";
                    exit();
                }
            }
            
            // Move the uploaded file to the target directory
            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $fileName)) {
                $imagePath = 'img/menu/' . $fileName;
            } else {
                echo "Failed to move uploaded file.";
                exit();
            }
        } else {
            echo "Invalid file type. Only jpg and png images are allowed.";
            exit();
        }
    } else if ($_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
        echo "File upload error. Error code: " . $_FILES['image']['error'];
        exit();
    }

    // Prepare SQL statement to update the menu
    $stmt = $conn->prepare("UPDATE menu SET menu_name = ?, code = ?, category = ?, subcategory = ?, description = ?, discount_type = ?, price = ?, status = ?, image_path = ? WHERE id = ?");
    if ($stmt === false) {
        echo "Prepare failed: " . htmlspecialchars($conn->error);
        exit();
    }

    $stmt->bind_param("sssssssssi", $menu_name, $code, $category, $subcategory, $description, $discount_type, $price, $status, $imagePath, $menu_id);

    if ($stmt->execute()) {
        // Redirect to the desired page
        echo "success";
    } else {
        echo "Execute failed: " . htmlspecialchars($stmt->error);
    }

    $stmt->close();
    $conn->close();
}
?>
