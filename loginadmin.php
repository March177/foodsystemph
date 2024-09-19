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
<html>

<head>
    <title>Admin Login</title>
</head>

<body>
    <h1>Admin Login</h1>
    <form action="loginadmin.php" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <button type="submit" name="login">Login</button>
    </form>
</body>

</html>