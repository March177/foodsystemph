<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Order Cake</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.1/dist/tailwind.min.css" rel="stylesheet" />
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background-color: #f7fafc;
    }

    .thumbnail img {
      border-radius: 10px;
      cursor: pointer;
      transition: transform 0.2s;
    }

    .thumbnail img:hover {
      transform: scale(1.1);
    }

    .star {
      color: #fbbf24;
    }

    .input-box {
      border-color: #cbd5e0;
      background-color: #edf2f7;
    }

    .input-box:focus {
      border-color: #805ad5;
      box-shadow: 0 0 0 1px #805ad5;
    }

    /* Align date and time selector in one row */
    .date-time-wrapper {
      display: flex;
      gap: 1rem;
      /* Gap between date and time fields */
    }

    /* Quantity input box styling */
    .quantity-wrapper {
      display: flex;
      align-items: center;
    }

    /* Submit button styling */
    .button-group {
      display: flex;
      gap: 10px;
    }

    .action-button {
      background-color: #9f7aea;
      color: white;
      padding: 0.75rem;
      border-radius: 0.25rem;
      transition: background-color 0.2s ease;
      text-align: center;
      width: 100%;
    }

    .action-button:hover {
      background-color: #805ad5;
    }

    .quantity-button {
      background-color: #edf2f7;
      border: 1px solid #cbd5e0;
      border-radius: 0.25rem;
      cursor: pointer;
      padding: 0.5rem;
      transition: background-color 0.2s ease;
      height: 100%;
      /* Match height with the input */
    }

    .quantity-input {
      text-align: center;
      width: 60px;
      border: 1px solid #cbd5e0;
      border-left: none;
      border-right: none;
      padding: 0.5rem 0;
      background-color: transparent;
      /* Remove background */
      height: 48px;
      /* Match height with other input boxes */
    }

    .quantity-wrapper {
      height: 48px;
      /* Match height with other input boxes */
    }

    /* Cart Icon Styles */
    .cart-icon {
      position: relative;
      cursor: pointer;
      margin-right: 20px;
      display: flex;
      align-items: center;
    }

    .cart-dropdown {
      position: absolute;
      top: 40px;
      right: 0;
      background: white;
      border: 1px solid #ccc;
      border-radius: 5px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      width: 300px;
      display: none;
      /* Hidden by default */
      z-index: 1000;
    }

    .cart-dropdown.active {
      display: block;
      /* Show the dropdown */
    }

    .cart-item {
      padding: 10px;
      border-bottom: 1px solid #f1f1f1;
    }

    .cart-item:last-child {
      border-bottom: none;
      /* Remove last border */
    }
  </style>
</head>

<body>
  <div class="container mx-auto p-8">
    <!-- Cart Icon -->
    <div class="cart-icon" onclick="toggleCartDropdown()">
      <img src="https://img.icons8.com/material-rounded/24/000000/shopping-cart.png" alt="Cart" />
      <span id="cart-count" class="ml-1">0</span>
      <div id="cart-dropdown" class="cart-dropdown">
        <h3 class="p-2 font-bold">Cart Items</h3>
        <div id="cart-items-container">
          <p class="p-2">No items in cart</p>
        </div>
      </div>
    </div>

    <?php
    // Include the database configuration file
    include 'db/config.php';

    // Get the cake ID from the URL
    if (isset($_GET['cake_id'])) {
      $cakeId = intval($_GET['cake_id']);

      // Fetch the cake details from the database
      $cakeQuery = "SELECT * FROM menu WHERE menu_id = $cakeId";
      $cakeResult = $conn->query($cakeQuery);

      if ($cakeResult && $cakeResult->num_rows > 0) {
        $cake = $cakeResult->fetch_assoc();
    ?>
        <!-- Cake Section -->
        <div class="flex flex-col md:flex-row max-w-5xl mx-auto bg-white rounded-lg shadow-lg p-6">
          <!-- Main Image and Thumbnails -->
          <div class="w-full md:w-1/2">
            <!-- Main Cake Image -->
            <img src="<?= htmlspecialchars($cake['image_path']) ?>" alt="<?= htmlspecialchars($cake['menu_name']) ?>" class="rounded-md w-full h-auto object-cover mb-4" />
          </div>

          <!-- Cake Details and Form -->
          <div class="w-full md:w-1/2 md:pl-10 mt-6 md:mt-0">
            <h2 class="text-md text-gray-500">Purple Cake Shop</h2>
            <h1 class="text-4xl font-bold mb-2 text-purple-600"><?= htmlspecialchars($cake['menu_name']) ?></h1>

            <!-- Star Ratings -->
            <div class="flex items-center mb-2">
              <span class="star text-2xl">★</span>
              <span class="star text-2xl">★</span>
              <span class="star text-2xl">★</span>
              <span class="star text-2xl">★</span>
              <span class="text-gray-500 ml-2">(4)</span>
            </div>

            <!-- Price -->
            <p class="text-2xl mb-4 font-semibold text-gray-800">₱<?= htmlspecialchars($cake['price']) ?></p>

            <!-- Quantity Input -->
            <form onsubmit="addToCart(event)">
              <div class="mb-4">
                <label for="quantity" class="block text-sm font-medium">Quantity</label>
                <div class="quantity-wrapper">
                  <button type="button" class="quantity-button" onclick="decrementQuantity()">-</button>
                  <input type="number" name="quantity" id="quantity" value="0" min="0" class="quantity-input" required />
                  <button type="button" class="quantity-button" onclick="incrementQuantity()">+</button>
                </div>
              </div>

              <!-- Pickup Date and Time (in one row) -->
              <div class="mb-4 date-time-wrapper">
                <!-- Pickup Date -->
                <div class="w-1/2">
                  <label for="pickup_date" class="block text-sm font-medium">Select Pickup Date</label>
                  <input type="date" name="pickup_date" id="pickup_date" class="border input-box rounded-md p-2 w-full" required />
                </div>

                <!-- Pickup Time -->
                <div class="w-1/2">
                  <label for="pickup_time" class="block text-sm font-medium">Select Time</label>
                  <select name="pickup_time" id="pickup_time" class="border input-box rounded-md p-2 w-full" required>
                    <option>09:00 AM - 09:30 AM</option>
                    <option>09:30 AM - 10:00 AM</option>
                    <!-- Add more time options as needed -->
                  </select>
                </div>
              </div>

              <!-- Personalized Greeting -->
              <div class="mb-4">
                <label for="greeting" class="block text-sm font-medium">Personalized Greeting/Dedication</label>
                <textarea name="greeting" id="greeting" rows="3" class="border input-box rounded-md p-2 w-full" placeholder="Enter your message here..."></textarea>
              </div>

              <input type="hidden" name="cake_id" value="<?= htmlspecialchars($cakeId) ?>" />

              <!-- Button Group -->
              <div class="button-group mb-4">
                <button type="submit" class="action-button">Add to Cart</button>
                <button type="button" class="action-button" onclick="buyNow(<?= htmlspecialchars($cakeId) ?>)">Buy Now</button>
              </div>
            </form>
          </div>
        </div>

    <?php
      } else {
        echo '<p class="text-red-500">Cake not found!</p>';
      }
    } else {
      echo '<p class="text-red-500">Invalid cake ID!</p>';
    }

    // Close the database connection
    $conn->close();
    ?>
  </div>

  <script>
    function toggleCartDropdown() {
      const cartDropdown = document.getElementById('cart-dropdown');
      cartDropdown.classList.toggle('active');
    }

    function incrementQuantity() {
      const quantityInput = document.getElementById('quantity');
      quantityInput.value = parseInt(quantityInput.value) + 1;
    }

    function decrementQuantity() {
      const quantityInput = document.getElementById('quantity');
      if (quantityInput.value > 0) {
        quantityInput.value = parseInt(quantityInput.value) - 1;
      }
    }

    function addToCart(event) {
      event.preventDefault();
      // Add your logic for adding to the cart here
      alert('Item added to cart!');
    }

    function buyNow(cakeId) {
      const quantityInput = document.getElementById('quantity');
      const quantity = parseInt(quantityInput.value);
      if (quantity > 0) {
        // Redirect to payment.php with cake ID and quantity as query parameters
        window.location.href = `payment.php?cake_id=${cakeId}&quantity=${quantity}`;
      } else {
        alert('Please select a quantity before proceeding to payment.');
      }
    }
  </script>
</body>

</html>