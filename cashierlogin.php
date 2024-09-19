<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cashier Login</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
</head>

<body>
    <div class="container">
        <h2>Cashier Login</h2>
        <form action="cashier_login.php" method="POST">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
        <div>
            <p>Don't have an account? <a href="cashier_signup.php">Sign Up</a></p>
        </div>
    </div>
</body>

</html>