<?php
// Include the database connection file
include 'db.php';

session_start(); // Start a session to store user data upon successful login

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form inputs
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

        $admin_email = "Admin@gmail.com";
        $admin_password = "admin1234";

        if ($email === $admin_email && $password === $admin_password) {
            // Set session variables for the admin
            $_SESSION['admin'] = true;
            $_SESSION['admin_name'] = "Admin";
            $_SESSION['email'] = $admin_email;
            
            // Redirect to the admin dashboard
            header("Location: admin_dashboard.php");
            exit();
        } else {
            // Query to get user with the given email
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);

        
        
        // Verify the password
        if (password_verify($password, $row['password'])) {
            // Password matches, set session variables
            $_SESSION['contractor_id'] = $row['id'];
            $_SESSION['contractor_name'] = $row['name'];

            $_SESSION['email'] = $row['email'];
            header("Location: home.php");
            exit();
        } else {
            $error_message = "Invalid password.";
        }
    } else {
        $error_message = "No user found with that email.";
    }
        }    
    
    
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>

<div class="container">
    <h2>Login</h2>
    
    <!-- Display error message if set -->
    <?php if (isset($error_message)): ?>
        <div class="error"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <form action="" method="post">
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required>

        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>

        <button type="submit">Login</button>
    </form>

    <div class="form-footer">
        <p>Don't have an account? <a href="join.php">Sign up here</a></p>
        <p>Continue without login? <a href="home.php">Click here...</a></p>
    </div>
</div>

</body>
</html>