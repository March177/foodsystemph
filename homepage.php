<?php
include 'db/config.php';

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Fetch active Cake Categories from the database
$categoryQuery = "SELECT DISTINCT category_name FROM categories WHERE status = 1"; // status = 1 for active categories
$categoryResult = $conn->query($categoryQuery);

if (!$categoryResult) {
  echo "<p>Error fetching categories. Please try again later.</p>";
  exit; // End execution if the query fails
}

// Prepare an array to hold the latest images per category
$latestImages = [];

// Fetch the latest image for each category from the menu
$cakeCategoryQuery = "SELECT category_name, image_path
FROM menu
WHERE status = 1
AND category_name IS NOT NULL
ORDER BY c_id DESC"; // Assuming 'c_id' is the primary key

$cakeCategoryResult = $conn->query($cakeCategoryQuery);

if ($cakeCategoryResult && $cakeCategoryResult->num_rows > 0) {
  while ($cake = $cakeCategoryResult->fetch_assoc()) {
    $category = htmlspecialchars($cake['category_name']);
    // Store the latest image for the category only if it hasn't been set yet
    if (!isset($latestImages[$category])) {
      $latestImages[$category] = htmlspecialchars($cake['image_path']);
    }
  }
}

// Fetch all cakes from the menu with optional filtering
$filter = isset($_GET['category']) ? $_GET['category'] : 'all';
$priceFilter = isset($_GET['price']) ? $_GET['price'] : '';

// Base query to fetch cakes
$cakesQuery = "SELECT menu_id, menu_name, price, image_path, category_name
FROM menu
WHERE status = 1";

// Apply category filter if selected
if ($filter !== 'all') {
  $cakesQuery .= " AND category_name = '" . $conn->real_escape_string($filter) . "'";
}

// Price filter handling
if ($priceFilter) {
  list($minPrice, $maxPrice) = explode('-', $priceFilter);
  $cakesQuery .= " AND price BETWEEN " . (int)$minPrice . " AND " . (int)$maxPrice;
}

// Fetch cakes based on filters
$cakesResult = $conn->query($cakesQuery);

if (!$cakesResult) {
  echo "<p>Error fetching cakes. Please try again later.</p>";
  exit; // End execution if the query fails
}

// Prepare an array to hold cakes grouped by category
$cakesByCategory = [];
if ($cakesResult->num_rows > 0) {
  while ($cake = $cakesResult->fetch_assoc()) {
    $cakesByCategory[$cake['category_name']][] = $cake;
  }
}

// Fetch categories again for the Cake Menu section filter
$categoryFilterResult = $conn->query($categoryQuery);

// Determine which section to display based on the query parameter
$section = isset($_GET['section']) ? $_GET['section'] : 'home';
$showHome = ($section === 'home');
$showCakeMenu = ($section === 'cakemenu');
$showPayments = ($section === 'payments');
$showAbout = ($section === 'about');
$showContact = ($section === 'contact');

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cake Shop</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    .navbar {
      transition: background-color 0.5s ease, box-shadow 0.5s ease;
      position: fixed;
      width: 100%;
      top: 0;
      z-index: 10;
      padding: 20px 0;
      background-color: rgba(255, 255, 255, 0.5);
      /* Transparent background */
    }

    .navbar.transparent {
      background-color: rgba(255, 255, 255, 0.5);
      /* Transparent background */
      box-shadow: none;
      /* No shadow when transparent */
    }


    .navbar.scrolled {
      background-color: rgba(255, 255, 255, 1);
      /* Solid background when scrolled */
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    body {
      padding-top: 80px;
    }

    .navbar a {
      font-size: 1.25rem;
      margin: 0 20px;
      /* Adjusted margin for better spacing */
      transition: color 0.3s;
      /* Smooth color transition */
    }

    .navbar .text-3xl {
      font-size: 2rem;
      /* Increased font size for the title */
    }

    .dropdown {
      position: relative;
      /* Position relative for the dropdown */
    }

    .dropdown-content {
      display: none;
      /* Initially hidden */
      position: absolute;
      /* Position absolute for dropdown items */
      background-color: white;
      /* Background color */
      min-width: 160px;
      /* Minimum width for dropdown */
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
      /* Shadow for dropdown */
      z-index: 10;
      /* Ensure it appears above other elements */
    }

    .dropdown:hover .dropdown-content {
      display: block;
      /* Show dropdown on hover */
    }

    .dropdown-item {
      padding: 12px 16px;
      /* Increased padding for better spacing */
      color: #4A5568;
      /* Default text color */
      text-decoration: none;
      /* Remove underline */
      display: block;
      /* Ensure block display for full width */
      transition: background-color 0.3s;
      /* Smooth background transition */
    }

    .dropdown-item:hover {
      background-color: #f472b6;
      /* Change background on hover */
      color: white;
      /* Change text color on hover */
    }

    .cake-details-container {
      display: flex;
      justify-content: center;
      align-items: stretch;
      /* Ensure both image and card stretch to the same height */
      max-width: 1200px;
      margin: 0 auto;
      margin-top: 100px;
    }

    .cake-image-large {
      max-width: 500px;
      width: 100%;
      height: 100%;
      /* Ensure the image takes the full height of its container */
      margin-right: 30px;
      border-radius: 12px;
      object-fit: cover;
      /* Ensure the image scales nicely */
    }

    .cake-card {
      background-color: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 8px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      padding: 5px;
      flex-grow: 1;
      height: 100%;
      /* Ensure the card takes the full height of its container */
      max-width: 700px;
      width: 100%;
      display: flex;
      flex-direction: column;
    }

    .cake-details-text {
      text-align: left;
      margin-left: 30px;
      max-width: 900px;
      line-height: 1;
    }

    .cake-details-text h4 {
      font-size: 2.5rem;
      margin-bottom: 5px;
      line-height: 1;
    }

    .cake-details-text p {
      font-size: 1rem;
      margin-bottom: 10px;
      line-height: 1;
    }

    .cake-actions {
      display: flex;
      align-items: center;
      margin-top: 10px;
    }

    .quantity-control {
      display: flex;
      align-items: center;
    }

    .quantity-btn {
      background-color: #e2e8f0;
      color: #333;
      border: none;
      border-radius: 4px;
      padding: 10px;
      cursor: pointer;
      transition: background-color 0.3s;
      margin-right: 10px;
    }

    .quantity-btn:hover {
      background-color: #d1d5db;
    }

    .quantity-input {
      width: 80px;
      margin-left: 10px;
      border-radius: 4px;
      border: 1px solid #e2e8f0;
      padding: 10px;
      font-size: 1.2rem;
      text-align: center;
    }

    .btn {
      padding: 10px 20px;
      margin-right: 10px;
      background-color: #e63946;
      color: #ffffff;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      transition: background-color 0.3s;
      font-size: 1.2rem;
    }

    .btn:hover {
      background-color: #d62839;
    }


    .pickup-section,
    .message-section {
      margin-top: 20px;
    }

    .pickup-section label,
    .message-section label {
      font-size: 1.2rem;
      margin-bottom: 5px;
      display: block;
    }


    .message-section {
      margin-bottom: 20px;
      /* Add space below the message section */
    }

    .add-to-cart {
      align-self: flex-end;
      /* Align button to the start of the card */
      margin-top: 5px;
    }

    .cart-icon {
      position: relative;
      display: flex;
      align-items: center;
    }

    .cart-icon img {
      width: 50px;
      height: 50px;
      right: 100px;
    }


    .cart-counter {
      position: absolute;
      top: -5px;
      /* Adjust position */
      right: -10px;
      /* Adjust position */
      background-color: #f472b6;
      color: white;
      border-radius: 50%;
      padding: 2px 6px;
      /* Adjust padding */
      font-size: 0.75rem;
    }


    input[type="date"],
    input[type="time"],
    textarea {
      margin-top: 10px;
      padding: 12px;
      border: 1px solid #e2e8f0;
      border-radius: 4px;
      width: 100%;
      font-size: 1rem;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      .cake-details-container {
        flex-direction: column;
        align-items: center;
      }

      .cake-image-large {
        max-width: 100%;
        margin-right: 0;
        margin-bottom: 20px;
        /* Add spacing between image and text */
      }

      .cake-details-text {
        margin-left: 0;
        text-align: center;
      }
    }
  </style>
  </style>
</head>

<body class="bg-gray-50 text-gray-800">
  <!-- Navbar -->
  <nav class="navbar transparent">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center py-4">
        <a href="?section=home" class="text-3xl font-bold text-pink-500">Cake Shop</a>
      </div>
      <div class="flex justify-center py-2" style="gap: 5px;"> <!-- Set gap to 50 pixels -->
        <a href="?section=home" class="text-gray-800 hover:text-pink-500">Home</a>
        <!-- Dropdown for Cake Menu -->
        <div class="dropdown">
          <a href="?section=cakemenu" class="text-gray-800 hover:text-pink-500">Cake Menu</a>
          <div class="dropdown-content">
            <a href="?section=cakemenu&category=all" class="dropdown-item">All Cakes</a>
            <a href="?section=cakemenu&category=wedding cakes" class="dropdown-item">Wedding Cakes</a>
            <a href="?section=cakemenu&category=birthday cakes" class="dropdown-item">Birthday Cakes</a>
            <a href="?section=cakemenu&category=cupcakes" class="dropdown-item">Cupcakes</a>
          </div>
        </div>
        <a href="?section=payments" class="text-gray-800 hover:text-pink-500">Mode of Payments</a>
        <a href="?section=about" class="text-gray-800 hover:text-pink-500">About Us</a>
        <a href="?section=contact" class="text-gray-800 hover:text-pink-500">Contact Us</a>


        <div class="cart-icon" style="float: right; padding-right: 20px;">
          <a href="cart.php" class="text-gray-800 hover:text-pink-500">
            <img src="img/menu/cart.png" alt="Cart" style="width: 30px; height: 30px;">
            <span class="cart-counter" id="cart-counter">0</span>
          </a>
        </div>

      </div>
    </div>
  </nav>



  <!-- Home Section -->
  <?php if ($showHome): ?>
    <section class="relative h-screen overflow-hidden">
      <div class="slider">
        <div class="slide bg-cover bg-center h-full active" style="background-image: url('img/menu/bday2.png');"></div>
        <div class="slide bg-cover bg-center h-full" style="background-image: url('img/menu/bday1.png');"></div>
        <div class="slide bg-cover bg-center h-full" style="background-image: url('img/menu/bday3.png');"></div>
      </div>
      <div class="absolute inset-0 flex flex-col justify-center items-center text-black">
        <h1 class="text-black text-4xl sm:text-6xl font-bold">Delicious Cakes for Every Occasion</h1>
        <p class="text-black mt-4 text-lg">Order your favorite cakes online</p>
        <a href="?section=cakemenu&category=all" class="mt-6 px-8 py-3 bg-pink-500 text-white rounded-lg shadow hover:bg-pink-600">Shop Now</a>
      </div>
    </section>

    <!-- Categories Section -->
    <section id="categories" class="py-12 bg-gray-100">
      <div class="max-w-6xl mx-auto px-4">
        <h2 class="text-3xl font-semibold text-center">Our Cake Categories</h2>
        <div class="mt-6">
          <?php if ($categoryResult && $categoryResult->num_rows > 0): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mt-4">
              <?php while ($category = $categoryResult->fetch_assoc()): ?>
                <div class="mt-8">
                  <div class="bg-white rounded-lg shadow p-4 text-center">
                    <a href="?section=cakemenu&category=<?= htmlspecialchars($category['category_name']); ?>">
                      <img src="<?= $latestImages[htmlspecialchars($category['category_name'])] ?? 'img/default.png'; ?>" alt="<?= htmlspecialchars($category['category_name']); ?>" class="rounded-md mb-2 h-32 w-full object-cover">
                      <h3 class="font-semibold text-lg"><?= htmlspecialchars($category['category_name']); ?></h3>
                    </a>
                  </div>
                </div>
              <?php endwhile; ?>
            </div>
          <?php else: ?>
            <p class="text-center">No categories available.</p>
          <?php endif; ?>
        </div>
      </div>
    </section>
  <?php endif; ?>




  <!-- Cake Menu Section -->
  <?php if ($showCakeMenu): ?>
    <section class="py-12 bg-gray-100 pt-100 mt-20"> <!-- Add mt-20 for top margin -->
      <div class="max-w-6xl mx-auto px-4">
        <h2 class="text-3xl font-semibold text-center">Our Cakes</h2>

        <!-- Filters -->
        <form method="GET" action="" id="filterForm">
          <input type="hidden" name="section" value="cakemenu">
          <div class="flex justify-center gap-6 mt-6">
            <div class="flex items-center"> <!-- Align items in the center -->
              <label for="category" class="text-lg font-medium text-gray-700 mr-2">Filter by Category:</label> <!-- Added margin to the right -->
              <select name="category" id="category" class="py-2 px-3 border border-gray-300 bg-white rounded-md" onchange="this.form.submit()">
                <option value="all">All</option>
                <?php if ($categoryFilterResult && $categoryFilterResult->num_rows > 0): ?>
                  <?php while ($category = $categoryFilterResult->fetch_assoc()): ?>
                    <option value="<?= htmlspecialchars($category['category_name']); ?>" <?= $filter === htmlspecialchars($category['category_name']) ? 'selected' : ''; ?>>
                      <?= htmlspecialchars($category['category_name']); ?>
                    </option>
                  <?php endwhile; ?>
                <?php endif; ?>
              </select>
            </div>
            <div class="flex items-center"> <!-- Align items in the center -->
              <label for="price" class="text-lg font-medium text-gray-700 mr-2">Filter by Price:</label> <!-- Added margin to the right -->
              <select name="price" id="price" class="py-2 px-3 border border-gray-300 bg-white rounded-md" onchange="this.form.submit()">
                <option value="">All Prices</option>
                <option value="100-200" <?= $priceFilter === '100-200' ? 'selected' : ''; ?>>100 - 200</option>
                <option value="300-500" <?= $priceFilter === '300-500' ? 'selected' : ''; ?>>300 - 500</option>
                <option value="600-1000" <?= $priceFilter === '600-1000' ? 'selected' : ''; ?>>600 - 1000</option>
              </select>
            </div>
          </div>
        </form>

        <!-- Cakes List -->
        <div id="cakes-list" class="grid grid-cols-1 mt-8">
          <?php if (!empty($cakesByCategory)): ?>
            <?php foreach ($cakesByCategory as $category => $cakes): ?>
              <div class="mt-8">
                <h3 class="font-semibold text-lg"><?= htmlspecialchars($category); ?></h3>
                <div class="flex overflow-x-auto gap-4 mt-4">
                  <?php foreach ($cakes as $cake): ?>
                    <a href="#" class="cake-item bg-white rounded-lg shadow p-4 text-center flex-none" style="width: 200px;" data-name="<?= htmlspecialchars($cake['menu_name']); ?>" data-price="<?= number_format($cake['price'], 2); ?>" data-image="<?= htmlspecialchars($cake['image_path']); ?>"> <!-- Added data attributes -->
                      <img src="<?= htmlspecialchars($cake['image_path']); ?>" alt="<?= htmlspecialchars($cake['menu_name']); ?>" class="rounded-md mb-2 h-32 w-full object-cover">
                      <h4 class="font-semibold"><?= htmlspecialchars($cake['menu_name']); ?></h4>
                      <p class="text-gray-700">$<?= number_format($cake['price'], 2); ?></p>
                    </a>
                  <?php endforeach; ?>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <p class="text-center">No cakes available for this filter.</p>
          <?php endif; ?>
        </div>

    </section>
  <?php endif; ?>


  <!-- Cake Details Section -->
  <div id="cake-details" class="mt-4 hidden">
    <div class="cake-details-container">
      <!-- Cake Image -->
      <img id="cake-image" src="cake-image.jpg" alt="Cake Image" class="cake-image-large" style="display: block;">
      <div class="cake-card">
        <div class="cake-details-text">
          <!-- Cake Name -->
          <h4 id="cake-name" class="font-semibold text-xl text-gray-800">Chocolate Cake</h4>
          <!-- Cake Price -->
          <p class="text-lg font-bold text-gray-700" id="cake-price">₱600.00</p>

          <!-- Quantity Section -->
          <div class="cake-actions">
            <label for="quantity" class="text-sm text-gray-600">Quantity:</label>
            <div class="quantity-control flex items-center">
              <!-- Quantity Controls -->
              <button type="button" id="decrease-quantity" class="quantity-btn text-gray-600">-</button>
              <input type="number" id="quantity" name="quantity" value="1" min="1" class="quantity-input mx-2 w-16 text-center" readonly>
              <button type="button" id="increase-quantity" class="quantity-btn text-gray-600">+</button>
            </div>
          </div>

          <!-- Boxed Pickup Date and Time Section -->
          <div class="pickup-box mt-4">
            <div class="pickup-section">
              <label for="pickup-date">Pickup Date:</label>
              <input type="date" id="pickup-date" name="pickup-date" required>
            </div>
            <div class="pickup-section">
              <label for="pickup-time">Pickup Time:</label>
              <input type="time" id="pickup-time" name="pickup-time" required>
            </div>
          </div>

          <!-- Message Section (Textarea) -->
          <div class="message-section mt-4">
            <label for="cake-message">Message on Cake:</label>
            <textarea id="cake-message" name="cake-message" rows="4" placeholder="Enter your message here" class="w-full p-2 mt-2 border border-gray-300 rounded-lg"></textarea>
          </div>

          <!-- Add to Cart Button -->
          <button class="add-to-cart btn mt-4" id="add-to-cart-btn">Add to Cart</button>
        </div>
      </div>
    </div>
  </div>




  <script>
    document.addEventListener('DOMContentLoaded', function() {
      var navbar = document.querySelector('.navbar');

      // Initially set navbar to transparent
      navbar.classList.add('transparent');

      window.addEventListener('scroll', function() {
        if (window.scrollY > 50) { // When scrolled more than 50 pixels
          navbar.classList.add('scrolled');
          navbar.classList.remove('transparent');
        } else {
          navbar.classList.remove('scrolled');
          navbar.classList.add('transparent');
        }
      });

      // Update cart counter from localStorage
      updateCartCounter();

      document.getElementById('add-to-cart-btn').addEventListener('click', function(event) {
        const pickupDate = document.getElementById('pickup-date').value;
        const pickupTime = document.getElementById('pickup-time').value;

        if (!pickupDate || !pickupTime) {
          event.preventDefault(); // Prevent form submission
          alert('Please select both pickup date and time.'); // Alert the user
        } else {
          addToCart(); // Call addToCart function if validations pass
        }
      });

      document.getElementById('increase-quantity').addEventListener('click', function() {
        const quantityInput = document.getElementById('quantity');
        quantityInput.value = parseInt(quantityInput.value) + 1;
      });

      document.getElementById('decrease-quantity').addEventListener('click', function() {
        const quantityInput = document.getElementById('quantity');
        if (quantityInput.value > 1) {
          quantityInput.value = parseInt(quantityInput.value) - 1;
        }
      });

      const cakeItems = document.querySelectorAll('.cake-item');
      const cakesList = document.getElementById('cakes-list');
      const cakeDetails = document.getElementById('cake-details');
      const cakeImage = document.getElementById('cake-image');
      const cakeName = document.getElementById('cake-name');
      const cakePrice = document.getElementById('cake-price');

      cakeItems.forEach(item => {
        item.addEventListener('click', function(event) {
          event.preventDefault(); // Prevent the default anchor behavior

          // Get cake details from data attributes
          const image = item.getAttribute('data-image');
          const name = item.getAttribute('data-name');
          const price = item.getAttribute('data-price');

          // Update the cake details section
          cakeImage.src = image; // Set the image source
          cakeImage.alt = name; // Set the image alt text
          cakeName.textContent = name; // Set the cake name
          cakePrice.textContent = `₱${price}`; // Set the cake price

          // Hide the cakes list and show the cake details
          cakesList.style.display = 'none'; // Hide the cakes list
          document.querySelector('section.py-12').style.display = 'none'; // Hide the cake menu section
          cakeImage.style.display = 'block'; // Show the image
          cakeDetails.classList.remove('hidden'); // Show the details section
          cakeDetails.scrollIntoView({
            behavior: 'smooth'
          }); // Scroll to the details section
        });
      });
    });

    const cartCounter = document.getElementById('cart-counter');

    // Function to add item to the cart and manage Local Storage
    function addToCart() {
      const cakeName = document.getElementById('cake-name').textContent;
      const cakePrice = parseFloat(document.getElementById('cake-price').textContent.replace('₱', ''));
      const quantity = parseInt(document.getElementById('quantity').value);
      const pickupDate = document.getElementById('pickup-date').value;
      const pickupTime = document.getElementById('pickup-time').value;
      const cakeMessage = document.getElementById('cake-message').value;
      const cakeImageSrc = document.getElementById('cake-image').src; // Get the image source

      // Validation checks
      if (quantity < 1) {
        alert("Please select a valid quantity.");
        return;
      }

      if (!pickupDate) {
        alert("Please select a pickup date.");
        return;
      }

      if (!pickupTime) {
        alert("Please select a pickup time.");
        return;
      }

      // Prepare cart item object
      const cartItem = {
        name: cakeName,
        price: cakePrice,
        quantity: quantity,
        pickupDate: pickupDate,
        pickupTime: pickupTime,
        message: cakeMessage,
        image: cakeImageSrc // Store the image source
      };

      // Get existing cart from Local Storage
      let cart = JSON.parse(localStorage.getItem('cartItems')) || [];

      // Check if the item is already in the cart
      const existingItemIndex = cart.findIndex(item =>
        item.name === cartItem.name &&
        item.pickupDate === cartItem.pickupDate &&
        item.pickupTime === cartItem.pickupTime
      );

      if (existingItemIndex !== -1) {
        // Item already exists in the cart, replace it
        cart[existingItemIndex] = cartItem;
        alert(`${cakeName} has been updated in your cart!`);
      } else {
        // Add new item to the cart
        cart.push(cartItem);
        alert(`${cakeName} has been added to your cart!`);
      }

      // Update Local Storage
      localStorage.setItem('cartItems', JSON.stringify(cart));
      updateCartCounter();
    }

    // Function to update the cart counter
    function updateCartCounter() {
      const cart = JSON.parse(localStorage.getItem('cartItems')) || [];
      cartCounter.textContent = cart.length;
    }

    // Function to checkout and clear the cart
    function checkout() {
      // Clear the cart from Local Storage
      localStorage.removeItem('cartItems');
      updateCartCounter();
      alert("Checkout successful! Your cart has been cleared.");
    }
  </script>


</body>

</html>