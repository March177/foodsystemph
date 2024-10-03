<?php

include 'db/config.php';
include 'functions/functionpos.php';
$categories = getAllCategories($conn);
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
                <a class="btn btn-searchset"><img src="assets/img/icons/search-white.svg" alt="img" /></a>
              </div>
            </div>
            <ul class="tabs owl-carousel owl-theme owl-product border-0">
              <?php foreach ($categories as $category): ?>
                <li id="<?php echo strtolower(str_replace(' ', '-', $category)); ?>" onclick="showCategory(this)">
                  <div class="product-details">
                    <img src="assets/img/product/noimage.png" alt="img" />
                    <h6><?php echo htmlspecialchars($category); ?></h6>
                  </div>
                </li>
              <?php endforeach; ?>
            </ul>
            <div class="tab_content active">
              <div class="row">
                <?php

                $query = "SELECT * FROM menu ORDER BY category_name";
                $result = $conn->query($query);

                if ($result->num_rows > 0) {
                  $currentCategory = '';
                  while ($row = $result->fetch_assoc()) {

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
                        data-image="<?php echo htmlspecialchars($row['image_path']); ?>"
                        style="cursor: pointer;">
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
                <h4>Order List</h4>
                <h5>Order ID</h5>
              </div>
              <div class="actionproducts">
                <ul>
                  <li>
                    <a
                      href="javascript:void(0);"
                      class="deletebg confirm-text"><img src="assets/img/icons/delete-2.svg" alt="img" /></a>
                  </li>
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

                    <label for="walkin-name" class="form-label">Walk-In</label>

                  </div>
                  <div class="col-12">
                    <a
                      href="javascript:void(0);"
                      class="btn btn-adds"
                      data-bs-toggle="modal"
                      data-bs-target="#create"><i class="fa fa-plus me-2"></i>Add Customer</a>
                  </div>

                  <div class="container mt-4">
                    <div class="row">
                      <div class="col-lg-6 col-md-12 mb-3">
                        <label for="orderType" class="form-label">Order Type</label>
                        <select id="orderType" name="order_type" class="form-select">
                          <option value="DINE">Dine-In</option>
                          <option value="TAKE OUT">Take-Out</option>
                        </select>
                      </div>


                      <div class="col-lg-6 col-md-12 mb-3">
                        <label for="discount" class="form-label">Discount Type</label>
                        <select id="discount" name="discount" class="form-select">
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
                    <h4>Total items: <span id="total-items">0</span></h4>
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

                <div class="col-lg-12">
                  <div class="d-flex justify-content-start">
                    <div class="form-check">
                      <input type="radio" id="cash" name="payment_method" value="Cash" class="form-check-input">
                      <label for="cash" class="form-check-label">Cash</label>
                    </div>
                    <div class="form-check">
                      <input type="radio" id="debit" name="payment_method" value="Debit" class="form-check-input">
                      <label for="debit" class="form-check-label">Debit</label>
                    </div>
                    <div class="form-check">
                      <input type="radio" id="scan" name="payment_method" value="Scan" class="form-check-input">
                      <label for="scan" class="form-check-label">Scan</label>
                    </div>
                  </div>
                </div>
                <button type="button" id="checkoutBtn" class="btn-totallabel d-flex justify-content-between align-items-center w-100 py-2" onclick="submitOrder()">
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
  </script>

  <script src="assets/js/pos.js"></script>
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