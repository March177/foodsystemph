<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cashier Registration</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
</head>

<body>
    <div class="container">
        <h2>Register Cashier</h2>
        <form action="cashier_signup.php" method="POST" enctype="multipart/form-data">
            <input type="text" name="cashier_name" placeholder="Cashier Name" required>
            <input type="number" name="age" placeholder="Age" required>
            <select name="gender" required>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="file" name="image" accept="image/*" required>
            <button type="submit">Register Cashier</button>
        </form>
    </div>
</body>

</html>