<?php
session_start();
include 'db/config.php'; // Include database configuration

// Configuration
define('MAX_DEVICES', 3); // Limit the admin to 3 devices

function getDeviceId()
{
    return md5($_SERVER['HTTP_USER_AGENT'] . $_SERVER['REMOTE_ADDR']);
}

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $hashed_password = md5($password);

    $query = "SELECT * FROM admins WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $username, $hashed_password);
    $stmt->execute();
    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();

    if ($admin) {
        $admin_id = $admin['id'];
        $device_id = getDeviceId();
        $ip_address = $_SERVER['REMOTE_ADDR'];
        $user_agent = $_SERVER['HTTP_USER_AGENT'];

        $query = "SELECT COUNT(*) as active_sessions FROM device_sessions WHERE admin_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $admin_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row['active_sessions'] < MAX_DEVICES) {
            $_SESSION['admin_id'] = $admin_id;

            $query = "INSERT INTO device_sessions (admin_id, device_id, ip_address, user_agent) 
                      VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("isss", $admin_id, $device_id, $ip_address, $user_agent);
            if (!$stmt->execute()) {
                echo "Error: " . $stmt->error;
            } else {
                header("Location: index.html");
                exit;
            }
        } else {
            echo "Device limit reached. Please log out from another device first.";
        }
    } else {
        echo "Invalid login credentials.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Login Form</title>
    <link rel="icon" href="images/favicon.png" />
    <link href="https://fonts.googleapis.com/css2?family=Lato:100,300,400,700,900&display=swap" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="assets/css/admin.css" />


</head>

<body>
    <div class="wrapper">
        <div class="container">
            <form class="login-form" action="" method="post">
                <span class="form-title">Log in</span>
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control" placeholder="Enter your username" required />
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Enter your Password" required>
                </div>
                <div class="form-group">
                    <span class="forgot-link"><a href="register.php">forgot password?</a></span>
                </div>
                <div class="form-group">
                    <input type="submit" name="login" class="btn btn-primary btn-lg btn-block" value="Login">
                </div>
                <p>Don't have an account? <a href="admin_signup.php">Register here</a>.</p>
            </form>
        </div>

    </div>
</body>

</html>