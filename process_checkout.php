<?php
include 'db/config.php';

// Enable error reporting for debugging (remove in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Function to log errors
function logError($message) {
    error_log(date('[Y-m-d H:i:s] ') . $message . "\n", 3, 'error.log');
}

// Receive and decode the JSON data
$json_data = file_get_contents('php://input');
$data = json_decode($json_data, true);

// Log received data
logError("Received data: " . print_r($data, true));

// Start a transaction
$conn->begin_transaction();

try {
    // First, insert into orders table
    $order_query = "INSERT INTO orders (customer, order_type, discount, payment_method, total_price, created_by, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())";
    $order_stmt = $conn->prepare($order_query);
    
    if (!$order_stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }

    // Ensure total_price is a float
    $total_price = floatval($data['total_price']);

    $bind_result = $order_stmt->bind_param("ssssds", 
        $data['customer'], 
        $data['order_type'], 
        $data['discount'], 
        $data['payment_method'], 
        $total_price,
        $data['created_by']
    );
    
    if (!$bind_result) {
        throw new Exception("Binding parameters failed: " . $order_stmt->error);
    }

    // Execute the order insertion
    $exec_result = $order_stmt->execute();
    
    if (!$exec_result) {
        logError("Execute failed for order: " . $order_stmt->error);
        throw new Exception("Execute failed");
    }

    // Get the last inserted order_id
    $order_id = $conn->insert_id;

    if (!$order_id) {
        throw new Exception("Failed to insert order: No order_id returned");
    }

    logError("Order inserted successfully. Order ID: " . $order_id);
    
    // Continue with order details...
    // Assuming you have an array of ordered items
    $orderedItems = $data['orderedItems'];
    
    // Insert each ordered item into the order_details table
    $details_query = "INSERT INTO order_details (order_id, menu_id, quantity, price) VALUES (?, ?, ?, ?)";
    $details_stmt = $conn->prepare($details_query);
    
    if (!$details_stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }

    foreach ($orderedItems as $item) {
        $details_stmt->bind_param("iiid", 
            $order_id, 
            $item['menu_id'], 
            $item['quantity'], 
            $item['price']
        );
        
        if (!$details_stmt->execute()) {
            logError("Failed to insert order detail: " . $details_stmt->error);
        }
    }

    // Commit the transaction
    $conn->commit();

    // Send a success response
    echo json_encode(["status" => "success", "order_id" => $order_id, "message" => "Order processed successfully."]);
} catch (Exception $e) {
    // Rollback the transaction on error
    $conn->rollback();
    
    // Log the error message
    logError("Error processing order: " . $e->getMessage());
    
    // Send a generic error response
    echo json_encode(["status" => "error", "message" => "There was an issue processing your order. Please try again."]);
}

$conn->close();
?>
