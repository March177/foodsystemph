<?php
// Include the database configuration file
include 'db/config.php';

// Fetch data from the database with JOINs
$query = "
    SELECT 
        id, 
        menu_name, 
        price, 
        created_by, 
        image_path, 
        description, 
        discount_type, 
        status, 
        category_name, 
        subcategory_name
    FROM 
        menu
";

$rows = mysqli_query($conn, $query);

if (!$rows) {
    die('Query Error: ' . mysqli_error($conn));
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

<body onload="table();">
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
                        <a href="addproduct.php" class="btn btn-added">
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
                                                <?php foreach ($menus as $menu): ?>
                                                    <option value="<?php echo htmlspecialchars($menu); ?>"><?php echo htmlspecialchars($menu); ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg col-sm-6 col-12">
                                        <div class="form-group">
                                            <select class="select" name="category">
                                                <option value="">Choose Category</option>
                                                <?php foreach ($categories as $category): ?>
                                                    <option value="<?php echo htmlspecialchars($category); ?>"><?php echo htmlspecialchars($category); ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg col-sm-6 col-12">
                                        <div class="form-group">
                                            <select class="select" name="subcategory">
                                                <option value="">Choose Sub Category</option>

                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg col-sm-6 col-12">
                                        <div class="form-group">
                                            <select class="select" name="price">
                                                <option value="">Choose Price</option>

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

                        <div id="table">
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
                                            <th>SubCategory</th>
                                            <th>Price</th>
                                            <th>Created By</th>
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
                                                <td><?php echo htmlspecialchars($row['subcategory_name']); ?></td>
                                                <td><?php echo htmlspecialchars($row['price']); ?></td>
                                                <td><?php echo htmlspecialchars($row['created_by']); ?></td>
                                                <td><?php echo htmlspecialchars($row['description']); ?></td>
                                                <td><?php echo htmlspecialchars($row['discount_type']); ?></td>
                                                <td><?php echo htmlspecialchars($row['status']); ?></td>
                                                <td>
                                                    <a class="me-3" href="product-details.php">
                                                        <img src="assets/img/icons/eye.svg" alt="img" />
                                                    </a>
                                                    <a class="me-3" href="editproduct.php">
                                                        <img src="assets/img/icons/edit.svg" alt="img" />
                                                    </a>
                                                    <a class="me-3" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#delete">
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

    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/datatables.min.js"></script>
    <script src="assets/js/script.js"></script>
</body>

</html>