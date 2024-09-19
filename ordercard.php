<?php
session_start();
include 'db/config.php';

// Get form data without required checks
$customerName = $_POST['name'] ?? '';
$customerEmail = $_POST['email'] ?? '';
$customerPhone = $_POST['phone'] ?? '';
$floor = $_POST['floor'] ?? '';
$noteToRider = $_POST['note_to_rider'] ?? '';
$deliveryMethod = $_POST['deliveryMethod'] ?? '';
$paymentMethod = $_POST['paymentMethod'] ?? '';
$tipAmount = isset($_POST['tipAmount']) ? floatval($_POST['tipAmount']) : 0;
$deliveryFee = isset($_POST['deliveryFee']) ? floatval($_POST['deliveryFee']) : 0;

// Check if order_items exists and is valid
$orderItemsJson = $_POST['order_items'] ?? '[]';
$orderItems = json_decode($orderItemsJson, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    echo "JSON decode error: " . json_last_error_msg();
    exit;
}

// Initialize totals
$subtotal = 0;
$vatRate = 0.12; // Example VAT rate (12%)
$smallOrderFee = 30; // Example small order fee

// Check if orderItems is an array
if (!is_array($orderItems)) {
    echo "Invalid order items format.";
    exit;
}

// Group items by name and calculate subtotal
$groupedItems = [];
foreach ($orderItems as $item) {
    if (!isset($item['name'], $item['quantity'], $item['price'])) {
        echo "Invalid item format.";
        exit;
    }

    $itemName = $item['name'];
    $itemQuantity = $item['quantity'];
    $itemPrice = $item['price'];

    if (isset($groupedItems[$itemName])) {
        $groupedItems[$itemName]['quantity'] += $itemQuantity;
        $groupedItems[$itemName]['totalPrice'] += $itemPrice * $itemQuantity;
    } else {
        $groupedItems[$itemName] = [
            'name' => $itemName,
            'quantity' => $itemQuantity,
            'unitPrice' => $itemPrice,
            'totalPrice' => $itemPrice * $itemQuantity
        ];
    }
}

// Recalculate the subtotal based on grouped items
foreach ($groupedItems as $item) {
    $subtotal += $item['totalPrice'];
}

// Calculate total
$smallOrderFee = ($subtotal < 30) ? 30 : 0;
$total = $subtotal + $deliveryFee + $smallOrderFee + $tipAmount;
$vat = $total * $vatRate;
$grandTotal = $total + $vat;

// Prepare and bind for orders
$stmt = $conn->prepare("INSERT INTO onlinecustomer (customer_name, customer_email, customer_phone, floor, note_to_rider, delivery_method, payment_method, tip_amount, delivery_fee, subtotal, grand_total) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
if ($stmt === false) {
    error_log("Prepare failed: " . $conn->error);
    exit;
}

$stmt->bind_param("ssssssddddd", $customerName, $customerEmail, $customerPhone, $floor, $noteToRider, $deliveryMethod, $paymentMethod, $tipAmount, $deliveryFee, $subtotal, $grandTotal);

// Execute the statement for orders
if ($stmt->execute()) {
    $orderId = $stmt->insert_id; // Get the last inserted order ID

    // Prepare and bind for onlineorderitem
    $itemStmt = $conn->prepare("INSERT INTO onlineorderitem (onr_id, itemName, itemPrice, quantity) VALUES (?, ?, ?, ?)");
    $itemStmt->bind_param("isdi", $orderId, $itemName, $itemPrice, $quantity);

    // Loop through each order item and insert
    foreach ($orderItems as $item) {
        $itemName = $item['name'];
        $itemPrice = $item['price'];
        $quantity = $item['quantity'];
        if (!$itemStmt->execute()) {
            error_log($itemStmt->error); // Log item insertion errors
        }
    }

    echo "Order submitted successfully!";
} else {
    error_log("Execution failed: " . $stmt->error);
    echo "Error: " . $stmt->error; // Display the error message
}

// Close connections
$itemStmt->close();
$stmt->close();
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Delivery Address and Order Summary</title>
    <link rel="stylesheet" href="style.css" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: #f9f9f9;
        }

        .container {
            display: flex;
            justify-content: space-between;
            /* Ensures that the main content and order summary are spaced out */
            gap: 20px;
            margin: 20px;
        }

        .main-content {
            flex: 3;
            /* Takes up more space */
            margin-right: 20px;
        }

        .order-summary {
            flex: 1;
            /* Takes up less space */
            max-width: 300px;
            /* Adjust width as needed */
            min-width: 250px;
            /* Set a minimum width to avoid being too narrow */
        }

        .card {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
        }

        h2 {
            font-size: 1.5em;
            margin-bottom: 15px;
        }

        .submit-btn {
            background-color: #e6b800;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 1.1em;
            border-radius: 5px;
            cursor: pointer;
        }

        .submit-btn:hover {
            background-color: #e6b800;
        }

        .hidden {
            display: none;
        }

        .preview img {
            max-width: 100px;
            max-height: 100px;
        }

        .order-summary p {
            font-size: 1.1em;
            margin: 10px 0;
        }

        .order-details p,
        .total p {
            display: flex;
            justify-content: space-between;
        }

        .total p {
            font-weight: bold;
            font-size: 1.3em;
        }

        /* Modal Styles */
        .modal {
            display: none;
            /* Hidden by default */
            position: fixed;
            z-index: 1;
            /* Sit on top */
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            /* Enable scroll if needed */
            background-color: rgb(0, 0, 0);
            /* Fallback color */
            background-color: rgba(0, 0, 0, 0.4);
            /* Black w/ opacity */
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            /* 15% from the top and centered */
            padding: 20px;
            border: 1px solid #888;
            width: 28%;
            /* Could be more or less, depending on screen size */
        }

        .close-button {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close-button:hover,
        .close-button:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        #map {
            height: 350px;
        }

        .leaflet-control-geocoder {
            margin-top: 10px;
        }
    </style>

</head>

<body>
    <div class="container">
        <!-- Main Content Section -->
        <div class="main-content">
            <!-- Delivery Address Section -->
            <div class="delivery-address card">
                <h2>Delivery Address</h2>
                <div class="map">
                    <img src="https://via.placeholder.com/400x200?text=Map" alt="Map" />
                </div>
                <form id="location-form">
                    <div class="address-details">
                        <p id="location-info">
                            <!-- Default text or will be updated dynamically -->
                        </p>
                        <button id="add-edit-location" class="submit-btn" type="button">Add Address</button>
                        <div id="edit-location-form" class="hidden">
                            <input type="text" name="floor" placeholder="Floor" />
                            <textarea name="note_to_rider" placeholder="Note to rider - e.g. building, landmark"></textarea>
                            <button class="submit-btn" type="button" id="save-location">Update Location</button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Delivery Options Section -->
            <div class="delivery-options card">
                <h2>Delivery Options</h2>
                <form id="delivery-form">
                    <label>
                        <input type="radio" name="deliveryMethod" value="standard" />
                        Standard 10 – 25 mins
                    </label>
                    <br />
                    <label>
                        <input type="radio" name="deliveryMethod" value="priority" />
                        Priority 5 – 20 mins <span>+ ₱19.00</span>
                    </label>
                    <br />
                    <label>
                        <input type="radio" name="deliveryMethod" value="scheduled" />
                        Scheduled
                        <span id="scheduled-info"></span> <!-- Placeholder for date and time -->
                    </label>
                </form>
            </div>

            <!-- Scheduled Delivery Modal -->
            <div id="scheduled-modal" class="modal hidden">
                <div class="modal-content">
                    <span class="close-button">&times;</span>
                    <h2>Select a Date and Time</h2>
                    <p>Select a date:</p>
                    <select id="date-select">
                        <option value="today">Today</option>
                        <option value="sat">Sat, Sep 14</option>
                        <option value="sun">Sun, Sep 15</option>
                    </select>
                    <p>Select a time slot:</p>
                    <select id="time-select">
                        <option value="10:30">10:30 AM – 10:45 AM</option>
                        <option value="10:45">10:45 AM – 11:00 AM</option>
                        <option value="11:00">11:00 AM – 11:15 AM</option>
                        <!-- Add more time slots as needed -->
                    </select>
                    <input type="hidden" name="deliveryDate" id="delivery-date-hidden" />
                    <input type="hidden" name="deliveryTime" id="delivery-time-hidden" />
                    <button id="confirm-time">Confirm</button>
                </div>
            </div>

            <!-- Personal Details Section -->
            <div class="card personal-details">
                <h2>Personal Details</h2>
                <div id="personal-info" class="hidden">
                    <!-- Displayed when details are present -->
                </div>
                <button id="edit-personal" class="edit hidden">Edit</button>
                <div id="personal-details-form">
                    <form method="post" action="">
                        <input type="text" name="name" placeholder="Full Name" />
                        <input type="email" name="email" placeholder="Email" />
                        <input type="tel" name="phone" placeholder="Phone Number" />
                        <button class="submit-btn" type="button" id="save-personal">Save</button>
                    </form>
                </div>
            </div>

            <div class="card payment-methods">
                <h2>Payment</h2>
                <form id="payment-form">
                    <label>
                        <input type="radio" name="paymentMethod" value="full" checked />
                        Full Payment
                    </label>
                    <br />
                    <label>
                        <input type="radio" name="paymentMethod" value="half" />
                        Half Payment
                    </label>
                </form>
            </div>

            <!-- Tip Your Rider Section -->
            <div class="card tip-rider">
                <h2>Tip Your Rider</h2>
                <p>100% of the tips go to your rider, we don't deduct anything from it.</p>
                <form id="tip-form">
                    <label>
                        <input type="radio" name="tipAmount" value="0" />
                        Not now
                    </label>
                    <label>
                        <input type="radio" name="tipAmount" value="5" />
                        ₱5.00
                    </label>
                    <label>
                        <input type="radio" name="tipAmount" value="20" />
                        ₱20.00
                    </label>
                    <label>
                        <input type="radio" name="tipAmount" value="40" />
                        ₱40.00
                    </label>
                    <label>
                        <input type="radio" name="tipAmount" value="60" />
                        ₱60.00
                    </label>
                </form>
            </div>

            <!-- ID Verification Section -->
            <div class="card id-verification">
                <h2>Upload Senior Citizen/PWD ID</h2>
                <form id="id-verification-form" enctype="multipart/form-data" method="post">
                    <label for="id_type">Select ID Type:</label><br />
                    <label>
                        <input type="radio" name="id_type" value="senior" />
                        Senior Citizen ID
                    </label><br />
                    <label>
                        <input type="radio" name="id_type" value="pwd" />
                        PWD ID
                    </label><br />
                    <label for="id_front_image">Upload Front of ID:</label>
                    <input type="file" name="id_front_image" id="id_front_image" accept="image/*" required />
                    <div id="id-front-preview" class="preview hidden">
                        <img id="id-front-image" src="#" alt="Front of ID Preview" />
                    </div><br />
                    <label for="id_back_image">Upload Back of ID:</label>
                    <input type="file" name="id_back_image" id="id_back_image" accept="image/*" required />
                    <div id="id-back-preview" class="preview hidden">
                        <img id="id-back-image" src="#" alt="Back of ID Preview" />
                    </div><br />
                    <div id="id-validation-message" class="error hidden"></div>
                </form>
            </div>

            <!-- Order Summary Section -->
            <div class="order-summary right-side card">
                <h2>Your Order Summary</h2>
                <div class="order-details">
                    <?php foreach ($groupedItems as $item): ?>
                        <p>
                            <?php echo htmlspecialchars($item['quantity']); ?> x <?php echo htmlspecialchars($item['name']); ?>
                            <span>₱<?php echo number_format($item['totalPrice'], 2); ?></span>
                        </p>
                    <?php endforeach; ?>
                    <p>Subtotal <span>₱<?php echo number_format($subtotal, 2); ?></span></p>

                    <p id="delivery-fee-display">Standard delivery <span>Free</span></p>

                    <!-- Conditionally display the small order fee -->
                    <?php if ($smallOrderFee > 0): ?>
                        <p id="small-order-fee">Small order fee <span>₱<?php echo number_format($smallOrderFee, 2); ?></span></p>
                    <?php endif; ?>

                    <p id="tip-amount">Tip <span>₱<?php echo number_format($tipAmount, 2); ?></span></p>
                </div>

                <div class="total">
                    <p>Total (incl. VAT) <span id="grand-total">₱<?php echo number_format($grandTotal, 2); ?></span></p>
                </div>
            </div>
        </div>
    </div>
    <!-- Place Order Button -->
    <form id="order-form" method="post" action="submit_order.php">
        <input type="hidden" name="order_items" id="order-items-json" value='<?php echo htmlspecialchars(json_encode($items)); ?>' />
        <input type="hidden" name="floor" id="floor-hidden" />
        <input type="hidden" name="note_to_rider" id="note-to-rider-hidden" />
        <input type="hidden" name="deliveryMethod" id="delivery-method-hidden" />
        <input type="hidden" name="paymentMethod" id="payment-method-hidden" />
        <input type="hidden" name="tipAmount" id="tip-amount-hidden" />
        <input type="hidden" name="deliveryFee" id="delivery-fee-hidden" />
        <div class="place-order">
            <button id="place-order-btn" class="submit-btn" type="submit">Place Order</button>
        </div>
    </form>
    </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Personal Details section functionality
            const personalInfo = document.getElementById('personal-info');
            const personalDetailsForm = document.getElementById('personal-details-form');
            const editPersonalBtn = document.getElementById('edit-personal');
            const savePersonalBtn = document.getElementById('save-personal');

            let hasPersonalDetails = false;

            function updatePersonalDetailsDisplay() {
                if (hasPersonalDetails) {
                    personalInfo.innerHTML = `<p>Name: Some Name</p><p>Email: some.email@example.com</p><p>Phone: 123-456-7890</p>`;
                    personalInfo.classList.remove('hidden');
                    editPersonalBtn.classList.remove('hidden');
                } else {
                    personalInfo.innerHTML = '';
                    personalInfo.classList.add('hidden');
                    editPersonalBtn.classList.add('hidden');
                }
            }

            editPersonalBtn.addEventListener('click', function() {
                personalDetailsForm.classList.toggle('hidden');
                personalInfo.classList.toggle('hidden');
            });

            savePersonalBtn.addEventListener('click', function() {
                const name = document.querySelector('input[name="name"]').value;
                const email = document.querySelector('input[name="email"]').value;
                const phone = document.querySelector('input[name="phone"]').value;

                if (name.trim() === '' || email.trim() === '' || phone.trim() === '') {
                    alert('Please fill out all fields.');
                    return;
                }

                // Validate email format
                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailPattern.test(email)) {
                    alert('Please enter a valid email address.');
                    return;
                }

                // Validate phone format (simple check for digits)
                const phonePattern = /^\d{10,15}$/;
                if (!phonePattern.test(phone)) {
                    alert('Please enter a valid phone number.');
                    return;
                }

                personalInfo.innerHTML = `<p>Name: ${name}</p><p>Email: ${email}</p><p>Phone: ${phone}</p>`;
                personalDetailsForm.classList.add('hidden');
                personalInfo.classList.remove('hidden');
                hasPersonalDetails = true;
                editPersonalBtn.classList.remove('hidden');
            });

            // Address form handling
            const addressForm = document.getElementById('location-form');
            const addEditLocationBtn = document.getElementById('add-edit-location');
            const saveLocationBtn = document.getElementById('save-location');
            const editLocationForm = document.getElementById('edit-location-form');

            addEditLocationBtn.addEventListener('click', function() {
                editLocationForm.classList.toggle('hidden');
            });

            saveLocationBtn.addEventListener('click', function() {
                const floor = addressForm.querySelector('input[name="floor"]').value;
                const noteToRider = addressForm.querySelector('textarea[name="note_to_rider"]').value;

                document.getElementById('floor-hidden').value = floor;
                document.getElementById('note-to-rider-hidden').value = noteToRider;

                addressForm.querySelector('#location-info').textContent = `Floor: ${floor}, Note to Rider: ${noteToRider}`;
                editLocationForm.classList.add('hidden');
            });

            // Delivery form handling
            const deliveryForm = document.getElementById('delivery-form');
            const deliveryFeeDisplay = document.getElementById('delivery-fee-display');
            const deliveryMethodHidden = document.getElementById('delivery-method-hidden');
            const baseDeliveryFee = 0;
            const priorityDeliveryFee = 19.00;

            deliveryForm.addEventListener('change', function() {
                const selectedDeliveryMethod = deliveryForm.querySelector('input[name="deliveryMethod"]:checked').value;
                deliveryMethodHidden.value = selectedDeliveryMethod;

                if (selectedDeliveryMethod === 'priority') {
                    deliveryFeeDisplay.innerHTML = `Priority 5 – 20 mins <span>+ ₱${priorityDeliveryFee.toFixed(2)}</span>`;
                    updateOrderSummary(priorityDeliveryFee);
                } else {
                    deliveryFeeDisplay.innerHTML = `Standard 10 – 25 mins <span>Free</span>`;
                    updateOrderSummary(baseDeliveryFee);
                }
            });

            // Tip form handling
            const tipForm = document.getElementById('tip-form');
            const tipAmountHidden = document.getElementById('tip-amount-hidden');
            const tipAmountDisplay = document.getElementById('tip-amount');
            const grandTotalDisplay = document.getElementById('grand-total');

            tipForm.addEventListener('change', function() {
                const selectedTipAmount = tipForm.querySelector('input[name="tipAmount"]:checked').value;
                tipAmountHidden.value = selectedTipAmount;

                const tipAmount = parseFloat(selectedTipAmount);
                tipAmountDisplay.innerHTML = `Tip <span>₱${tipAmount.toFixed(2)}</span>`;
                updateOrderSummary(undefined, tipAmount);
            });

            function updateOrderSummary(deliveryFee = baseDeliveryFee, tipAmount = parseFloat(tipAmountHidden.value) || 0) {
                const subtotal = parseFloat("<?php echo number_format($subtotal, 2); ?>");
                const smallOrderFee = parseFloat("<?php echo $smallOrderFee; ?>");
                const vatRate = 0.12;
                const total = subtotal + deliveryFee + smallOrderFee + tipAmount;
                const vat = total * vatRate;
                const grandTotal = total + vat;

                grandTotalDisplay.innerHTML = `₱${grandTotal.toFixed(2)}`;
            }

            // Modal elements
            const scheduledModal = document.getElementById('scheduled-modal');
            const closeButton = document.querySelector('.close-button');
            const confirmTimeButton = document.getElementById('confirm-time');
            const scheduledInfo = document.getElementById('scheduled-info'); // Placeholder element

            // Show the modal when 'Scheduled' is selected
            deliveryForm.addEventListener('change', function() {
                const selectedDeliveryMethod = deliveryForm.querySelector('input[name="deliveryMethod"]:checked').value;

                if (selectedDeliveryMethod === 'scheduled') {
                    scheduledModal.style.display = 'block';
                } else {
                    scheduledModal.style.display = 'none';
                }
            });

            // Close the modal
            closeButton.addEventListener('click', function() {
                scheduledModal.style.display = 'none';
            });

            // Confirm button functionality
            confirmTimeButton.addEventListener('click', function() {
                const selectedDate = document.getElementById('date-select').value;
                const selectedTime = document.getElementById('time-select').value;

                // Update the scheduled-info span with selected date and time
                scheduledInfo.textContent = `Date: ${selectedDate}, Time: ${selectedTime}`;

                // Set hidden input fields with selected date and time
                document.getElementById('delivery-date-hidden').value = selectedDate;
                document.getElementById('delivery-time-hidden').value = selectedTime;

                // Hide the modal
                scheduledModal.style.display = 'none';
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const paymentForm = document.getElementById('payment-form');
            const subtotal = parseFloat("<?php echo number_format($subtotal, 2); ?>"); // Get subtotal from PHP
            const grandTotalDisplay = document.getElementById('grand-total');

            paymentForm.addEventListener('change', function() {
                const selectedPaymentMethod = paymentForm.querySelector('input[name="paymentMethod"]:checked').value;

                if (selectedPaymentMethod === 'half') {
                    updateOrderSummary(subtotal / 2);
                } else {
                    updateOrderSummary(subtotal);
                }
            });

            function updateOrderSummary(newSubtotal) {
                const smallOrderFee = parseFloat("<?php echo $smallOrderFee; ?>");
                const vatRate = 0.12;
                const tipAmount = parseFloat(document.getElementById('tip-amount-hidden').value) || 0;
                const total = newSubtotal + smallOrderFee + tipAmount;
                const vat = total * vatRate;
                const grandTotal = total + vat;

                grandTotalDisplay.innerHTML = `₱${grandTotal.toFixed(2)}`;
            }
        });
    </script>


    <script src="validate_id.js"></script>
</body>

</html>