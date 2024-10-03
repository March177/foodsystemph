<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Area</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.1/dist/tailwind.min.css" rel="stylesheet">
    <script>
        // JavaScript to populate order details
        document.addEventListener("DOMContentLoaded", function() {
            const params = getQueryParams();

            // Fetching item details from URL parameters
            const itemName = params.name;
            const price = parseFloat(params.price);
            const quantity = parseInt(params.quantity);

            // Ensure all parameters are present and valid
            if (itemName && !isNaN(price) && price >= 0 && !isNaN(quantity) && quantity > 0) {
                const delivery = "Free";
                const subtotal = price * quantity;
                const total = subtotal; // Adjust total calculation if necessary

                // Populate order details in the HTML
                document.getElementById("item-name").textContent = itemName;
                document.getElementById("item-price").textContent = `₱${price.toFixed(2)}`;
                document.getElementById("item-subtotal").textContent = `₱${subtotal.toFixed(2)}`;
                document.getElementById("item-delivery").textContent = delivery;
                document.getElementById("item-total").textContent = `₱${total.toFixed(2)}`;
            } else {
                // Handle missing or invalid parameters
                alert("Invalid order details. Please check your selection.");
            }
        });

        // Function to get URL parameters
        function getQueryParams() {
            const params = {};
            const queryString = window.location.search.substring(1);
            const queryArray = queryString.split('&');

            queryArray.forEach(param => {
                const [key, value] = param.split('=');
                params[decodeURIComponent(key)] = decodeURIComponent(value.replace(/\+/g, ' '));
            });

            return params;
        }
    </script>
</head>

<body class="bg-gray-100">
    <div class="container mx-auto p-5 grid grid-cols-1 md:grid-cols-2 gap-5">
        <!-- Left Side: Payment Area -->
        <div class="bg-white p-5 rounded-md shadow-md">
            <h1 class="text-3xl font-bold mb-5">Payment Area</h1>

            <!-- Contact Information -->
            <div class="mb-5">
                <h2 class="text-xl font-semibold">Contact</h2>
                <input type="email" placeholder="Email" class="w-full p-2 border border-gray-300 rounded-md mt-2">
                <label class="flex items-center mt-2">
                    <input type="checkbox" class="mr-2">
                    Email me my order updates
                </label>
            </div>

            <!-- Delivery Section -->
            <div class="mb-5">
                <h2 class="text-xl font-semibold">Delivery</h2>
                <p>There is 1 store with stock close to your location</p>
                <div class="mt-2 p-2 border border-gray-300 rounded-md">
                    <p class="text-gray-600">UNTIL 8:00PM</p>
                    <button class="text-blue-500 underline">Change my location</button>
                </div>
            </div>

            <!-- Payment Section -->
            <div class="mb-5">
                <h2 class="text-xl font-semibold">Payment Method</h2>
                <p class="text-gray-600">All transactions are secure and encrypted.</p>
                <!-- Payment Information -->
                <div class="bg-gray-100 p-4 rounded-md mt-4">
                    <div class="flex items-center justify-center mb-4">
                        <!-- PayMongo logo or icon -->
                        <img src="https://paymongo.com/favicon.ico" alt="Secure Payments via PayMongo" class="w-24 h-auto">
                    </div>
                    <p class="text-center text-lg font-semibold">Secure Payments via PayMongo</p>
                    <p class="text-center text-sm text-gray-600 mt-2">After clicking "Pay now", you will be redirected to Secure Payments via PayMongo to complete your purchase securely.</p>
                </div>
            </div>
            <form action="process_payment.php" method="POST">
                <!-- Left Side: Payment Area -->
                <div class="bg-white p-5 rounded-md shadow-md">
                    <!-- Billing Address -->
                    <div class="mb-5">
                        <h2 class="text-xl font-semibold">Billing Address</h2>
                        <div class="grid grid-cols-2 gap-4 mt-4">
                            <input type="text" name="first_name" placeholder="First name" class="p-2 border border-gray-300 rounded-md" required>
                            <input type="text" name="last_name" placeholder="Last name" class="p-2 border border-gray-300 rounded-md" required>
                            <input type="text" name="address" placeholder="Address" class="col-span-2 p-2 border border-gray-300 rounded-md" required>
                            <input type="text" name="postal_code" placeholder="Postal code" class="p-2 border border-gray-300 rounded-md" required>
                            <input type="text" name="city" placeholder="City" class="p-2 border border-gray-300 rounded-md" required>
                        </div>
                    </div>

                    <!-- Pay Now Button -->
                    <button type="submit" class="bg-blue-600 text-white w-full p-3 rounded-md hover:bg-blue-700 mt-4">Pay now</button>
                </div>
            </form>
        </div>

        <!-- Right Side: My Order Summary -->
        <div class="bg-white p-5 rounded-md shadow-md">
            <h1 class="text-3xl font-bold mb-5">My Order</h1>
            <div class="border-t border-gray-300 pt-2">
                <p class="flex justify-between mb-2"><span>Item:</span> <span id="item-name"></span></p>
                <p class="flex justify-between mb-2"><span>Price:</span> <span id="item-price"></span></p>
                <p class="flex justify-between mb-2"><span>Subtotal:</span> <span id="item-subtotal"></span></p>
                <p class="flex justify-between mb-2"><span>Delivery:</span> <span id="item-delivery"></span></p>
                <p class="flex justify-between font-bold"><span>Total:</span> <span id="item-total"></span></p>
            </div>
            <div class="mt-4">
                <h2 class="text-xl font-semibold">Discount Code</h2>
                <div class="flex">
                    <input type="text" placeholder="Enter code" class="flex-grow p-2 border border-gray-300 rounded-md">
                    <button class="bg-blue-600 text-white p-2 rounded-md hover:bg-blue-700 ml-2">Apply</button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>