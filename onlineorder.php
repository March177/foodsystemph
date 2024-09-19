<?php
include 'db/config.php';

// Fetch categories for the sidebar
$query = "SELECT c_id, category_name FROM categories";
$categoriesResult = $conn->query($query);

if (!$categoriesResult) {
  die("Error: " . $conn->error);
}

// Define $selectedCategory based on user input
$selectedCategory = isset($_GET['category']) ? (int)$_GET['category'] : 0;

// Fetch menu items if a category is selected
if ($selectedCategory > 0) {
  $menuQuery = "SELECT c_id, menu_name, price, image_path FROM menu WHERE id = ?";
  $stmt = $conn->prepare($menuQuery);
  $stmt->bind_param("i", $selectedCategory);
  $stmt->execute();
  $menuResult = $stmt->get_result();
  $stmt->close();
}

// Handle form submission for placing an order
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = $_POST['name'];
  $location = $_POST['address'];
  $number = $_POST['number'];

  // Prepare and bind the SQL statement
  $stmt = $conn->prepare("INSERT INTO `order` (or_name, or_address, or_number) VALUES (?, ?, ?)");
  $stmt->bind_param("sss", $name, $location, $number);

  if ($stmt->execute()) {
    echo "Order submitted successfully!";
  } else {
    echo "Error: " . $stmt->error;
  }

  // Close the statement
  $stmt->close();
}

// Close the database connection
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Food Ordering System</title>
  <link rel="stylesheet" href="onlineorder.css"> <!-- Adjust the path as needed -->
  <style>
    /* Global Styles */
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif;
    }

    /* Header Styles */
    header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 10px 20px;
      background-color: #ffcc00;
      color: #800000;
    }

    .logo {
      font-size: 24px;
      font-weight: bold;
    }

    .cart-icon {
      position: relative;
    }

    .cart-icon img {
      width: 40px;
    }

    #cart-count {
      position: absolute;
      top: -5px;
      right: -10px;
      background-color: red;
      color: white;
      border-radius: 50%;
      padding: 5px;
      font-size: 14px;
    }

    /* Container Layout */
    .container {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      height: calc(100vh - 60px);
    }

    /* Sidebar Styles */
    .sidebar {
      flex: 1;
      max-width: 200px;
      background-color: #f2f2f2;
      padding: 20px;
    }

    .sidebar ul {
      list-style: none;
    }

    .sidebar ul li {
      padding: 10px;
      margin-bottom: 10px;
      background-color: #fff;
      cursor: pointer;
      border-radius: 5px;
      transition: background-color 0.3s;
    }

    .sidebar ul li:hover {
      background-color: #ffcc00;
    }

    /* Main Content Styles */
    .main-content {
      flex: 3;
      padding: 20px;
      overflow-y: auto;
    }

    .product-grid {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
    }

    .product-card {
      border: 1px solid #ddd;
      border-radius: 5px;
      padding: 10px;
      text-align: center;
      width: calc(25% - 20px);
      /* Adjust width to make card smaller */
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      background-color: #fff;
    }

    .product-card img {
      width: 100%;
      height: auto;
      border-bottom: 1px solid #ddd;
      margin-bottom: 10px;
    }

    .product-card h4 {
      margin: 0;
      font-size: 18px;
      /* Slightly adjust font size if needed */
    }

    .product-card .price {
      font-size: 16px;
      color: #800000;
      margin-top: 5px;
    }

    .product-card button {
      background-color: #ffcc00;
      border: none;
      color: #800000;
      padding: 10px;
      border-radius: 5px;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    .product-card button:hover {
      background-color: #e6b800;
    }

    /* Order Summary Styles */
    .order-summary {
      position: fixed;
      right: 0;
      top: 0;
      width: 300px;
      height: 100%;
      background-color: #fff;
      box-shadow: -2px 0 4px rgba(0, 0, 0, 0.1);
      padding: 20px;
      overflow-y: auto;
    }

    .order-summary-content {
      display: flex;
      flex-direction: column;
      gap: 10px;
    }

    .order-summary-content .order-button {
      background-color: #800000;
      color: #fff;
      border: none;
      padding: 10px 15px;
      border-radius: 5px;
      cursor: pointer;
      font-size: 16px;
      transition: background-color 0.3s;
    }

    .order-summary-content .order-button:hover {
      background-color: #600000;
    }

    .order-item {
      display: flex;
      align-items: center;
      margin-bottom: 20px;
    }

    .order-item-image {
      width: 80px;
      height: auto;
      border-radius: 5px;
    }

    .order-item-details {
      margin-left: 15px;
      flex: 1;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    .order-item-details span.name {
      font-size: 16px;
      font-weight: bold;
    }

    .order-item-details span.price {
      font-size: 14px;
      color: #800000;
    }

    .order-item-details .quantity-controls {
      display: flex;
      align-items: center;
      margin: 10px 0;
    }

    .quantity-controls button {
      background-color: #ffcc00;
      border: none;
      padding: 5px;
      color: #800000;
      cursor: pointer;
      border-radius: 5px;
      width: 30px;
      height: 30px;
      text-align: center;
      line-height: 20px;
    }

    .quantity-controls span {
      margin: 0 10px;
      font-size: 16px;
    }

    .remove-btn {
      background-color: #ff3333;
      color: white;
      border: none;
      padding: 5px;
      cursor: pointer;
      border-radius: 5px;
      font-size: 14px;
    }

    .remove-btn:hover {
      background-color: #cc0000;
    }

    #total-price {
      font-weight: bold;
      margin-top: 20px;
    }

    /* Responsive Styles */
    @media (max-width: 1024px) {
      .container {
        flex-direction: column;
      }

      .sidebar {
        max-width: 100%;
        width: 100%;
        margin-bottom: 20px;
      }

      .main-content,
      .order-summary {
        width: 100%;
      }

      .product-card {
        width: calc(50% - 20px);
        /* Adjust for medium screens */
      }
    }

    @media (max-width: 768px) {
      .product-card {
        width: calc(100% - 20px);
        /* Adjust for small screens */
      }

      .order-summary {
        margin-top: 20px;
      }
    }

    @media (max-width: 480px) {
      header {
        flex-direction: column;
        align-items: flex-start;
      }

      .cart-icon {
        margin-top: 10px;
      }

      .logo {
        margin-bottom: 10px;
      }
    }

    /* Styles for the dark overlay background */
    .order-popup-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      /* Dark semi-transparent background */
      display: none;
      /* Hidden by default */
      justify-content: center;
      /* Center horizontally */
      align-items: center;
      /* Center vertically */
      z-index: 1000;
      /* Ensure it's on top */
    }

    /* Styles for the popup itself */
    .order-popup {
      background: #fff;
      /* White background for the popup */
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
      /* Shadow effect */
      padding: 20px;
      max-width: 500px;
      /* Maximum width */
      width: 100%;
      position: relative;
      /* Ensure that close button is positioned correctly */
    }

    /* Styles for the close button */
    .close-popup {
      position: absolute;
      top: 10px;
      right: 10px;
      font-size: 24px;
      cursor: pointer;
      color: #333;
      /* Dark color for the close button */
    }

    /* Styles for the popup content */
    .popup-content {
      position: relative;
      padding: 20px;
    }

    /* Ensure popup footer buttons are styled */
    .popup-footer {
      margin-top: 20px;
      text-align: center;
    }

    .checkout-btn {
      background-color: #28a745;
      color: #fff;
      border: none;
      padding: 10px 20px;
      border-radius: 5px;
      cursor: pointer;
    }

    .order-more-btn {
      background-color: #007bff;
      color: #fff;
      border: none;
      padding: 10px 20px;
      border-radius: 5px;
      cursor: pointer;
      margin-top: 10px;
    }

    .order-more-btn:hover,
    .checkout-btn:hover {
      opacity: 0.8;
    }
  </style>
</head>

<body>
  <header>
    <div class="logo">Restaurant</div>
    <div class="cart-icon">
      <img src="images/cart.png" alt="Cart" />
      <span id="cart-count">0</span>
    </div>
  </header>

  <div class="container">
    <aside class="sidebar">
      <ul>
        <?php while ($category = $categoriesResult->fetch_assoc()): ?>
          <li data-category="<?php echo $category['c_id']; ?>">
            <a href="?category=<?php echo $category['c_id']; ?>"><?php echo htmlspecialchars($category['category_name']); ?></a>
          </li>
        <?php endwhile; ?>
      </ul>

    </aside>

    <main class="main-content">
      <div id="products" class="product-grid">
        <?php if ($selectedCategory > 0): ?>
          <?php while ($menuItem = $menuResult->fetch_assoc()): ?>
            <div class="product-card">
              <img src="<?php echo htmlspecialchars($menuItem['image_path']); ?>" class="product-image" alt="<?php echo htmlspecialchars($menuItem['menu_name']); ?>">
              <div class="product-info">
                <h4 class="product-name"><?php echo htmlspecialchars($menuItem['menu_name']); ?></h4>
                <span class="product-price">₱<?php echo number_format($menuItem['price'], 2); ?></span>
                <button class="add-to-cart-btn" data-id="<?php echo $menuItem['c_id']; ?>" data-name="<?php echo htmlspecialchars($menuItem['menu_name']); ?>" data-price="<?php echo number_format($menuItem['price'], 2); ?>" data-image="<?php echo htmlspecialchars($menuItem['image_path']); ?>">Buy</button>
              </div>
            </div>
          <?php endwhile; ?>
        <?php else: ?>
          <p>Please select a category to view menu items.</p>
        <?php endif; ?>
      </div>
    </main>

    <!-- Order Summary Section -->
    <aside class="order-summary">
      <div class="order-summary-content">
        <h2>Your Orders</h2>
        <div class="order-options">
          <div class="order-option" data-type="delivery">
            <span>Delivery</span>
            <small>Get your order delivered</small>
          </div>
          <div class="order-option" data-type="pickup">
            <span>Pick-up</span>
            <small>Pick up from store</small>
          </div>
        </div>

        <div id="order-items">
          <!-- Order items will be added here -->
        </div>
        <div class="total">Total: <span id="total-price">₱0.00</span></div>
        <button id="order-now" class="order-now-button">Order Now</button>
      </div>
    </aside>
  </div>

  <div id="orderPopup" class="order-popup-overlay">
    <div class="order-popup">
      <span class="close-popup">&times;</span>
      <h2>Order Details</h2>
      <div id="popup-order-items">
        <!-- Order items will be displayed here -->
      </div>
      <div class="popup-footer">
        <!-- Form with the continue button -->
        <form id="order-form" action="ordercard.php" method="post">
          <input type="hidden" name="order_items" id="order-items-data">
          <input type="hidden" name="name" id="popup-name">
          <input type="hidden" name="address" id="popup-address">
          <input type="hidden" name="number" id="popup-number">
          <button id="checkout-button" class="checkout-btn" type="submit">CHECKOUT</button>
        </form>

        <button class="order-more-btn">ORDER MORE</button>
      </div>
    </div>
  </div>



  <script>
    // JavaScript to handle "Order Now" and "Continue" button clicks
    document.addEventListener('DOMContentLoaded', function() {
      const orderItems = []; // This will hold the order items

      document.querySelectorAll('.add-to-cart-btn').forEach(button => {
        button.addEventListener('click', function() {
          const id = this.getAttribute('data-id');
          const name = this.getAttribute('data-name');
          const price = parseFloat(this.getAttribute('data-price'));
          const image = this.getAttribute('data-image');
          const quantity = 1; // Default quantity

          // Add item to the order
          orderItems.push({
            id,
            name,
            price,
            quantity,
            image
          });

          // Update the order summary
          updateOrderSummary();
        });
      });

      document.getElementById('order-now').addEventListener('click', function() {
        document.getElementById('orderPopup').style.display = 'block';
        const orderItemsData = JSON.stringify(orderItems);
        document.getElementById('order-items-data').value = orderItemsData;

        // Collect user information and set hidden fields in the popup form
        document.getElementById('popup-name').value = 'User Name'; // Replace with actual user input
        document.getElementById('popup-address').value = 'User Address'; // Replace with actual user input
        document.getElementById('popup-number').value = 'User Number'; // Replace with actual user input

        const popupOrderItems = document.getElementById('popup-order-items');
        popupOrderItems.innerHTML = ''; // Clear previous items

        orderItems.forEach(item => {
          const itemElement = document.createElement('div');
          itemElement.innerHTML = `
                    <p>${item.quantity} x ${item.name} <span>₱${(item.price * item.quantity).toFixed(2)}</span></p>
                `;
          popupOrderItems.appendChild(itemElement);
        });
      });

      document.querySelector('.close-popup').addEventListener('click', function() {
        document.getElementById('orderPopup').style.display = 'none';
      });

      document.querySelector('.order-more-btn').addEventListener('click', function() {
        document.getElementById('orderPopup').style.display = 'none';
      });

      function updateOrderSummary() {
        let total = 0;
        const orderItemsContainer = document.getElementById('order-items');
        orderItemsContainer.innerHTML = '';

        orderItems.forEach(item => {
          const itemTotal = item.price * item.quantity;
          total += itemTotal;

          const itemElement = document.createElement('div');
          itemElement.innerHTML = `
                    <p>${item.quantity} x ${item.name} <span>₱${itemTotal.toFixed(2)}</span></p>
                `;
          orderItemsContainer.appendChild(itemElement);
        });

        document.getElementById('total-price').innerText = `₱${total.toFixed(2)}`;
      }
    });
  </script>
</body>

</html>