<?php
session_start();
include './db_connection.php'; // Include the database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Extract form data
    $name = $_POST['name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $usertype = $_POST['usertype'];
    $userOtp = $_POST['otp'];

    // Basic validation
    if (empty($name) || empty($username) || empty($email) || empty($phone) || empty($password) || empty($confirm_password) || empty($usertype)) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
        exit();
    }

    // Check if passwords match
    if ($password !== $confirm_password) {
        echo json_encode(['status' => 'error', 'message' => 'Passwords do not match.']);
        exit();
    }

    // Validate OTP
    if (!isset($_SESSION['otp']) || $_SESSION['otp'] !== $userOtp) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid OTP.']);
        exit();
    }

    // Clear the OTP from session after it's validated
    unset($_SESSION['otp']);

    // Hash the password before storing it in the database
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Check if the username or email already exists in the database
    if (isUserExists($email, $username)) {
        echo json_encode(['status' => 'error', 'message' => 'Email or username already exists.']);
        exit();
    }

    // Insert user into the database
    if (insertUser($name, $username, $email, $phone, $hashedPassword, $usertype)) {
        echo json_encode(['status' => 'success', 'message' => 'Registration successful!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Registration failed. Please try again later.']);
    }
}

// Function to check if a username or email already exists in the database
function isUserExists($email, $username) {
    global $conn; // Use the global $conn object from db_connection.php
    try {
        $query = "SELECT * FROM users WHERE email = '$email' OR username = '$username'";
        $result = mysqli_query($conn, $query);
        return mysqli_fetch_assoc($result); // Return user data if found
    } catch (Exception $e) {
        // Handle any exception
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
        exit();
    }
}

// Function to insert user data into the users table
function insertUser($name, $username, $email, $phone, $hashedPassword, $usertype) {
    global $conn; // Use the global $conn object from db_connection.php
    try {
        $query = "INSERT INTO users (name, username, email, phone, password, usertype) 
                  VALUES ('$name', '$username', '$email', '$phone', '$hashedPassword', '$usertype')";
        return mysqli_query($conn, $query);
    } catch (Exception $e) {
        // Handle any exception
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
        return false;
    }
}
?>
