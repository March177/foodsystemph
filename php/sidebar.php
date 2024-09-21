<?php
$current_page = basename($_SERVER['PHP_SELF']); // Get the current page's filename

// Function to determine if a page is active
function isActive($page)
{
  global $current_page;
  return $current_page == $page ? 'active' : '';
}
?>

<div class="sidebar" id="sidebar">
  <div class="sidebar-inner slimscroll">
    <div id="sidebar-menu" class="sidebar-menu">
      <ul>
        <li class="<?php echo isActive('index.html'); ?>">
          <a href="index.html">
            <img src="assets/img/icons/dashboard.svg" alt="img" /><span>Dashboard</span>
          </a>
        </li>
        <li class="submenu">
          <a href="javascript:void(0);">
            <img src="assets/img/icons/product.svg" alt="img" /><span>Menu</span>
            <span class="menu-arrow"></span>
          </a>
          <ul>
            <li><a href="productlist.php">Menu List</a></li>
            <li><a href="addproduct.php">Add Menu</a></li>
            <li><a href="categorylist.php">Category List</a></li>
            <li><a href="addcategory.php">Add Category</a></li>
            <li><a href="subcategorylist.php">Sub Category List</a></li>
            <li><a href="subaddcategory.php">Add Sub Category</a></li>
          </ul>
        </li>
        <li class="submenu">
          <a href="javascript:void(0);">
            <img src="assets/img/icons/purchase1.svg" alt="img" /><span>Review</span>
            <span class="menu-arrow"></span>
          </a>
          <ul>
            <li><a href="reviewlist.html">Review List</a></li>
          </ul>
        </li>
        <li class="submenu">
          <a href="javascript:void(0);">
            <img src="assets/img/icons/quotation1.svg" alt="img" /><span>Discount</span>
            <span class="menu-arrow"></span>
          </a>
          <ul>
            <li><a href="discountlist.html">Discount List</a></li>
            <li><a href="discount.html">Add Discount</a></li>
          </ul>
        </li>
        <li class="submenu">
          <a href="javascript:void(0);">
            <img src="assets/img/icons/users1.svg" alt="img" /><span>People</span>
            <span class="menu-arrow"></span>
          </a>
          <ul>
            <li><a href="customerlist.html">Customer List</a></li>
            <li><a href="addcustomer.html">Add Customer</a></li>
            <li><a href="userlist.html">User List</a></li>
            <li><a href="adduser.html">Add User</a></li>
          </ul>
        </li>
        <li class="submenu">
          <a href="javascript:void(0);">
            <img src="assets/img/icons/time.svg" alt="img" /><span>Report</span>
            <span class="menu-arrow"></span>
          </a>
          <ul>
            <li><a href="purchaseorderreport.html">Purchase order report</a></li>
            <li><a href="inventoryreport.html">Inventory Report</a></li>
            <li><a href="salesreport.html">Sales Report</a></li>
            <li><a href="invoicereport.html">Invoice Report</a></li>
            <li><a href="purchasereport.html">Purchase Report</a></li>
            <li><a href="customerreport.html">Customer Report</a></li>
          </ul>
        </li>
        <li class="submenu">
          <a href="javascript:void(0);">
            <img src="assets/img/icons/users1.svg" alt="img" /><span>Users</span>
            <span class="menu-arrow"></span>
          </a>
          <ul>
            <li><a href="newuser.html">New User</a></li>
            <li><a href="userlists.html">Users List</a></li>
          </ul>
        </li>
        <li class="submenu">
          <a href="javascript:void(0);">
            <img src="assets/img/icons/settings.svg" alt="img" /><span>Settings</span>
            <span class="menu-arrow"></span>
          </a>
          <ul>
            <li><a href="generalsettings.html">General Settings</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</div>