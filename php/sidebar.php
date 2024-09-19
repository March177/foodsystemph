<div class="sidebar" id="sidebar">
  <div class="sidebar-inner slimscroll">
    <div id="sidebar-menu" class="sidebar-menu">
      <ul>
        <li>
          <a href="index.html"><img src="assets/img/icons/dashboard.svg" alt="img" /><span>
              Dashboard</span>
          </a>
        </li>
        <li class="submenu">
          <a href="javascript:void(0);"><img src="assets/img/icons/product.svg" alt="img" /><span>
              Menu</span>
            <span class="menu-arrow"></span></a>
          <ul>
            <li><a href="productlist.php" class="menu-item">Menu List</a></li>
            <li><a href="addproduct.php" class="menu-item">Add Menu</a></li>

            <li><a href="categorylist.php">Category List</a></li>
            <li><a href="addcategory.php">Add Category</a></li>
            <li><a href="subcategorylist.php">Sub Category List</a></li>
            <li><a href="addsubcategory.php">Add Sub Category</a></li>

          </ul>
        </li>
        <li class="submenu">
          <a href="javascript:void(0);"><img src="assets/img/icons/sales1.svg" alt="img" /><span>
              Sales</span>
            <span class="menu-arrow"></span></a>
          <ul>
            <li><a href="saleslist.html">Sales List</a></li>
            <li><a href="pos.html">POS</a></li>


          </ul>
        </li>



        <li class="submenu">
          <a href="javascript:void(0);"><img src="assets/img/icons/users1.svg" alt="img" /><span>
              People</span>
            <span class="menu-arrow"></span></a>
          <ul>
            <li><a href="customerlist.html">Customer List</a></li>
            <li><a href="addcustomer.html">Add Customer </a></li>
            <li><a href="userlist.html">User List</a></li>
            <li><a href="adduser.html">Add User</a></li>

          </ul>
        </li>








        <li class="submenu">
          <a href="javascript:void(0);"><img src="assets/img/icons/time.svg" alt="img" /><span>
              Report</span>
            <span class="menu-arrow"></span></a>
          <ul>
            <li>
              <a href="purchaseorderreport.html">Purchase order report</a>
            </li>
            <li><a href="inventoryreport.html">Inventory Report</a></li>
            <li><a href="salesreport.html">Sales Report</a></li>
            <li><a href="invoicereport.html">Invoice Report</a></li>
            <li><a href="customerreport.html">Customer Report</a></li>
          </ul>
        </li>
        <li class="submenu">
          <a href="javascript:void(0);"><img src="assets/img/icons/users1.svg" alt="img" /><span>
              Users</span>
            <span class="menu-arrow"></span></a>
          <ul>
            <li><a href="newuser.html">New User </a></li>
            <li><a href="userlists.html">Users List</a></li>
          </ul>
        </li>
        <li class="submenu">
          <a href="javascript:void(0);"><img src="assets/img/icons/settings.svg" alt="img" /><span>
              Settings</span>
            <span class="menu-arrow"></span></a>
          <ul>
            <li><a href="generalsettings.html">General Settings</a></li>

            <li><a href="paymentsettings.html">Payment Settings</a></li>


          </ul>
        </li>
      </ul>
    </div>
  </div>
</div>


<script>
  document.addEventListener('DOMContentLoaded', () => {

    const menuLinks = document.querySelectorAll('#sidebar-menu .menu-item');


    const currentPage = window.location.href.split('/').pop();

    menuLinks.forEach(link => {
      const linkHref = link.getAttribute('href').split('/').pop();


      if (linkHref === currentPage) {
        link.classList.add('active');
      } else {
        link.classList.remove('active');
      }


      link.addEventListener('click', () => {

        menuLinks.forEach(link => link.classList.remove('active'));


        link.classList.add('active');
      });
    });
  });
</script>