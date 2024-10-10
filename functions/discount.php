<?php
function get_filtered_discounts($filter)
{
    global $conn; // Use the global connection variable
    $discounts = [];

    // Start building the query
    $query = "SELECT discount_id, discount_value, discount_name, discount_code, start_date, end_date, status FROM discounts";
    $params = [];
    $types = ''; // To hold the types for bind_param

    // Check if there is a status filter
    if (!empty($filter['status'])) {
        $query .= " WHERE status = ?";
        $params[] = $filter['status'];
        $types .= 's'; // 's' indicates the type of the parameter is string
    }

    // Prepare the statement
    $stmt = $conn->prepare($query);

    // Check if prepare was successful
    if ($stmt) {
        // If there are parameters, bind them
        if ($params) {
            $stmt->bind_param($types, ...$params);
        }

        // Execute the statement
        $stmt->execute();
        $result = $stmt->get_result();

        // Fetch all results
        while ($row = $result->fetch_assoc()) {
            $discounts[] = $row;
        }

        // Close the statement
        $stmt->close();
    } else {
        // Handle error if preparation fails
        error_log("Query preparation failed: " . $conn->error);
    }

    return $discounts; // Return the list of discounts
}
