<?php
include 'db/config.php';


$categoryQuery = "SELECT c.c_id, c.category_name 
                  FROM categories c
                  LEFT JOIN menu m ON c.c_id = m.c_id
                  GROUP BY c.c_id, c.category_name";
$categoryResult = $conn->query($categoryQuery);


$subcategoryQuery = "SELECT s.sub_id, s.subcategory_name 
                     FROM subcategories s
                     LEFT JOIN menu m ON s.sub_id = m.sub_id
                     GROUP BY s.sub_id, s.subcategory_name";
$subcategoryResult = $conn->query($subcategoryQuery);
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0" />
    <meta name="keywords" content="admin, estimates, bootstrap, business, corporate, creative, invoice, html5, responsive, Projects" />
    <meta name="robots" content="noindex, nofollow" />
    <title>Add Product</title>

    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.jpg" />
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/css/animate.css" />
    <link rel="stylesheet" href="assets/plugins/select2/css/select2.min.css" />
    <link rel="stylesheet" href="assets/css/dataTables.bootstrap4.min.css" />
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css" />
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css" />
    <link rel="stylesheet" href="assets/css/style.css" />
    <link rel="stylesheet" href="assets/css/product.css" />


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
                        <h4>Menu Add</h4>
                        <h6>Create new Menu</h6>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <?php if (isset($_GET['success']) && $_GET['success'] == '1'): ?>
                            <div class="success-message show">Menu added successfully.</div>
                        <?php endif; ?>

                        <form action="db/insert.php" method="POST" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-lg-3 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label>Menu Name</label>
                                        <input type="text" name="menu_name" class="form-control" required />
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label>Menu Code</label>
                                        <input type="text" name="code" class="form-control" required />
                                    </div>
                                </div>

                                <div class="col-lg-3 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label>Category</label>
                                        <select name="category_name" id="c_id" required>
                                            <option value="">Choose Category</option>
                                            <?php
                                            if ($categoryResult->num_rows > 0) {
                                                while ($row = $categoryResult->fetch_assoc()) {
                                                    echo '<option value="' . htmlspecialchars($row['category_name']) . '">' . htmlspecialchars($row['category_name']) . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label>Sub Category</label>
                                        <select name="subcategory_name" id="sub_id" required>
                                            <option value="">Choose Sub Category</option>
                                            <?php
                                            if ($subcategoryResult->num_rows > 0) {
                                                while ($row = $subcategoryResult->fetch_assoc()) {
                                                    echo '<option value="' . htmlspecialchars($row['subcategory_name']) . '">' . htmlspecialchars($row['subcategory_name']) . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>


                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Description</label>
                                        <textarea name="description" class="form-control" required></textarea>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label>Discount Type</label>
                                        <select name="discount_type" class="form-control" required>
                                            <option value="">Choose Discount Type</option>
                                            <option value="Percentage">Percentage</option>
                                            <option value="10%">10%</option>
                                            <option value="20%">20%</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label>Price</label>
                                        <input type="number" name="price" class="form-control" step="0.01" min="0" required />
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label>Status</label>
                                        <select name="status" class="form-control" required>
                                            <option value="Available">Available</option>
                                            <option value="Not Available">Not Available</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-6 col-12">
                                    <div class="form-group image-upload">
                                        <input type="file" name="menu_image" id="file-upload" accept="image/*" style="display:none;" />
                                        <label for="file-upload" class="upload-label">
                                            <img src="assets/img/upload-image.png" alt="Upload Icon" class="upload-icon" />
                                            <h4>Upload Image</h4>
                                            <img id="image-preview" class="preview-image" />
                                        </label>
                                    </div>
                                </div>
                                <div class="col-lg-12 text-right">
                                    <button type="submit" class="btn btn-primary">Add Menu</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/js/jquery-3.5.1.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/plugins/select2/js/select2.min.js"></script>
    <script src="assets/js/jquery.dataTables.min.js"></script>
    <script src="assets/js/dataTables.bootstrap4.min.js"></script>
    <script src="assets/js/script.js"></script>
    <script>
        document.getElementById('file-upload').addEventListener('change', function() {
            var file = this.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    var img = document.getElementById('image-preview');
                    img.src = e.target.result;
                    img.style.display = 'block';
                }
                reader.readAsDataURL(file);
            }
        });


        setTimeout(function() {
            var message = document.querySelector('.success-message');
            if (message) {
                message.classList.remove('show');
            }
        }, 5000);
    </script>
</body>

</html>