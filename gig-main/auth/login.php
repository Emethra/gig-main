<?php
session_start();
include './db_connection.php';
include './fun.php';

function loginUser($email, $password, $otp, $conn) {
    session_start();

    // Validate if OTP matches the one stored in session
    if (!isset($_SESSION['otp']) || $_SESSION['otp'] !== $otp) {
        return ['status' => 'error', 'message' => 'Invalid OTP.'];
    }

    // Clear OTP after it's validated
    unset($_SESSION['otp']);

    // Check if the user exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user) {
        return ['status' => 'error', 'message' => 'No user found with this email.'];
    }

    // Verify password
    if (!password_verify($password, $user['password'])) {
        return ['status' => 'error', 'message' => 'Incorrect password.'];
    }

    // Log the user in by setting session data
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['name'];

    return ['status' => 'success', 'message' => 'Login successful!'];
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Extract form data
    $email = $_POST['email'];
    $password = $_POST['password'];
    $otp = $_POST['otp'];

    // Basic validation
    if (empty($email) || empty($password) || empty($otp)) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
        exit();
    }

    // Call the login function and get the result
    $loginResult = loginUser($email, $password, $otp, $conn);

    // Output the result as JSON
    echo json_encode($loginResult);
}
?>
