<?php
// Database connection
$host = 'localhost';
$db = 'restaurant_db';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and decode JSON data from POST request
    $data = json_decode(file_get_contents('php://input'), true);

    // Check if JSON data is properly decoded
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo "Invalid JSON data.";
        exit;
    }

    $order_id = 'ORD' . uniqid();
    $customer = 'Walk-In';
    $order_type = isset($data['order_type']) ? $data['order_type'] : '';
    $discount = isset($data['discount']) ? $data['discount'] : 0;
    $total_price = isset($data['total_amount']) ? $data['total_amount'] : 0;
    $payment_method = isset($data['payment_method']) ? $data['payment_method'] : '';
    $created_by = 'Cashier';
    $created_at = date('Y-m-d H:i:s');

    try {
        // Start transaction
        $pdo->beginTransaction();

        // Insert into orders table
        $stmt = $pdo->prepare("
            INSERT INTO orders 
            (order_id, customer, order_type, discount, payment_method, total_price, created_at, created_by) 
            VALUES 
            (:order_id, :customer, :order_type, :discount, :payment_method, :total_price, :created_at, :created_by)
        ");
        $stmt->execute([
            ':order_id' => $order_id,
            ':customer' => $customer,
            ':order_type' => $order_type,
            ':discount' => $discount,
            ':payment_method' => $payment_method,
            ':total_price' => $total_price,
            ':created_at' => $created_at,
            ':created_by' => $created_by
        ]);

        // Insert into order_menu table
        $stmt = $pdo->prepare("
            INSERT INTO order_menu 
            (order_id, menu_name, menu_image, category, subcategory, quantity, price) 
            VALUES 
            (:order_id, :menu_name, :menu_image, :category, :subcategory, :quantity, :price)
        ");

        if (isset($data['orderedItems']) && is_array($data['orderedItems'])) {
            foreach ($data['orderedItems'] as $item) {
                $stmt->execute([
                    ':order_id' => $order_id,
                    ':menu_name' => isset($item['name']) ? $item['name'] : '',
                    ':menu_image' => isset($item['image']) ? $item['image'] : '',
                    ':category' => isset($item['category']) ? $item['category'] : '',
                    ':subcategory' => isset($item['subcategory']) ? $item['subcategory'] : '', // Check if this key exists
                    ':quantity' => isset($item['quantity']) ? $item['quantity'] : 0,
                    ':price' => isset($item['price']) ? $item['price'] : 0
                ]);
            }
        }

        // Commit transaction
        $pdo->commit();

        echo "Order and transaction recorded successfully.";
    } catch (PDOException $e) {
        // Rollback transaction if there's an error
        $pdo->rollBack();
        echo "Error: " . $e->getMessage();
    }
}
?>
