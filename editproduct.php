<?php

include 'db/config.php';

if (!isset($_GET['id'])) {
    die('Menu ID not provided.');
}

$menu_id = intval($_GET['id']);

$query = "SELECT * FROM menu WHERE id = $menu_id";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $menu = mysqli_fetch_assoc($result);
} else {
    die('Menu not found.');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0" />
    <meta name="robots" content="noindex, nofollow" />
    <title>Edit Product</title>

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
        <div> <?php include 'php/sidebar.php'; ?>
        </div>
        <div class="page-wrapper">
            <div class="content">
                <div class="page-header">
                    <div class="page-title">
                        <h4>Edit Menu</h4>
                        <h6>Edit existing Menu</h6>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="alert" id="success-alert">Update successful!</div>
                        <form id="edit-menu-form" enctype="multipart/form-data">
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($menu['id']); ?>" />
                            <input type="hidden" name="existing_image" value="<?php echo htmlspecialchars($menu['image_path']); ?>" />

                            <div class="row">
                                <div class="col-lg-3 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label>Menu Name</label>
                                        <input type="text" name="menu_name" class="form-control" value="<?php echo htmlspecialchars($menu['menu_name']); ?>" />
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label>Menu Code</label>
                                        <input type="text" name="code" class="form-control" value="<?php echo htmlspecialchars($menu['code']); ?>" />
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label>Category</label>
                                        <select name="category" class="form-control">
                                            <option value="">Choose Category</option>
                                            <?php
                                            $query = "SELECT DISTINCT category FROM menu";
                                            $result = $conn->query($query);
                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                                    $selected = ($menu['category'] == $row['category']) ? 'selected' : '';
                                                    echo '<option value="' . htmlspecialchars($row['category']) . '" ' . $selected . '>' . htmlspecialchars($row['category']) . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label>Sub Category</label>
                                        <select name="subcategory" class="form-control">
                                            <option value="">Choose Sub Category</option>
                                            <?php
                                            $query = "SELECT DISTINCT subcategory FROM menu WHERE subcategory IS NOT NULL AND subcategory != ''";
                                            $result = $conn->query($query);
                                            if ($result && $result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                                    $selected = ($menu['subcategory'] == $row['subcategory']) ? 'selected' : '';
                                                    echo '<option value="' . htmlspecialchars($row['subcategory']) . '" ' . $selected . '>' . htmlspecialchars($row['subcategory']) . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Description</label>
                                        <textarea name="description" class="form-control"><?php echo htmlspecialchars($menu['description']); ?></textarea>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label>Discount Type</label>
                                        <select name="discount_type" class="form-control">
                                            <option value="">Choose Discount</option>
                                            <option value="Percentage" <?php echo ($menu['discount_type'] == 'Percentage') ? 'selected' : ''; ?>>Percentage</option>
                                            <option value="10%" <?php echo ($menu['discount_type'] == '10%') ? 'selected' : ''; ?>>10%</option>
                                            <option value="20%" <?php echo ($menu['discount_type'] == '20%') ? 'selected' : ''; ?>>20%</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label>Price</label>
                                        <input type="text" name="price" class="form-control" value="<?php echo htmlspecialchars($menu['price']); ?>" />
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label>Status</label>
                                        <select name="status" class="form-control">
                                            <option value="Active" <?php echo ($menu['status'] == 'Active') ? 'selected' : ''; ?>>Active</option>
                                            <option value="Inactive" <?php echo ($menu['status'] == 'Inactive') ? 'selected' : ''; ?>>Inactive</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label>Image</label>
                                        <?php if (!empty($menu['image_path'])): ?>
                                            <div class="image-preview-container">
                                                <img id="image-preview" src="<?php echo htmlspecialchars($menu['image_path']); ?>" alt="Menu Image" class="existing-image" />
                                            </div>
                                        <?php else: ?>
                                            <div class="image-preview-container">
                                                <img id="image-preview" src="#" alt="Menu Image" style="display: none;" class="existing-image" />
                                            </div>
                                        <?php endif; ?>
                                        <input type="file" id="image-input" name="image" class="form-control" />
                                        <small>Leave blank if you do not want to update the image.</small>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <button type="submit" class="btn btn-primary">Update Menu</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var imageInput = document.getElementById('image-input');
            var imagePreview = document.getElementById('image-preview');

            imageInput.addEventListener('change', function() {
                var file = this.files[0];
                if (file) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        imagePreview.src = e.target.result;
                        imagePreview.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                } else {
                    imagePreview.src = '#';
                    imagePreview.style.display = 'none';
                }
            });

            $(document).ready(function() {
                $('#edit-menu-form').on('submit', function(event) {
                    event.preventDefault();

                    $.ajax({
                        url: 'db/update.php',
                        type: 'POST',
                        data: new FormData(this),
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            if (response.trim() === 'success') {
                                $('#success-alert').fadeIn().delay(2000).fadeOut();
                            } else {
                                alert('An error occurred: ' + response);
                            }
                        },
                        error: function() {
                            alert('An error occurred. Please try again.');
                        }
                    });
                });
            });
        });
    </script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/select2.min.js"></script>
    <script src="assets/js/script.js"></script>
</body>

</html>