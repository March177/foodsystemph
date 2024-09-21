<?php
session_start();
include 'db/config.php'; // Include database configuration

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cashier_name = $_POST['cashier_name'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password

    $sql = "INSERT INTO cashiers (cashier_name, age, gender, email, password) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sisss", $cashier_name, $age, $gender, $email, $password);

    if ($stmt->execute()) {
        echo "Cashier registered successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cashier Registration</title>
    <link rel="icon" href="images/favicon.png" />
    <link href="https://fonts.googleapis.com/css2?family=Lato:100,300,400,700,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="assets/css/cashier.css" />
</head>

<body>
    <div class="wrapper">
        <div class="container">
            <form class="login-form" action="" method="POST">
                <span class="form-title">Cashier Registration</span>

                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="cashier_name" class="form-control" placeholder="Enter your name" required />
                </div>
                <div class="form-group">
                    <label>Age</label>
                    <input type="number" name="age" class="form-control" placeholder="Enter your age" required />
                </div>
                <div class="form-group">
                    <label>Gender</label>
                    <select name="gender" class="form-control" required>
                        <option value="">Select your gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" placeholder="Enter your email" required />
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Enter your password" required />
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary btn-lg btn-block" value="Register">
                </div>
                <p>Already have an account? <a href="cashierlogin.php">Login here</a>.</p>
            </form>
        </div>
    </div>
</body>

</html>