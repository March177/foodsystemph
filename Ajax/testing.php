<?php
// Include your database connection file
include 'db/config.php'; // adjust the path as needed
include 'getallcategories.php';
// Call the function and get all categories
$categories = getAllCategories($conn);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=0"
    />
   
    <meta
      name="keywords"
      content="admin, estimates, bootstrap, business, corporate, creative, invoice, html5, responsive, Projects"
    />

    <meta name="robots" content="noindex, nofollow" />
    <title>POS</title>

    <link
      rel="shortcut icon"
      type="image/x-icon"
      href="assets/img/favicon.jpg"
    />

    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />

    <link rel="stylesheet" href="assets/css/animate.css" />

    <link
      rel="stylesheet"
      href="assets/plugins/owlcarousel/owl.carousel.min.css"
    />
    <link
      rel="stylesheet"
      href="assets/plugins/owlcarousel/owl.theme.default.min.css"
    />

    <link rel="stylesheet" href="assets/plugins/select2/css/select2.min.css" />

    <link rel="stylesheet" href="assets/css/bootstrap-datetimepicker.min.css" />

    <link
      rel="stylesheet"
      href="assets/plugins/fontawesome/css/fontawesome.min.css"
    />
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css" />

    <link rel="stylesheet" href="assets/css/style.css" />
    <style>
      .tabs_container {
    max-height: 500px; /* Adjust as needed */
    overflow-y: auto;
    max-width: 1499px;
}

/* Optional: Customize the appearance of the text box and label */
.form-label {
    font-weight: bold; /* Optional: make the label text bold */
}
/* Custom CSS to highlight the label */
.highlight-label {
    color: #007bff; /* Blue color or any color you prefer */
    font-weight: bold; /* Make the text bold */
    font-size: 1.2rem; /* Optional: increase the font size for better visibility */
    background-color: #f0f8ff; /* Light background color to make the text stand out */
    padding: 0.5rem; /* Optional: add some padding around the label */
    border-radius: 4px; /* Optional: add rounded corners */
}


.paymentmethod.active {
    background-color: #f0f0f0; /* Background color when active */
    border: 2px solid #007bff; /* Border color for active item */
    border-radius: 4px; /* Optional: rounded corners */
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
              <ul class="tabs owl-carousel owl-theme owl-product border-0">
            <?php foreach ($categories as $category): ?>
                <li id="<?php echo strtolower(str_replace(' ', '-', $category)); ?>">
                    <div class="product-details">
                        <img src="assets/img/product/noimage.png" alt="img" />
                        <h6><?php echo htmlspecialchars($category); ?></h6>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>


<div class="tabs_container" style="max-height: 500px; overflow-y: auto;">
    <!-- Your PHP-generated menu content will go here -->


    <div class="tab_content active">
    <div class="row">
        <?php
        // Fetch data from the `menu` table, ordered by category
        $query = "SELECT * FROM menu ORDER BY category";
        $result = $conn->query($query); // Assuming $conn is your database connection

        if ($result->num_rows > 0) {
            $currentCategory = '';
            while ($row = $result->fetch_assoc()) {
                // Check if the category has changed
                if ($currentCategory !== $row['category']) {
                    // Close the previous category's div if it exists
                    if ($currentCategory !== '') {
                        echo '</div>'; // Close the product row for the previous category
                    }

                    // Start a new category section
                    $currentCategory = $row['category'];
                    echo '<h3>' . htmlspecialchars($currentCategory) . '</h3>';
                    echo '<div class="row">'; // Start a new row for products under this category
                }
                ?>
                <div class="col-lg-3 col-sm-6 d-flex">
                    <div class="productset flex-fill" data-id="<?php echo htmlspecialchars($row['id']); ?>"
                        data-name="<?php echo htmlspecialchars($row['menu_name']); ?>"
                        data-price="<?php echo number_format($row['price'], 2); ?>"
                        data-image="<?php echo htmlspecialchars($row['image_path']); ?>"
                        style="cursor: pointer;">
                        <div class="productsetimg">
                            <img
                                src="<?php echo htmlspecialchars($row['image_path']); ?>"
                                alt="img"
                            />
                            <h6>10%</h6> <!-- Quantity can be dynamic or static depending on your needs -->
                            <div class="check-product">
                                <i class="fa fa-check"></i>
                            </div>
                        </div>
                        <div class="productsetcontent">
                            <h5><?php echo htmlspecialchars($row['subcategory']); ?></h5> <!-- Subcategory -->
                            <h4><?php echo htmlspecialchars($row['menu_name']); ?></h4> <!-- Menu Name -->
                            <h6><?php echo number_format($row['price'], 2); ?></h6> <!-- Price -->
                        </div>
                    </div>
                </div>
                <?php
            }
            echo '</div>'; // Close the last category's product row
        } else {
            echo "No products available.";
        }
        ?>
    </div>
</div>



                <div class="tab_content" data-tab="headphone">
                  <div class="row">
                    <div class="col-lg-3 col-sm-6 d-flex">
                      <div class="productset flex-fill">
                        <div class="productsetimg">
                          <img
                            src="assets/img/product/product36.jpg"
                            alt="img"
                          />
                          <h6>Qty: 5.00</h6>
                          <div class="check-product">
                            <i class="fa fa-check"></i>
                          </div>
                        </div>
                        <div class="productsetcontent">
                          <h5>Fried</h5>
                          <h4>Adobo</h4>
                          <h6>150.00</h6>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="tab_content" data-tab="Accessories">
                  <div class="row">
                    <div class="col-lg-3 col-sm-6 d-flex">
                      <div class="productset flex-fill">
                        <div class="productsetimg">
                          <img
                            src="assets/img/product/product55.jpg"
                            alt="img"
                          />
                          <h6>Qty: 1.00</h6>
                          <div class="check-product">
                            <i class="fa fa-check"></i>
                          </div>
                        </div>
                        <div class="productsetcontent">
                          <h5>Accessories</h5>
                          <h4>Mouse</h4>
                          <h6>150.00</h6>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="tab_content" data-tab="Shoes">
                  <div class="row">
                    <div class="col-lg-3 col-sm-6 d-flex">
                      <div class="productset flex-fill">
                        <div class="productsetimg">
                          <img
                            src="assets/img/product/product60.jpg"
                            alt="img"
                          />
                          <h6>Qty: 1.00</h6>
                          <div class="check-product">
                            <i class="fa fa-check"></i>
                          </div>
                        </div>
                        <div class="productsetcontent">
                          <h5>Shoes</h5>
                          <h4>Red nike</h4>
                          <h6>1500.00</h6>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>







            <div class="col-lg-4 col-sm-12">
              <div class="order-list">
                <div class="orderid">
                  <h4>Order List</h4>
                  <h5>Transaction id : #65565</h5>
                </div>
                <div class="actionproducts">
                  <ul>
                    <li>
                      <a
                        href="javascript:void(0);"
                        class="deletebg confirm-text"
                        ><img src="assets/img/icons/delete-2.svg" alt="img"
                      /></a>
                    </li>
                    <li>
                      <a
                        href="javascript:void(0);"
                        data-bs-toggle="dropdown"
                        aria-expanded="false"
                        class="dropset"
                      >
                        <img src="assets/img/icons/ellipise1.svg" alt="img" />
                      </a>
                      <ul
                        class="dropdown-menu"
                        aria-labelledby="dropdownMenuButton"
                        data-popper-placement="bottom-end"
                      >
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
    <!-- Replace the button with a text box and label -->
    <label for="walkin-name" class="form-label">Walk-In</label>
    <!-- <input type="text" id="walkin-name" class="form-control" placeholder="Enter Walk-In name" /> -->
</div>

                    
<div class="col-lg-12">
    <div class="select-split">
        <div class="select-group w-100">
        <select id="orderType" name="order_type">
            <option value="DINE">Dine-In</option>
            <option value="TAKE OUT">Take-Out</option>
        </select>
        </div>
    </div>
</div>
<div class="col-lg-12">
    <div class="select-split">
        <div class="select-group w-100">
        <select id="discount" name="discount">
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
         
        <!-- New product lists will be appended here -->
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

<div class="setvaluecash">
<input type="radio" id="cash" name="payment_method" value="Cash">
        <label for="cash">Cash</label>
        <input type="radio" id="debit" name="payment_method" value="Debit">
        <label for="debit">Debit</label>
        <input type="radio" id="scan" name="payment_method" value="Scan">
        <label for="scan">Scan</label>
</div>

                  <div class="btn-totallabel">
                    <h5>Checkout</h5>
                    <h6>₱0.00</h6>
                    <button type="button" id="checkoutBtn">Checkout</button>
                    </form>
                  </div>
                  <div class="btn-pos">
                    <ul>
                      <li>
                        <a class="btn"
                          ><img
                            src="assets/img/icons/pause1.svg"
                            alt="img"
                            class="me-1"
                          />Hold</a
                        >
                      </li>
                      <li>
                        <a class="btn"
                          ><img
                            src="assets/img/icons/edit-6.svg"
                            alt="img"
                            class="me-1"
                          />Quotation</a
                        >
                      </li>
                      <li>
                        <a class="btn"
                          ><img
                            src="assets/img/icons/trash12.svg"
                            alt="img"
                            class="me-1"
                          />Void</a
                        >
                      </li>
                      <li>
                        <a class="btn"
                          ><img
                            src="assets/img/icons/wallet1.svg"
                            alt="img"
                            class="me-1"
                          />Payment</a
                        >
                      </li>
                      <li>
                        <a
                          class="btn"
                          data-bs-toggle="modal"
                          data-bs-target="#recents"
                          ><img
                            src="assets/img/icons/transcation.svg"
                            alt="img"
                            class="me-1"
                          />
                          Transaction</a
                        >
                      </li>
                    </ul>
                  </div>
                </div>








              </div>
            </div>














            
          </div>
        </div>
      </div>
    </div>
    <div class="modal fade" id="calculator" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Define Quantity</h5>
            <button
              type="button"
              class="close"
              data-bs-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="calculator-set">
              <div class="calculatortotal">
                <h4>0</h4>
              </div>
              <ul>
                <li>
                  <a href="javascript:void(0);">1</a>
                </li>
                <li>
                  <a href="javascript:void(0);">2</a>
                </li>
                <li>
                  <a href="javascript:void(0);">3</a>
                </li>
                <li>
                  <a href="javascript:void(0);">4</a>
                </li>
                <li>
                  <a href="javascript:void(0);">5</a>
                </li>
                <li>
                  <a href="javascript:void(0);">6</a>
                </li>
                <li>
                  <a href="javascript:void(0);">7</a>
                </li>
                <li>
                  <a href="javascript:void(0);">8</a>
                </li>
                <li>
                  <a href="javascript:void(0);">9</a>
                </li>
                <li>
                  <a href="javascript:void(0);" class="btn btn-closes"
                    ><img src="assets/img/icons/close-circle.svg" alt="img"
                  /></a>
                </li>
                <li>
                  <a href="javascript:void(0);">0</a>
                </li>
                <li>
                  <a href="javascript:void(0);" class="btn btn-reverse"
                    ><img src="assets/img/icons/reverse.svg" alt="img"
                  /></a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="modal fade" id="holdsales" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Hold order</h5>
            <button
              type="button"
              class="close"
              data-bs-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="hold-order">
              <h2>4500.00</h2>
            </div>
            <div class="form-group">
              <label>Order Reference</label>
              <input type="text" />
            </div>
            <div class="para-set">
              <p>
                The current order will be set on hold. You can retreive this
                order from the pending order button. Providing a reference to it
                might help you to identify the order more quickly.
              </p>
            </div>
            <div class="col-lg-12">
              <a class="btn btn-submit me-2">Submit</a>
              <a class="btn btn-cancel" data-bs-dismiss="modal">Cancel</a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="modal fade" id="edit" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Edit Order</h5>
            <button
              type="button"
              class="close"
              data-bs-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-lg-6 col-sm-12 col-12">
                <div class="form-group">
                  <label>Product Price</label>
                  <input type="text" value="20" />
                </div>
              </div>
              <div class="col-lg-6 col-sm-12 col-12">
                <div class="form-group">
                  <label>Product Price</label>
                  <select class="select">
                    <option>Exclusive</option>
                    <option>Inclusive</option>
                  </select>
                </div>
              </div>
              <div class="col-lg-6 col-sm-12 col-12">
                <div class="form-group">
                  <label> Discount</label>
                  <div class="input-group">
                    <input type="text" />
                    <a class="scanner-set input-group-text"> % </a>
                  </div>
                </div>
              </div>
              <div class="col-lg-6 col-sm-12 col-12">
                <div class="form-group">
                  <label>Discount Type</label>
                  <select class="select">
                    <option>Fixed</option>
                    <option>Percentage</option>
                  </select>
                </div>
              </div>
              <div class="col-lg-6 col-sm-12 col-12">
                <div class="form-group">
                  <label>Discount</label>
                  <input type="text" value="20" />
                </div>
              </div>
              <div class="col-lg-6 col-sm-12 col-12">
                <div class="form-group">
                  <label>Sales Unit</label>
                  <select class="select">
                    <option>Kilogram</option>
                    <option>Grams</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="col-lg-12">
              <a class="btn btn-submit me-2">Submit</a>
              <a class="btn btn-cancel" data-bs-dismiss="modal">Cancel</a>
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
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Create</h5>
            <button
              type="button"
              class="close"
              data-bs-dismiss="modal"
              aria-label="Close"
            >
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
            <div class="col-lg-12">
              <a class="btn btn-submit me-2">Submit</a>
              <a class="btn btn-cancel" data-bs-dismiss="modal">Cancel</a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="modal fade" id="delete" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Order Deletion</h5>
            <button
              type="button"
              class="close"
              data-bs-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="delete-order">
              <img src="assets/img/icons/close-circle1.svg" alt="img" />
            </div>
            <div class="para-set text-center">
              <p>
                The current order will be deleted as no payment has been <br />
                made so far.
              </p>
            </div>
            <div class="col-lg-12 text-center">
              <a class="btn btn-danger me-2">Yes</a>
              <a class="btn btn-cancel" data-bs-dismiss="modal">No</a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="modal fade" id="recents" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Recent Transactions</h5>
            <button
              type="button"
              class="close"
              data-bs-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="tabs-sets">
              <ul class="nav nav-tabs" id="myTabs" role="tablist">
                <li class="nav-item" role="presentation">
                  <button
                    class="nav-link active"
                    id="purchase-tab"
                    data-bs-toggle="tab"
                    data-bs-target="#purchase"
                    type="button"
                    aria-controls="purchase"
                    aria-selected="true"
                    role="tab"
                  >
                    Purchase
                  </button>
                </li>
                <li class="nav-item" role="presentation">
                  <button
                    class="nav-link"
                    id="payment-tab"
                    data-bs-toggle="tab"
                    data-bs-target="#payment"
                    type="button"
                    aria-controls="payment"
                    aria-selected="false"
                    role="tab"
                  >
                    Payment
                  </button>
                </li>
                <li class="nav-item" role="presentation">
                  <button
                    class="nav-link"
                    id="return-tab"
                    data-bs-toggle="tab"
                    data-bs-target="#return"
                    type="button"
                    aria-controls="return"
                    aria-selected="false"
                    role="tab"
                  >
                    Return
                  </button>
                </li>
              </ul>
              <div class="tab-content">
                <div
                  class="tab-pane fade show active"
                  id="purchase"
                  role="tabpanel"
                  aria-labelledby="purchase-tab"
                >
                  <div class="table-top">
                    <div class="search-set">
                      <div class="search-input">
                        <a class="btn btn-searchset"
                          ><img
                            src="assets/img/icons/search-white.svg"
                            alt="img"
                        /></a>
                      </div>
                    </div>
                    <div class="wordset">
                      <ul>
                        <li>
                          <a
                            data-bs-toggle="tooltip"
                            data-bs-placement="top"
                            title="pdf"
                            ><img src="assets/img/icons/pdf.svg" alt="img"
                          /></a>
                        </li>
                        <li>
                          <a
                            data-bs-toggle="tooltip"
                            data-bs-placement="top"
                            title="excel"
                            ><img src="assets/img/icons/excel.svg" alt="img"
                          /></a>
                        </li>
                        <li>
                          <a
                            data-bs-toggle="tooltip"
                            data-bs-placement="top"
                            title="print"
                            ><img src="assets/img/icons/printer.svg" alt="img"
                          /></a>
                        </li>
                      </ul>
                    </div>
                  </div>
                  <div class="table-responsive">
                    <table class="table datanew">
                      <thead>
                        <tr>
                          <th>Date</th>
                          <th>Reference</th>
                          <th>Customer</th>
                          <th>Amount</th>
                          <th class="text-end">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>2022-03-07</td>
                          <td>INV/SL0101</td>
                          <td>Walk-in Customer</td>
                          <td>P 1500.00</td>
                          <td>
                            <a class="me-3" href="javascript:void(0);">
                              <img src="assets/img/icons/eye.svg" alt="img" />
                            </a>
                            <a class="me-3" href="javascript:void(0);">
                              <img src="assets/img/icons/edit.svg" alt="img" />
                            </a>
                            <a
                              class="me-3 confirm-text"
                              href="javascript:void(0);"
                            >
                              <img
                                src="assets/img/icons/delete.svg"
                                alt="img"
                              />
                            </a>
                          </td>
                        </tr>
                       
                  
             
                  
                     
                       
                      </tbody>




                    </table>
                  </div>
                </div>
                <div class="tab-pane fade" id="payment" role="tabpanel">
                  <div class="table-top">
                    <div class="search-set">
                      <div class="search-input">
                        <a class="btn btn-searchset"
                          ><img
                            src="assets/img/icons/search-white.svg"
                            alt="img"
                        /></a>
                      </div>
                    </div>
                    <div class="wordset">
                      <ul>
                        <li>
                          <a
                            data-bs-toggle="tooltip"
                            data-bs-placement="top"
                            title="pdf"
                            ><img src="assets/img/icons/pdf.svg" alt="img"
                          /></a>
                        </li>
                        <li>
                          <a
                            data-bs-toggle="tooltip"
                            data-bs-placement="top"
                            title="excel"
                            ><img src="assets/img/icons/excel.svg" alt="img"
                          /></a>
                        </li>
                        <li>
                          <a
                            data-bs-toggle="tooltip"
                            data-bs-placement="top"
                            title="print"
                            ><img src="assets/img/icons/printer.svg" alt="img"
                          /></a>
                        </li>
                      </ul>
                    </div>
                  </div>
                  <div class="table-responsive">
                    <table class="table datanew">
                      <thead>
                        <tr>
                          <th>Date</th>
                          <th>Reference</th>
                          <th>Customer</th>
                          <th>Amount</th>
                          <th class="text-end">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                      
                     
        
                       
                    
                        <tr>
                          <td>2022-03-07</td>
                          <td>0107</td>
                          <td>Walk-in Customer</td>
                          <td>P 1500.00</td>
                          <td>
                            <a class="me-3" href="javascript:void(0);">
                              <img src="assets/img/icons/eye.svg" alt="img" />
                            </a>
                            <a class="me-3" href="javascript:void(0);">
                              <img src="assets/img/icons/edit.svg" alt="img" />
                            </a>
                            <a
                              class="me-3 confirm-text"
                              href="javascript:void(0);"
                            >
                              <img
                                src="assets/img/icons/delete.svg"
                                alt="img"
                              />
                            </a>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
                <div class="tab-pane fade" id="return" role="tabpanel">
                  <div class="table-top">
                    <div class="search-set">
                      <div class="search-input">
                        <a class="btn btn-searchset"
                          ><img
                            src="assets/img/icons/search-white.svg"
                            alt="img"
                        /></a>
                      </div>
                    </div>
                    <div class="wordset">
                      <ul>
                        <li>
                          <a
                            data-bs-toggle="tooltip"
                            data-bs-placement="top"
                            title="pdf"
                            ><img src="assets/img/icons/pdf.svg" alt="img"
                          /></a>
                        </li>
                        <li>
                          <a
                            data-bs-toggle="tooltip"
                            data-bs-placement="top"
                            title="excel"
                            ><img src="assets/img/icons/excel.svg" alt="img"
                          /></a>
                        </li>
                        <li>
                          <a
                            data-bs-toggle="tooltip"
                            data-bs-placement="top"
                            title="print"
                            ><img src="assets/img/icons/printer.svg" alt="img"
                          /></a>
                        </li>
                      </ul>
                    </div>
                  </div>
                  <div class="table-responsive">
                    <table class="table datanew">
                      <thead>
                        <tr>
                          <th>Date</th>
                          <th>Reference</th>
                          <th>Customer</th>
                          <th>Amount</th>
                          <th class="text-end">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>2022-03-07</td>
                          <td>0101</td>
                          <td>Walk-in Customer</td>
                          <td>P 1500.00</td>
                          <td>
                            <a class="me-3" href="javascript:void(0);">
                              <img src="assets/img/icons/eye.svg" alt="img" />
                            </a>
                            <a class="me-3" href="javascript:void(0);">
                              <img src="assets/img/icons/edit.svg" alt="img" />
                            </a>
                            <a
                              class="me-3 confirm-text"
                              href="javascript:void(0);"
                            >
                              <img
                                src="assets/img/icons/delete.svg"
                                alt="img"
                              />
                            </a>
                          </td>
                        </tr>
                       
                        
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const productListsContainer = document.getElementById('product-lists-container');
    const totalItemsElement = document.getElementById('total-items');
    const clearAllButton = document.getElementById('clear-all');
    const paymentMethods = document.querySelectorAll('.paymentmethod');

    // Elements for subtotal, discount, and total
    const subtotalElement = document.getElementById('subtotal-value');
    const discountElement = document.getElementById('discount-value');
    const totalElement = document.getElementById('total-value');
    const checkoutTotalElement = document.querySelector('.btn-totallabel h6'); // New element for checkout total

    function createProductList(categoryId) {
        const ul = document.createElement('ul');
        ul.className = 'product-lists';
        ul.dataset.categoryId = categoryId;
        return ul;
    }
    paymentMethods.forEach(function(method) {
        method.addEventListener('click', function() {
            // Remove 'active' class from all payment methods
            paymentMethods.forEach(function(m) {
                m.classList.remove('active');
            });
            
            // Add 'active' class to the clicked payment method
            this.classList.add('active');
        });
    });

    function addToProductList(item) {
        let ul = [...productListsContainer.querySelectorAll('ul.product-lists')].find(ul => 
            ul.querySelector(`li[data-id="${item.id}"]`)
        );

        if (!ul) {
            ul = createProductList(item.categoryId);
            productListsContainer.appendChild(ul);
        }

        const existingItem = ul.querySelector(`li[data-id="${item.id}"]`);
        if (existingItem) {
            const quantityField = existingItem.querySelector('.quantity-field');
            quantityField.value = parseInt(quantityField.value, 10) + 1;
        } else {
            const listItem = document.createElement('li');
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

            const priceItem = document.createElement('li');
            priceItem.dataset.id = item.id;
            priceItem.className = 'price-item'; // Added class to identify price items
            priceItem.textContent = `Price: ${item.price}`;

            const deleteItem = document.createElement('li');
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
        console.log('Adding product:', item); // Debug log
        addToProductList(item);
    }

    function updateTotals() {
        let subtotal = 0;
        let totalItems = 0;
        const discountPercentage = 0.10; // 10% discount for PWD/Senior

        [...productListsContainer.querySelectorAll('ul.product-lists')].forEach(ul => {
            [...ul.querySelectorAll('.price-item')].forEach(priceItem => {
                const priceText = priceItem.textContent.replace('Price: ', '');
                const price = parseFloat(priceText);
                const quantityField = priceItem.previousElementSibling.querySelector('.quantity-field');
                const quantity = parseInt(quantityField.value, 10);
                if (!isNaN(price) && !isNaN(quantity)) {
                    subtotal += price * quantity;
                }
            });
            totalItems += [...ul.querySelectorAll('.quantity-field')].reduce((total, field) => total + parseInt(field.value, 10), 0);
        });

        // Update subtotal
        subtotalElement.textContent = `${subtotal.toFixed(2)}₱`;

        // Calculate and update discount
        const discount = subtotal * discountPercentage;
        discountElement.textContent = `${discount.toFixed(2)}₱`;

        // Update total
        const total = subtotal - discount;
        totalElement.textContent = `${total.toFixed(2)}₱`;

        // Update total items count
        totalItemsElement.textContent = totalItems;

        // Update checkout total
        if (checkoutTotalElement) {
            checkoutTotalElement.textContent = `${total.toFixed(2)}₱`;
        }
    }




    $(document).ready(function() {
    $('#checkoutBtn').click(function() {
        // Collect data from the form
        var order_type = $('#orderType').val(); // Get selected order type
        var discount = $('#discount').val(); // Get selected discount type
        var total_amount = $('#totalAmount').text().replace('₱', '').replace(',', ''); // Clean up the amount
        var payment_method = $('input[name="payment_method"]:checked').val(); // Get the selected payment method

        // Collect order items from your cart (this is an example, adjust based on your cart structure)
        var order_items = []; // Replace with actual cart data
        $('.cart-item').each(function() {
            order_items.push({
                menu_name: $(this).find('.menu-name').text(),
                menu_image: $(this).find('.menu-image').attr('src'),
                category: $(this).find('.category').text(),
                subcategory: $(this).find('.subcategory').text(),
                quantity: $(this).find('.quantity').val(),
                price: $(this).find('.price').text().replace('₱', '').replace(',', '')
            });
        });

        // Data to be sent
        var data = {
            transaction_type: order_type,
            discount: discount,
            total_amount: total_amount,
            payment_method: payment_method,
            order_items: order_items
        };

        // Send data via AJAX
        $.ajax({
            url: 'process_checkout.php', // Your server-side script
            type: 'POST',
            data: data,
            success: function(response) {
                alert(response); // Show the response from the server
                // Optionally, redirect or clear cart
                window.location.href = 'pos.php'; // Redirect to a confirmation page
            },
            error: function() {
                alert('There was an error processing your order. Please try again.');
            }
        });
    });
});



    function handleQuantityChange(event) {
        const button = event.target;
        const input = button.parentElement.querySelector('.quantity-field');
        let value = parseInt(input.value, 10);

        if (isNaN(value)) {
            value = 0;
        }

        if (button.classList.contains('button-minus')) {
            if (value > 0) {
                value--;
            }
        } else if (button.classList.contains('button-plus')) {
            value++;
        }

        input.value = value;
        updateTotals(); // Update totals after changing quantity
    }

    function handleRemove(event) {
        const target = event.target.closest('.remove-item');
        if (target) {
            const ul = target.closest('ul.product-lists');
            if (ul) {
                ul.remove(); // Remove the entire <ul> element
                updateTotals(); // Update totals after removal
            }
        }
    }

    function clearAll() {
        productListsContainer.innerHTML = ''; // Remove all <ul> elements
        updateTotals(); // Update totals after clearing all
    }

    document.querySelectorAll('.productset').forEach(item => {
        item.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');
            const price = this.getAttribute('data-price');
            const image = this.getAttribute('data-image');
            const categoryId = this.getAttribute('data-category-id');
            
            addProductList({ id, name, price, image, categoryId });
        });
    });

    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('button-minus') || event.target.classList.contains('button-plus')) {
            handleQuantityChange(event);
        } else if (event.target.closest('.remove-item')) {
            handleRemove(event);
        }
    });

    clearAllButton.addEventListener('click', clearAll); // Attach the clearAll function to the "Clear all" button
});

</script>

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
