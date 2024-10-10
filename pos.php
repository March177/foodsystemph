<?php

include 'db/config.php';
include 'functions/functionpos.php';
$categories = getAllCategories($conn);


$categoryQuery = "SELECT DISTINCT category_name FROM categories WHERE status = 1";
$categoryResult = $conn->query($categoryQuery);



$categories = getAllCategories($conn);

// Check if a category is selected
$selectedCategory = isset($_GET['category']) ? $_GET['category'] : '';

// Query to get distinct categories
$categoryQuery = "SELECT DISTINCT category_name FROM categories WHERE status = 1";
$categoryResult = $conn->query($categoryQuery);

// Modify the menu query based on the selected category
if (!empty($selectedCategory)) {
  $menuQuery = "SELECT * FROM menu WHERE category_name = '$selectedCategory' ORDER BY category_name";
} else {
  $menuQuery = "SELECT * FROM menu ORDER BY category_name";
}

$menuResult = $conn->query($menuQuery);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta
    name="viewport"
    content="width=device-width, initial-scale=1.0, user-scalable=0" />

  <meta
    name="keywords"
    content="admin, estimates, bootstrap, business, corporate, creative, invoice, html5, responsive, Projects" />

  <meta name="robots" content="noindex, nofollow" />
  <title>POS</title>

  <link
    rel="shortcut icon"
    type="image/x-icon"
    href="assets/img/favicon.jpg" />

  <link rel="stylesheet" href="assets/css/bootstrap.min.css" />

  <link rel="stylesheet" href="assets/css/animate.css" />

  <link
    rel="stylesheet"
    href="assets/plugins/owlcarousel/owl.carousel.min.css" />
  <link
    rel="stylesheet"
    href="assets/plugins/owlcarousel/owl.theme.default.min.css" />

  <link rel="stylesheet" href="assets/plugins/select2/css/select2.min.css" />

  <link rel="stylesheet" href="assets/css/bootstrap-datetimepicker.min.css" />

  <link
    rel="stylesheet"
    href="assets/plugins/fontawesome/css/fontawesome.min.css" />
  <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css" />

  <link rel="stylesheet" href="assets/css/style.css" />
  <style>
    .select-split {
      margin-bottom: 15px;
    }

    .select-group {
      position: relative;
    }

    .form-select {
      margin-bottom: 1rem;
    }

    .form-check {
      display: inline-block;
      margin: 0.5rem;
    }

    .form-check-input:checked+.form-check-label {
      background-color: #FF8343;
      color: #fff;
    }

    .form-check-input {
      position: absolute;
      opacity: 0;
    }

    .form-check-label {
      display: block;
      padding: 10px 20px;
      cursor: pointer;
      border: 1px solid #ced4da;
      border-radius: 0.25rem;
      text-align: center;
    }

    .form-check-label:hover {
      background-color: #e9ecef;
    }

    .form-label {
      font-weight: bold;
    }

    .highlight-label {
      color: #007bff;
      font-weight: bold;
      font-size: 1.2rem;
      background-color: #f0f8ff;
      padding: 0.5rem;
      border-radius: 4px;
    }


    .paymentmethod.active {
      background-color: #f0f0f0;
      border: 2px solid #007bff;
      border-radius: 4px;
    }

    .checkout-form {
      background: white;
      padding: 20px;
      border-radius: 5px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .is-invalid {
      border: 2px solid red;
      /* Highlight invalid fields */
    }

    .btn-totallabel {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 10px;
      background-color: #28a745;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    .btn-totallabel:disabled {
      background-color: #ccc;
      cursor: not-allowed;
    }
  </style>
</head>

<body>

  <div class="main-wrappers">
    <div class="header">
      <?php include 'php/header.php'; ?>
    </div>
    <div class="page-wrapper ms-0">
      <div class="content">
        <div class="row">
          <div class="col-lg-8 col-sm-12 tabs_wrapper">
            <div class="page-header">
              <div class="page-title">
                <h4>Categories</h4>
                <h6>Manage your purchases</h6>
              </div>
            </div>
            <div class="search-set">
              <div class="search-input">
                <input type="text" id="searchMenu" onkeyup="searchMenu()" placeholder="Search...">

              </div>

              <!-- Category Filter -->
              <form method="GET" id="categoryForm">
                <div class="flex items-center space-x-2">
                  <label for="category" class="block text-sm font-medium">Category:</label>
                  <select name="category" id="category" class="block w-full p-2 border rounded-md" onchange="document.getElementById('categoryForm').submit();">
                    <option value="">All Categories</option>
                    <?php
                    if ($categoryResult->num_rows > 0) {
                      while ($category = $categoryResult->fetch_assoc()) {
                        $selected = ($category['category_name'] == $selectedCategory) ? 'selected' : '';
                        echo '<option value="' . htmlspecialchars($category['category_name']) . '" ' . $selected . '>' . htmlspecialchars($category['category_name']) . '</option>';
                      }
                    }
                    ?>
                  </select>
                </div>
              </form>

            </div>
            <div class="tab_content active">
              <div class="row">
                <?php
                if ($menuResult->num_rows > 0) {
                  $currentCategory = '';
                  while ($row = $menuResult->fetch_assoc()) {
                    if ($currentCategory !== $row['category_name']) {
                      if ($currentCategory !== '') {
                        echo '</div>';
                      }
                      $currentCategory = $row['category_name'];
                      echo '<h3>' . htmlspecialchars($currentCategory) . '</h3>';
                      echo '<div class="row">';
                    }
                ?>
                    <div class="col-lg-3 col-sm-6 d-flex">
                      <div class="productset flex-fill" data-id="<?php echo htmlspecialchars($row['menu_id']); ?>"
                        data-name="<?php echo htmlspecialchars($row['menu_name']); ?>"
                        data-price="<?php echo number_format($row['price'], 2); ?>"
                        data-image="<?php echo htmlspecialchars($row['image_path']); ?>" style="cursor: pointer;">
                        <div class="productsetimg">
                          <img src="<?php echo htmlspecialchars($row['image_path']); ?>" alt="img" />
                          <h6>10%</h6>
                          <div class="check-product">
                            <i class="fa fa-check"></i>
                          </div>
                        </div>
                        <div class="productsetcontent">
                          <h4><?php echo htmlspecialchars($row['menu_name']); ?></h4>
                          <h6><?php echo number_format($row['price'], 2); ?></h6>
                        </div>
                      </div>
                    </div>
                <?php
                  }
                  echo '</div>';
                } else {
                  echo "No products available.";
                }
                ?>
              </div>

            </div>
          </div>





          <div class="col-lg-4 col-sm-12">
            <div class="order-list">
              <div class="orderid">

              </div>
              <div class="actionproducts">
                <ul>

                  <li>
                    <a
                      href="javascript:void(0);"
                      data-bs-toggle="dropdown"
                      aria-expanded="false"
                      class="dropset">
                      <img src="assets/img/icons/ellipise1.svg" alt="img" />
                    </a>
                    <ul
                      class="dropdown-menu"
                      aria-labelledby="dropdownMenuButton"
                      data-popper-placement="bottom-end">
                      <li>
                        <a href="#" class="dropdown-item">Action</a>
                      </li>
                      <li>
                        <a href="#" class="dropdown-item">Another Action</a>
                      </li>
                      <li>
                        <a href="#" class="dropdown-item">Something Elses</a>
                      </li>
                    </ul>
                  </li>
                </ul>
              </div>
            </div>





            <diz`v class="card card-order">
              <div class="card-body">
                <div class="row">
                  <div class="col-12">

                    <label for="walkin-name" class="form-label">Cart</label>

                  </div>
                  <div class="col-12">
                    <a
                      href="javascript:void(0);"
                      class="btn btn-adds"
                      data-bs-toggle="modal"
                      data-bs-target="#create"><i class="fa fa-plus me-2"></i>Add Reservation</a>
                  </div>

                  <div class="container mt-4">
                    <div class="row">
                      <div class="col-lg-6 col-md-12 mb-3">
                        <label for="orderType" class="form-label">Order Type</label>
                        <select id="orderType" name="order_type" class="form-select validate">
                          <option value="">Select Order Type</option> <!-- Add this option -->
                          <option value="DINE">Dine-In</option>
                          <option value="TAKE OUT">Take-Out</option>
                        </select>
                      </div>
                      <!-- Discount Type -->
                      <div class="col-lg-6 col-md-12 mb-3">
                        <label for="discount" class="form-label">Discount Type</label>
                        <select id="discount" name="discount" class="form-select validate">
                          <option value="">Select Discount Type</option> <!-- Add this option -->
                          <option value="Regular">Regular</option>
                          <option value="PWD/Senior Citizen">PWD/Senior Citizen</option>
                        </select>
                      </div>
                    </div>
                  </div>




                </div>
              </div>
              <div class="split-card"></div>
              <div class="card-body pt-0">
                <form id="checkoutForm">
                  <div class="totalitem">
                    <h4>Total products: <span id="total-items">0</span></h4>
                    <a href="javascript:void(0);" id="clear-all">Clear all</a>
                  </div>

                  <div class="product-table">

                    <div id="product-lists-container">


                    </div>
                  </div>
              </div>
              <div class="split-card"></div>
              <div class="card-body pt-0 pb-2">


                <div class="setvalue">
                  <ul>
                    <li>
                      <h5>Subtotal</h5>
                      <h6 id="subtotal-value">₱0.00</h6>
                    </li>
                    <li>
                      <h5>Discount</h5>
                      <h6 id="discount-value">₱0.00</h6>
                    </li>
                    <li class="total-value">
                      <h5>Total</h5>
                      <h6 id="total-value">₱0.00</h6>
                    </li>
                  </ul>
                </div>


                <!-- Payment Method -->
                <div class="col-lg-12">
                  <div class="d-flex justify-content-start">
                    <div class="form-check">
                      <input type="radio" id="cash" name="payment_method" value="Cash" class="form-check-input validate">
                      <label for="cash" class="form-check-label">Cash</label>
                    </div>
                    <div class="form-check">
                      <input type="radio" id="debit" name="payment_method" value="Debit" class="form-check-input validate">
                      <label for="debit" class="form-check-label">Debit</label>
                    </div>
                    <div class="form-check">
                      <input type="radio" id="scan" name="payment_method" value="Scan" class="form-check-input validate">
                      <label for="scan" class="form-check-label">Scan</label>
                    </div>
                  </div>
                </div>
                <button type="button" id="checkoutBtn" class="btn-totallabel d-flex justify-content-between align-items-center w-100 py-2" disabled>
                  <h5 class="mb-0">Checkout</h5>
                  <h6 class="mb-0" id="total-price-display">₱0.00</h6>
                </button>

                <div class="btn-pos">
                  <ul>
                    <li>
                      <a class="btn"><img
                          src="assets/img/icons/pause1.svg"
                          alt="img"
                          class="me-1" />Hold</a>
                    </li>
                    <li>
                      <a class="btn"><img
                          src="assets/img/icons/edit-6.svg"
                          alt="img"
                          class="me-1" />Quotation</a>
                    </li>
                    <li>
                      <a class="btn"><img
                          src="assets/img/icons/trash12.svg"
                          alt="img"
                          class="me-1" />Void</a>
                    </li>
                    <li>
                      <a class="btn"><img
                          src="assets/img/icons/wallet1.svg"
                          alt="img"
                          class="me-1" />Payment</a>
                    </li>
                    <li>
                      <a
                        class="btn"
                        data-bs-toggle="modal"
                        data-bs-target="#recents"><img
                          src="assets/img/icons/transcation.svg"
                          alt="img"
                          class="me-1" />
                        Transaction</a>
                    </li>
                  </ul>
                </div>
              </div>

          </div>
        </div>
      </div>


      <div
        class="modal fade"
        id="create"
        tabindex="-1"
        aria-labelledby="create"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Create</h5>
              <button
                type="button"
                class="close"
                data-bs-dismiss="modal"
                aria-label="Close">
                <span aria-hidden="true">×</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-lg-6 col-sm-12 col-12">
                  <div class="form-group">
                    <label>Customer Name</label>
                    <input type="text" />
                  </div>
                </div>
                <div class="col-lg-6 col-sm-12 col-12">
                  <div class="form-group">
                    <label>Email</label>
                    <input type="text" />
                  </div>
                </div>
                <div class="col-lg-6 col-sm-12 col-12">
                  <div class="form-group">
                    <label>Phone</label>
                    <input type="text" />
                  </div>
                </div>


              </div>
              <div class="col-lg-6 col-sm-12 col-12">
                <div class="form-group">
                  <label>Address</label>
                  <input type="text" />
                </div>
              </div>
            </div>
            <div class="col-lg-12 d-flex justify-content-center align-items-start mt-3">
              <a class="btn btn-submit me-2">Submit</a>
              <a class="btn btn-cancel" data-bs-dismiss="modal">Cancel</a>
            </div>

          </div>
        </div>
      </div>
    </div>


  </div>
  </div>
  </div>
  </div>
  </div>
  </div>
  </div>
  <script>
    function searchMenu() {
      let input = document.getElementById('searchMenu').value.toLowerCase();
      let menuItems = document.querySelectorAll('.productset');
      for (let i = 0; i < menuItems.length; i++) {
        let name = menuItems[i].getAttribute('data-name').toLowerCase();
        if (name.includes(input)) {
          menuItems[i].style.display = '';
        } else {
          menuItems[i].style.display = 'none';
        }
      }
    }

    document.addEventListener("DOMContentLoaded", function() {
      const productListsContainer = document.getElementById(
        "product-lists-container"
      );
      const totalItemsElement = document.getElementById("total-items");
      const clearAllButton = document.getElementById("clear-all");
      const paymentMethods = document.querySelectorAll(".paymentmethod");

      // Elements for subtotal, discount, and total
      const subtotalElement = document.getElementById("subtotal-value");
      const discountElement = document.getElementById("discount-value");
      const totalElement = document.getElementById("total-value");
      const checkoutTotalElement = document.querySelector(".btn-totallabel h6"); // New element for checkout total

      function createProductList(categoryId) {
        const ul = document.createElement("ul");
        ul.className = "product-lists";
        ul.dataset.categoryId = categoryId;
        return ul;
      }
      paymentMethods.forEach(function(method) {
        method.addEventListener("click", function() {
          // Remove 'active' class from all payment methods
          paymentMethods.forEach(function(m) {
            m.classList.remove("active");
          });

          // Add 'active' class to the clicked payment method
          this.classList.add("active");
        });
      });

      function addToProductList(item) {
        let ul = [
          ...productListsContainer.querySelectorAll("ul.product-lists"),
        ].find((ul) => ul.querySelector(`li[data-id="${item.id}"]`));

        if (!ul) {
          ul = createProductList(item.categoryId);
          productListsContainer.appendChild(ul);
        }

        const existingItem = ul.querySelector(`li[data-id="${item.id}"]`);
        if (existingItem) {
          const quantityField = existingItem.querySelector(".quantity-field");
          quantityField.value = parseInt(quantityField.value, 10) + 1;
        } else {
          const listItem = document.createElement("li");
          listItem.dataset.id = item.id;
          listItem.innerHTML = `
                <div class="productimg">
                    <div class="productimgs">
                        <img src="${item.image}" alt="img" />
                    </div>
                    <div class="productcontet">
                        <h4>
                            ${item.name}
                            <a href="javascript:void(0);" class="ms-2" data-bs-toggle="modal" data-bs-target="#edit">
                                <img src="assets/img/icons/edit-5.svg" alt="img"/>
                            </a>
                        </h4>
                        <div class="productlinkset">
                            <h5>PT${item.id}</h5>
                        </div>
                        <div class="increment-decrement">
                            <div class="input-groups">
                                <input type="button" value="-" class="button-minus dec button"/>
                                <input type="text" name="child" value="1" class="quantity-field"/>
                                <input type="button" value="+" class="button-plus inc button"/>
                            </div>
                        </div>
                    </div>
                </div>
            `;

          const priceItem = document.createElement("li");
          priceItem.dataset.id = item.id;
          priceItem.className = "price-item"; // Added class to identify price items
          priceItem.textContent = `Price: ${item.price}`;

          const deleteItem = document.createElement("li");
          deleteItem.innerHTML = `
                <a href="javascript:void(0);" class="remove-item" data-id="${item.id}">
                    <img src="assets/img/icons/delete-2.svg" alt="img"/>
                </a>
            `;

          ul.appendChild(listItem);
          ul.appendChild(priceItem);
          ul.appendChild(deleteItem);
        }
        updateTotals(); // Update totals whenever a product is added
      }

      function addProductList(item) {
        console.log("Adding product:", item); // Debug log
        addToProductList(item);
      }

      function updateTotals() {
        let subtotal = 0;
        let totalItems = 0;
        const discountPercentageRegular = 0; // No discount for Regular
        const discountPercentagePWD = 0.1; // 10% discount for PWD/Senior

        [...productListsContainer.querySelectorAll("ul.product-lists")].forEach(
          (ul) => {
            [...ul.querySelectorAll(".price-item")].forEach((priceItem) => {
              const priceText = priceItem.textContent.replace("Price: ", "");
              const price = parseFloat(priceText);
              const quantityField =
                priceItem.previousElementSibling.querySelector(".quantity-field");
              const quantity = parseInt(quantityField.value, 10);
              if (!isNaN(price) && !isNaN(quantity)) {
                subtotal += price * quantity;
              }
            });
            totalItems += [...ul.querySelectorAll(".quantity-field")].reduce(
              (total, field) => total + parseInt(field.value, 10),
              0
            );
          }
        );

        // Update subtotal
        subtotalElement.textContent = `₱${subtotal.toFixed(2)}`;

        // Determine discount based on discount type selected
        const discountType = $("#discount").val();
        let discount = 0;
        if (discountType === "PWD/Senior Citizen") {
          discount = subtotal * discountPercentagePWD; // Apply 10% discount
        }

        discountElement.textContent = `₱${discount.toFixed(2)}`;

        // Update total
        const total = subtotal - discount;
        totalElement.textContent = `₱${total.toFixed(2)}`;

        // Update total items count
        totalItemsElement.textContent = totalItems;

        // Update checkout total
        if (checkoutTotalElement) {
          checkoutTotalElement.textContent = `₱${total.toFixed(2)}`;
        }
      }



      $(document).ready(function() {
        // Function to validate fields
        function validateFields() {
          let isValid = true;
          const requiredFields = $('.validate');

          // Remove previous highlights
          requiredFields.removeClass('is-invalid');

          // Check Order Type
          const orderType = $("#orderType").val();
          if (!orderType) {
            $("#orderType").addClass('is-invalid');
            isValid = false;
          }

          // Check Discount Type
          const discountType = $("#discount").val();
          if (!discountType) {
            $("#discount").addClass('is-invalid');
            isValid = false;
          }

          // Check Payment Method
          const paymentMethod = $('input[name="payment_method"]:checked');
          if (!paymentMethod.length) {
            $('.form-check-input').addClass('is-invalid');
            isValid = false;
          }

          // Enable or disable the checkout button
          $('#checkoutBtn').prop('disabled', !isValid);
          return isValid;
        }

        // Attach change event listeners to the required fields
        $("#orderType, #discount").change(validateFields);
        $('input[name="payment_method"]').change(validateFields);

        // Submit order function
        $('#checkoutBtn').click(function() {
          // Validate fields before proceeding
          if (!validateFields()) {
            alert("Please fill in all required fields.");
            return;
          }

          // Proceed with the order submission (this is where you'd call your order submission function)
          alert("Order submitted successfully!"); // Placeholder for AJAX call
        });
      });
      // Function to show the receipt popup
      function showReceipt(orderDetails) {
        const receiptModal = document.createElement('div');
        receiptModal.className = 'modal fade';
        receiptModal.id = 'receiptModal';
        receiptModal.tabIndex = '-1';
        receiptModal.setAttribute('aria-labelledby', 'receiptModalLabel');
        receiptModal.setAttribute('aria-hidden', 'true');

        receiptModal.innerHTML = `
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="receiptModalLabel">Receipt</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <h6>Order ID: ${orderDetails.order_id}</h6>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Menu Name</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${orderDetails.items.map(item => `
                                    <tr>
                                        <td>${item.menu_name}</td>
                                        <td>${item.quantity}</td>
                                        <td>₱${(item.price * item.quantity).toFixed(2)}</td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                        <h6>Subtotal: ₱${orderDetails.subtotal.toFixed(2)}</h6>
                        <h6>Total: ₱${orderDetails.total_price.toFixed(2)}</h6>
                        <h6>Cashier: ${orderDetails.cashier_name}</h6>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        `;

        document.body.appendChild(receiptModal);
        var modal = new bootstrap.Modal(receiptModal);
        modal.show();

        // Cleanup after modal is closed
        receiptModal.addEventListener('hidden.bs.modal', function() {
          document.body.removeChild(receiptModal);
        });
      }
      $("#checkoutBtn").click(function() {
        var order_type = $("#orderType").val();
        var discount = $("#discount").val();
        var total_price = $("#total-value").text().replace("₱", "").trim();
        var payment_method = $('input[name="payment_method"]:checked').val();
        var customer = $("#walkin-name").val() || "Walk-in Customer";
        var created_by = "1"; // Replace with actual user ID

        var orderedItems = [];
        $(".product-lists li[data-id]").each(function() {
          var item = {
            menu_id: $(this).attr("data-id"), // Ensure this is correct
            menu_name: $(this).find("h4").text().trim(),
            quantity: parseInt($(this).find(".quantity-field").val()), // Ensure this is a number
            price: parseFloat($(this).next(".price-item").text().replace("Price: ", "").trim())
          };

          // Check if all properties are defined
          if (item.menu_id && item.menu_name && !isNaN(item.quantity) && item.price) {
            orderedItems.push(item);
          } else {
            console.error("Item data is incomplete or invalid", item);
          }
        });

        var data = {
          customer: customer,
          order_type: order_type,
          discount: discount,
          payment_method: payment_method,
          total_price: total_price,
          created_by: created_by,
          orderedItems: orderedItems,
        };

        console.log("Data to be sent:", data); // Check the final data structure

        $.ajax({
          url: "place_order.php",
          type: "POST",
          data: JSON.stringify(data),
          contentType: "application/json",
          dataType: "json",
          success: function(response) {
            if (response.status === "success") {
              alert(response.message);
              // Prepare order details for receipt
              const orderDetails = {
                order_id: response.order_id,
                items: orderedItems,
                subtotal: response.subtotal,
                total_price: total_price,
                cashier_name: "Cashier Name" // Replace with dynamic cashier name if available
              };

              // Show the receipt popup
              showReceipt(orderDetails);

              window.location.href = "pos.php"; // Redirect after successful order
            } else {
              alert("Error: " + response.message);
            }
          },
          error: function(jqXHR, textStatus, errorThrown) {
            console.log("Server response:", jqXHR.responseText);
            alert("There was an error processing your order: " + jqXHR.responseText);
          },
        });
      });












      document.getElementById("category").addEventListener("change", function() {
        const selectedCategory = this.value;
        const products = document.querySelectorAll(".productset");

        products.forEach((product) => {
          const productCategory = product.getAttribute("data-category");

          if (selectedCategory === "" || productCategory === selectedCategory) {
            product.style.display = "block";
          } else {
            product.style.display = "none";
          }
        });
      });

      function handleQuantityChange(event) {
        const button = event.target;
        const input = button.parentElement.querySelector(".quantity-field");
        let value = parseInt(input.value, 10);

        if (isNaN(value) || value < 0) {
          value = 0; // Reset to zero if invalid or negative
        }

        if (button.classList.contains("button-minus")) {
          if (value > 0) {
            value--;
          }
        } else if (button.classList.contains("button-plus")) {
          value++;
        }

        input.value = value;
        updateTotals(); // Update totals after changing quantity
      }

      function handleRemove(event) {
        const target = event.target.closest(".remove-item");
        if (target) {
          const ul = target.closest("ul.product-lists");
          if (ul) {
            ul.remove(); // Remove the entire <ul> element
            updateTotals(); // Update totals after removal
          }
        }
      }

      function clearAll() {
        productListsContainer.innerHTML = ""; // Remove all <ul> elements
        updateTotals(); // Update totals after clearing all
      }

      document.querySelectorAll(".productset").forEach((item) => {
        item.addEventListener("click", function() {
          const id = this.getAttribute("data-id");
          const name = this.getAttribute("data-name");
          const price = this.getAttribute("data-price");
          const image = this.getAttribute("data-image");
          const categoryId = this.getAttribute("data-category-id");

          addProductList({
            id,
            name,
            price,
            image,
            categoryId
          });
        });
      });

      document.addEventListener("click", function(event) {
        if (
          event.target.classList.contains("button-minus") ||
          event.target.classList.contains("button-plus")
        ) {
          handleQuantityChange(event);
        } else if (event.target.closest(".remove-item")) {
          handleRemove(event);
        }
      });

      clearAllButton.addEventListener("click", clearAll); // Attach the clearAll function to the "Clear all" button
    });
  </script>


  <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/jquery-3.6.0.min.js"></script>

  <script src="assets/js/feather.min.js"></script>

  <script src="assets/js/jquery.slimscroll.min.js"></script>

  <script src="assets/js/bootstrap.bundle.min.js"></script>

  <script src="assets/js/jquery.dataTables.min.js"></script>
  <script src="assets/js/dataTables.bootstrap4.min.js"></script>

  <script src="assets/plugins/select2/js/select2.min.js"></script>

  <script src="assets/plugins/owlcarousel/owl.carousel.min.js"></script>

  <script src="assets/plugins/sweetalert/sweetalert2.all.min.js"></script>
  <script src="assets/plugins/sweetalert/sweetalerts.min.js"></script>

  <script src="assets/js/script.js"></script>
</body>

</html>