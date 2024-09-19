<?php
session_start();

// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Check if POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST['email'];
  $password = md5($_POST['password']); // Hashing the password

  $sql = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $_SESSION['email'] = $email;
    echo "success";
  } else {
    echo "Invalid email or password";
  }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta
    name="viewport"
    content="width=device-width, initial-scale=1.0, user-scalable=0" />

  <meta
    name="keywords"
    content="admin, estimates, bootstrap, business, corporate, creative, invoice, html5, responsive, Projects" />
  <meta name="author" content="" />
  <meta name="robots" content="noindex, nofollow" />
  <title>Login</title>

  <link
    rel="shortcut icon"
    type="image/x-icon"
    href="assets/img/favicon.jpg" />
  <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
  <link
    rel="stylesheet"
    href="assets/plugins/fontawesome/css/fontawesome.min.css" />
  <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css" />
  <link rel="stylesheet" href="assets/css/style.css" />
</head>

<body class="account-page">
  <div class="main-wrapper">
    <div class="account-content">
      <div class="login-wrapper">
        <div class="login-content">
          <div class="login-userset">
            <div class="login-logo">
              <img src="assets/img/logo.png" alt="img" />
            </div>
            <div class="login-userheading">
              <h3>Sign In</h3>
              <h4>Please login to your account</h4>
            </div>
            <form id="loginForm" action="signin.php" method="POST">
              <div class="form-login">
                <label>Email</label>
                <div class="form-addons">
                  <input
                    type="text"
                    id="loginEmail"
                    name="email"
                    placeholder="Enter your email address" />
                  <img src="assets/img/icons/mail.svg" alt="img" />
                </div>
              </div>
              <div class="form-login">
                <label>Password</label>
                <div class="pass-group">
                  <input
                    type="password"
                    id="loginPassword"
                    name="password"
                    class="pass-input"
                    placeholder="Enter your password" />
                  <span class="fas toggle-password fa-eye-slash"></span>
                </div>
              </div>
              <div class="form-login">
                <div class="alreadyuser">
                  <h4>
                    <a href="forgetpassword.html" class="hover-a">Forgot Password?</a>
                  </h4>
                </div>
              </div>
              <div class="form-login">
                <button type="submit" class="btn btn-login">Sign In</button>
              </div>
            </form>
            <div class="signinform text-center">
              <h4>
                Don’t have an account?
                <a href="signup.html" class="hover-a">Sign Up</a>
              </h4>
            </div>
            <div class="form-setlogin">
              <h4>Or sign up with</h4>
            </div>
            <div class="form-sociallink">
              <ul>
                <li>
                  <a href="javascript:void(0);">
                    <img
                      src="assets/img/icons/google.png"
                      class="me-2"
                      alt="google" />
                    Sign Up using Google
                  </a>
                </li>
                <li>
                  <a href="javascript:void(0);">
                    <img
                      src="assets/img/icons/facebook.png"
                      class="me-2"
                      alt="facebook" />
                    Sign Up using Facebook
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </div>
        <div class="login-img">
          <img src="assets/img/login.jpg" alt="img" />
        </div>
      </div>
    </div>
  </div>

  <script src="assets/js/jquery-3.6.0.min.js"></script>
  <script src="assets/js/feather.min.js"></script>
  <script src="assets/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/script.js"></script>

  <script>
    $("#loginForm").on("submit", function(event) {
      event.preventDefault();
      var email = $("#loginEmail").val();
      var password = $("#loginPassword").val();

      // Simple validation
      if (!email || !password) {
        alert("Please fill in all fields");
        return;
      }

      $.ajax({
        url: "signin.php",
        type: "POST",
        data: $(this).serialize(),
        success: function(response) {
          if (response === "success") {
            window.location.href = "index.html";
          } else {
            alert(response);
          }
        },
      });
    });
  </script>
</body>

</html>