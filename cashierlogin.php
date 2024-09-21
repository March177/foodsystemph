<?php
session_start();
include 'db/config.php'; // Include database configuration

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM cashiers WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['cashier_id'] = $row['cashier_id'];
            $_SESSION['cashier_name'] = $row['cashier_name'];
            header("Location: index.html");
            exit();
        } else {
            $error_message = "Invalid password.";
        }
    } else {
        $error_message = "No account found with that email.";
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
    <title>Cashier Login</title>
    <link rel="icon" href="images/favicon.png" />
    <link href="https://fonts.googleapis.com/css2?family=Lato:100,300,400,700,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="assets/css/admin.css" />
</head>

<body>
    <div class="wrapper">
        <div class="container">
            <form class="login-form" action="" method="POST">
                <span class="form-title">Cashier Login</span>

                <?php if (isset($error_message)): ?>
                    <div class="alert alert-danger"><?php echo $error_message; ?></div>
                <?php endif; ?>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" placeholder="Enter your email" required />
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
                </div>
                <div class="form-group">
                    <span class="forgot-link"><a href="register.php">Forgot password?</a></span>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary btn-lg btn-block" value="Login">
                </div>
                <p>Don't have an account? <a href="cashier_signup.php">Register here</a>.</p>
            </form>
        </div>
    </div>
</body>

</html>