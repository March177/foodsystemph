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
</head>

<body class="bg-gray-50 text-gray-800">
  <!-- Hero Section -->
  <section class="relative bg-cover bg-center h-screen" style="background-image: url('cake-hero.jpg');">
    <div class="absolute inset-0 bg-black bg-opacity-50 flex flex-col justify-center items-center">
      <h1 class="text-white text-4xl sm:text-6xl font-bold">Delicious Cakes for Every Occasion</h1>
      <p class="text-gray-200 mt-4 text-lg">Order your favorite cakes online</p>
      <a href="#cakes" class="mt-6 px-8 py-3 bg-pink-500 text-white rounded-lg shadow hover:bg-pink-600">Shop Now</a>
    </div>
  </section>

  <!-- About Section -->
  <section id="about" class="py-12 bg-white text-center">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
      <h2 class="text-3xl font-semibold">About Us</h2>
      <p class="mt-4 text-lg text-gray-600">We specialize in crafting cakes that are as delicious as they are beautiful. Whether you’re celebrating a wedding, birthday, or any special event, we have a cake for every occasion.</p>
      <img src="about-cake.jpg" alt="About Cake" class="mt-6 mx-auto w-3/4 sm:w-1/2 rounded-lg shadow-lg">
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
              $latestImage = isset($latestImages[$categoryName]) ? $latestImages[$categoryName] : 'category-placeholder.jpg'; // Default image if none found

              // Wrap the image and category name in an anchor tag that links to cakes.php
              echo '
            <div class="bg-white rounded-lg shadow-lg p-4">
              <a href="cakes.php?category=' . urlencode($categoryName) . '">
                <img src="' . $latestImage . '" alt="' . $categoryName . '" class="rounded-md w-full h-48 object-cover">
                <h3 class="text-xl font-semibold mt-4">' . $categoryName . '</h3>
              </a>
            </div>';
            }
          } else {
            echo "<p class='text-center text-red-500'>No categories found.</p>";
          }
          ?>
        </div>
      </div>
    </div>
  </section>





  <!-- Testimonials Section -->
  <section id="testimonials" class="py-12 bg-gray-100">
    <div class="max-w-6xl mx-auto px-4 text-center">
      <h2 class="text-3xl font-semibold">What Our Customers Say</h2>
      <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow-lg p-6">
          <p class="italic">"The best cakes in town! The chocolate cake was a hit at my party!"</p>
          <h3 class="font-semibold mt-4">- Sarah P.</h3>
        </div>
      </div>
    </div>
  </section>

  <!-- Contact Section -->
  <section id="contact" class="py-12 bg-white">
    <div class="max-w-6xl mx-auto px-4 text-center">
      <h2 class="text-3xl font-semibold">Get In Touch</h2>
      <p class="mt-4 text-lg text-gray-600">Contact us for custom cake orders or any inquiries.</p>
      <form class="mt-8 max-w-lg mx-auto" action="contact.php" method="POST">
        <input type="text" name="name" placeholder="Your Name" class="w-full p-3 border rounded-md mb-4" required />
        <input type="email" name="email" placeholder="Your Email" class="w-full p-3 border rounded-md mb-4" required />
        <textarea name="message" placeholder="Your Message" class="w-full p-3 border rounded-md mb-4" required></textarea>
        <button type="submit" class="px-8 py-3 bg-pink-500 text-white rounded-lg shadow hover:bg-pink-600">Send Message</button>
      </form>
    </div>
  </section>

  <!-- Footer -->
  <footer class="bg-gray-900 text-white py-6 text-center">
    <p>&copy; 2024 Cake Shop. All rights reserved.</p>
    <div class="mt-4 space-x-4">
      <a href="#" class="text-pink-500 hover:text-pink-400">Facebook</a>
      <a href="#" class="text-pink-500 hover:text-pink-400">Instagram</a>
    </div>
  </footer>
</body>

</html>