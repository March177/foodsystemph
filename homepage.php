<?php
// Include the database configuration file
include 'db/config.php'; // Ensure config.php is correctly set up for database connection

// Fetch Cake Categories from the database
$categoryQuery = "SELECT category_name FROM categories WHERE status = 1"; // status = 1 for active categories
$categoryResult = $conn->query($categoryQuery);

// Prepare an array to hold the latest images per category
$latestImages = [];

// Fetch the latest image for each category from the menu
$cakeCategoryQuery = "SELECT category_name, image_path 
               FROM menu  
               WHERE status = 1 
               AND category_name IS NOT NULL 
               ORDER BY c_id DESC"; // Assuming 'c_id' is the primary key and the highest value is the latest entry

$cakeCategoryResult = $conn->query($cakeCategoryQuery);

// Loop through menu items and group the latest image by category
if ($cakeCategoryResult && $cakeCategoryResult->num_rows > 0) {
  while ($cake = $cakeCategoryResult->fetch_assoc()) {
    $category = htmlspecialchars($cake['category_name']);
    if (!isset($latestImages[$category])) {
      $latestImages[$category] = htmlspecialchars($cake['image_path']); // Store the latest image for the category
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cake Shop</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    /* Navbar styles */
    .navbar {
      transition: background-color 0.5s ease, box-shadow 0.5s ease;
      position: fixed;
      width: 100%;
      top: 0;
      z-index: 10;
    }

    .navbar.transparent {
      background-color: rgba(255, 255, 255, 0.5);
      /* Transparent */
      box-shadow: none;
    }

    .navbar.scrolled {
      background-color: rgba(255, 255, 255, 1);
      /* Solid */
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    body {
      padding-top: 80px;
      /* To prevent content from being hidden behind the fixed navbar */
    }

    /* Slider styles */
    .slider {
      position: relative;
      height: 100vh;
      width: 100%;
      overflow: hidden;
      /* Hide overflow for sliding effect */
    }

    .slide {
      position: absolute;
      top: 0;
      left: 0;
      /* Start all slides at the same position */
      height: 100%;
      width: 100%;
      transition: transform 1s ease;
      /* Transition effect */
      opacity: 0;
      /* Hide slides initially */
    }

    .slide.active {
      opacity: 1;
      /* Show active slide */
      transform: translateX(0);
      /* Keep active slide in place */
    }

    .slide.prev {
      transform: translateX(-100%);
      /* Move previous slide off to the left */
      opacity: 0;
      /* Hide previous slide */
    }

    .slide.next {
      transform: translateX(100%);
      /* Move next slide off to the right */
      opacity: 0;
      /* Hide next slide */
    }

    /* Dropdown styles */
    .dropdown {
      position: relative;
      display: inline-block;
    }

    .dropdown-content {
      display: none;
      position: absolute;
      background-color: white;
      min-width: 160px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
      z-index: 1;
    }

    .dropdown:hover .dropdown-content {
      display: block;
    }

    .dropdown-content a {
      color: black;
      padding: 12px 16px;
      text-decoration: none;
      display: block;
    }

    .dropdown-content a:hover {
      background-color: #f1f1f1;
    }
  </style>
</head>

<body class="bg-gray-50 text-gray-800">
  <!-- Navbar -->
  <nav class="navbar transparent">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center py-4">
        <a href="#" class="text-3xl font-bold text-pink-500">Cake Shop</a>
      </div>
      <div class="flex justify-center space-x-6 py-2">
        <a href="#" class="text-gray-800 hover:text-pink-500">Home</a>
        <div class="dropdown">
          <a href="#" class="text-gray-800 hover:text-pink-500">Cake Menu</a>
          <div class="dropdown-content">
            <a href="cakes.php?category=all">All Cakes</a>
            <a href="cakes.php?category=wedding">Wedding</a>
            <a href="cakes.php?category=birthday">Birthday</a>
            <a href="cakes.php?category=cupcakes">Cupcakes</a>
          </div>
        </div>
        <a href="#payments" class="text-gray-800 hover:text-pink-500">Mode of Payments</a>
        <a href="#about" class="text-gray-800 hover:text-pink-500">About Us</a>
        <a href="#contact" class="text-gray-800 hover:text-pink-500">Contact Us</a>
      </div>
    </div>
  </nav>

  <section class="relative h-screen overflow-hidden">
    <div class="slider">
      <!-- Initially active slide -->
      <div class="slide bg-cover bg-center h-full active" style="background-image: url('img/menu/bday2.png');"></div>
      <div class="slide bg-cover bg-center h-full" style="background-image: url('img/menu/bday1.png');"></div>
      <div class="slide bg-cover bg-center h-full" style="background-image: url('img/menu/bday3.png');"></div>
    </div>
    <div class="absolute inset-0 flex flex-col justify-center items-center text-black">
      <h1 class="text-black text-4xl sm:text-6xl font-bold">Delicious Cakes for Every Occasion</h1>
      <p class="text-black mt-4 text-lg">Order your favorite cakes online</p>
      <a href="cakes.php" class="mt-6 px-8 py-3 bg-pink-500 text-white rounded-lg shadow hover:bg-pink-600">Shop Now</a>
    </div>
  </section>


  <script>
    const slides = document.querySelectorAll('.slide');
    let currentIndex = 0;

    function showSlide(index) {
      slides.forEach((slide, i) => {
        slide.classList.remove('active', 'prev', 'next');
        if (i === index) {
          slide.classList.add('active'); // Show active slide
        } else if (i === (index - 1 + slides.length) % slides.length) {
          slide.classList.add('prev'); // Previous slide
        } else if (i === (index + 1) % slides.length) {
          slide.classList.add('next'); // Next slide
        }
      });
    }

    function nextSlide() {
      currentIndex = (currentIndex + 1) % slides.length; // Loop back to the first slide
      showSlide(currentIndex);
    }

    // Initial slide display
    showSlide(currentIndex);
    setInterval(nextSlide, 5000); // Change slide every 5 seconds
  </script>

  <!-- About Section -->
  <section id="about" class="py-12 bg-white text-center">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
      <h2 class="text-3xl font-semibold">About Us</h2>
      <p class="mt-4 text-lg text-gray-600">We specialize in crafting cakes that are as delicious as they are beautiful. Whether you’re celebrating a wedding, birthday, or any special event, we have a cake for every occasion.</p>
    </div>
  </section>

  <section id="categories" class="py-12 bg-gray-100">
    <div class="max-w-6xl mx-auto px-4">
      <h2 class="text-3xl font-semibold text-center">Our Cake Categories</h2>

      <div class="flex justify-between items-center mt-8">
        <!-- Grid of Cake Categories -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
          <?php
          // Loop through categories and display them
          if ($categoryResult->num_rows > 0) {
            while ($category = $categoryResult->fetch_assoc()) {
              $categoryName = htmlspecialchars($category['category_name']);
              $latestImage = isset($latestImages[$categoryName]) ? $latestImages[$categoryName] : 'images/category-placeholder.jpg'; // Placeholder image if none found
              echo '
                                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                                    <a href="cakes.php?category=' . urlencode($categoryName) . '">
                                        <img src="' . $latestImage . '" alt="' . $categoryName . ' image" class="w-full h-48 object-cover rounded-lg mb-4">
                                        <h3 class="text-xl font-semibold text-center">' . $categoryName . '</h3>
                                    </a>
                                </div>
                            ';
            }
          } else {
            echo '<p class="text-center text-gray-600">No categories found.</p>';
          }
          ?>
        </div>
      </div>
    </div>
  </section>
</body>

</html>