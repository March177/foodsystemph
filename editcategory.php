<?php
// Include the database configuration file
include 'db/config.php';

// Check if the ID is provided
if (!isset($_GET['c_id'])) {
    die('Category ID not provided.');
}

// Get the category ID from the URL
$c_id = intval($_GET['c_id']);

// Fetch the category data from the database
$query = "SELECT * FROM categories WHERE c_id = $c_id";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $category = mysqli_fetch_assoc($result);
} else {
    die('Category not found.');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0" />
    <meta name="description" content="Category Edit Page" />
    <meta name="keywords" content="admin, edit, category, bootstrap, responsive" />
    <meta name="author" content="Your Name" />
    <meta name="robots" content="noindex, nofollow" />
    <title>Edit Category</title>

    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.jpg" />
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/css/animate.css" />
    <link rel="stylesheet" href="assets/plugins/select2/css/select2.min.css" />
    <link rel="stylesheet" href="assets/css/dataTables.bootstrap4.min.css" />
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css" />
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css" />
    <link rel="stylesheet" href="assets/css/style.css" />
    <style>
        .alert {
            display: none;
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border-radius: 5px;
            z-index: 1000;
        }
    </style>
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
                        <h4>Edit Category</h4>
                        <h6>Edit existing Category</h6>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="alert" id="success-alert">Update successful!</div>
                        <form id="edit-category-form">
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($category['c+id']); ?>" />

                            <div class="row">
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label>Category Name</label>
                                        <input type="text" name="category_name" class="form-control" value="<?php echo htmlspecialchars($category['category_name']); ?>" />
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label>Description</label>
                                        <input type="text" name="description" class="form-control" value="<?php echo htmlspecialchars($category['description']); ?>" />
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label>Status</label>
                                        <select name="status" class="form-control">
                                            <option value="">Choose Status</option>
                                            <option value="Not Available" <?php echo ($category['status'] == 'Not Available') ? 'selected' : ''; ?>>Not Available</option>
                                            <option value="Available" <?php echo ($category['status'] == 'Available') ? 'selected' : ''; ?>>Available</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <button type="submit" class="btn btn-primary">Update Category</button>
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
        $(document).ready(function() {
            $('#edit-category-form').on('submit', function(event) {
                event.preventDefault(); // Prevent default form submission

                $.ajax({
                    url: 'db/update_category.php',
                    type: 'POST',
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.trim() === 'success') {
                            $('#success-alert').fadeIn().delay(2000).fadeOut(); // Show and hide success message
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
    </script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/select2.min.js"></script>
    <script src="assets/js/script.js"></script>
</body>
</html>
