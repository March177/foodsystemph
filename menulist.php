<?php
// Include the database configuration file
include 'db/config.php';

// Function to delete a menu item
function deleteMenuItem($conn, $menuId)
{
    $menuId = mysqli_real_escape_string($conn, $menuId);
    $query = "DELETE FROM menu WHERE menu_id = '$menuId'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        return true;
    } else {
        return false;
    }
}

$query = "
    SELECT 
        menu_id, 
        menu_name, 
        price, 
        image_path, 
        description, 
        discount_type, 
        status, 
        category_name
    FROM 
        menu
";

$rows = mysqli_query($conn, $query);

if (!$rows) {
    die('Query Error: ' . mysqli_error($conn));
}

// Handle delete request
if (isset($_POST['delete_menu']) && isset($_POST['menu_id'])) {
    $menuId = $_POST['menu_id'];
    if (deleteMenuItem($conn, $menuId)) {
        // Redirect to refresh the page after successful deletion
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        $deleteError = "Failed to delete menu item.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0" />
    <meta name="keywords" content="admin, estimates, bootstrap, business, corporate, creative, invoice, html5, responsive, Projects" />
    <meta name="robots" content="noindex, nofollow" />
    <title>Display Menu</title>

    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.jpg" />
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/css/animate.css" />
    <link rel="stylesheet" href="assets/plugins/select2/css/select2.min.css" />
    <link rel="stylesheet" href="assets/css/dataTables.bootstrap4.min.css" />
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css" />
    <link rel="stylesheet" href="assets/css/style.css" />
</head>

<body>

    <div class="main-wrapper">
        <div class="header">
            <?php include 'php/header.php'; ?>
        </div>
        <?php include 'php/sidebar.php'; ?>
        <div class="page-wrapper">
            <div class="content">
                <div class="page-header">
                    <div class="page-title">
                        <h4>Menu List</h4>
                        <h6>Manage your menu</h6>
                    </div>
                    <div class="page-btn">
                        <a href="addmenu.php" class="btn btn-added">
                            <img src="assets/img/icons/plus.svg" alt="img" class="me-1" /> Add New Menu
                        </a>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="table-top">
                            <div class="search-set">
                                <div class="search-path">
                                    <a class="btn btn-filter" id="filter_search">
                                        <img src="assets/img/icons/filter.svg" alt="img" />
                                        <span><img src="assets/img/icons/closes.svg" alt="img" /></span>
                                    </a>
                                </div>
                                <div class="search-input">
                                    <a class="btn btn-searchset">
                                        <img src="assets/img/icons/search-white.svg" alt="img" />
                                    </a>
                                </div>
                            </div>
                            <div class="wordset">
                                <ul>
                                    <li><a data-bs-toggle="tooltip" data-bs-placement="top" title="pdf"><img src="assets/img/icons/pdf.svg" alt="img" /></a></li>
                                    <li><a data-bs-toggle="tooltip" data-bs-placement="top" title="excel"><img src="assets/img/icons/excel.svg" alt="img" /></a></li>
                                    <li><a data-bs-toggle="tooltip" data-bs-placement="top" title="print"><img src="assets/img/icons/printer.svg" alt="img" /></a></li>
                                </ul>
                            </div>
                        </div>

                        <div class="card mb-0" id="filter_inputs">
                            <div class="card-body pb-0">
                                <div class="row">
                                    <div class="col-lg col-sm-6 col-12">
                                        <div class="form-group">
                                            <select class="select" name="menu">
                                                <option value="">Choose Menu</option>
                                                <?php
                                                $menu_query = "SELECT DISTINCT menu_name FROM menu";
                                                $menu_result = mysqli_query($conn, $menu_query);
                                                while ($menu = mysqli_fetch_assoc($menu_result)) {
                                                    echo "<option value='" . htmlspecialchars($menu['menu_name']) . "'>" . htmlspecialchars($menu['menu_name']) . "</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg col-sm-6 col-12">
                                        <div class="form-group">
                                            <select class="select" name="category">
                                                <option value="">Choose Category</option>
                                                <?php
                                                $category_query = "SELECT DISTINCT category_name FROM menu";
                                                $category_result = mysqli_query($conn, $category_query);
                                                while ($category = mysqli_fetch_assoc($category_result)) {
                                                    echo "<option value='" . htmlspecialchars($category['category_name']) . "'>" . htmlspecialchars($category['category_name']) . "</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg col-sm-6 col-12">
                                        <div class="form-group">
                                            <select class="select" name="price">
                                                <option value="">Choose Price</option>
                                                <option value="0-50">₱0 - ₱50</option>
                                                <option value="50-100">₱50 - ₱100</option>
                                                <option value="100+">₱100+</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-1 col-sm-6 col-12">
                                        <div class="form-group">
                                            <a class="btn btn-filters ms-auto" onclick="performFilter()">
                                                <img src="assets/img/icons/search-whites.svg" alt="img" />
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table datanew">
                                <thead>
                                    <tr>
                                        <th>
                                            <label class="checkboxs">
                                                <input type="checkbox" id="select-all" />
                                                <span class="checkmarks"></span>
                                            </label>
                                        </th>
                                        <th>Menu Name</th>
                                        <th>Category</th>
                                        <th>Price</th>
                                        <th>Description</th>
                                        <th>Discount Type</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = mysqli_fetch_assoc($rows)) : ?>
                                        <tr>
                                            <td>
                                                <label class="checkboxs">
                                                    <input type="checkbox" />
                                                    <span class="checkmarks"></span>
                                                </label>
                                            </td>
                                            <td class="productimgname">
                                                <a href="javascript:void(0);" class="product-img">
                                                    <img src="<?php echo htmlspecialchars($row['image_path']); ?>" alt="product" onerror="this.src='assets/img/default.jpg';" />
                                                </a>
                                                <a href="javascript:void(0);"><?php echo htmlspecialchars($row['menu_name']); ?></a>
                                            </td>
                                            <td><?php echo htmlspecialchars($row['category_name']); ?></td>
                                            <td><?php echo htmlspecialchars($row['price']); ?></td>
                                            <td><?php echo htmlspecialchars(substr($row['description'], 0, 30)) . '...'; ?></td>
                                            <td><?php echo htmlspecialchars($row['discount_type']); ?></td>
                                            <td><?php echo htmlspecialchars($row['status']); ?></td>
                                            <td>
                                                <a class="me-3" href="menu-details.php?id=<?php echo $row['menu_id']; ?>">
                                                    <img src="assets/img/icons/eye.svg" alt="img" />
                                                </a>
                                                <a class="me-3" href="editmenu.php?id=<?php echo $row['menu_id']; ?>">
                                                    <img src="assets/img/icons/edit.svg" alt="img" />
                                                </a>
                                                <!-- Example for deleting a menu item -->
                                                <a class="me-3 confirm-texts" data-id="<?php echo $row['menu_id']; ?>" data-type="menu" href="javascript:void(0);">
                                                    <img src="assets/img/icons/delete.svg" alt="img" />
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <script src="assets/js/feather.min.js"></script>
    <script src="assets/js/jquery.slimscroll.min.js"></script>
    <script src="assets/js/jquery.dataTables.min.js"></script>
    <script src="assets/js/dataTables.bootstrap4.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/plugins/select2/js/select2.min.js"></script>
    <script src="assets/plugins/sweetalert/sweetalert2.all.min.js"></script>
    <script src="assets/plugins/sweetalert/sweetalerts.min.js"></script>
    <script src="assets/js/script.js"></script>

</body>

</html>