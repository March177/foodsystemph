<?php
include 'db/config.php';
$conn = mysqli_connect("localhost", "root", "", "restaurant_db");

$rows = mysqli_query($conn, "SELECT * FROM menu");
?>
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
                <th>Code</th>
                <th>Category</th>
                <th>SubCategory</th>
                <th>Price</th>
                <th>Created By</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($rows as $row) : ?>
            <tr>
                <td>
                    <label class="checkboxs">
                        <input type="checkbox" />
                        <span class="checkmarks"></span>
                    </label>
                </td>
                <td class="productimgname">
                    <a href="javascript:void(0);" class="product-img">
                        <img src="<?php echo $row['image_path']; ?>" alt="product" />
                    </a>
                    <a href="javascript:void(0);"><?php echo $row['menu_name']; ?></a>
                </td>
                <td><?php echo $row['code']; ?></td>
                <td><?php echo $row['category']; ?></td>
                <td><?php echo $row['subcategory']; ?></td>
                <td><?php echo number_format($row['price'], 2); ?></td>
                <td><?php echo $row['created_by']; ?></td>
                <td>
                    <a class="me-3" href="product-details.php">
                        <img src="assets/img/icons/eye.svg" alt="img" />
                    </a>
                    <a class="me-3" href="editproduct.php">
                        <img src="assets/img/icons/edit.svg" alt="img" />
                    </a>
                    <a class="confirm-text" href="javascript:void(0);" onclick="deleteProduct(<?php echo $row['id']; ?>);">
                        <img src="assets/img/icons/delete.svg" alt="img" />
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
