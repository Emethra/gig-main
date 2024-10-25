<?php
// Include your database connection file
include('db.php');

// Check if an ID is provided via GET method
if (isset($_GET['id'])) {
    // Get the user ID from the URL
    $user_id = $_GET['id'];

    // Prepare the SQL DELETE statement
    $sql = "DELETE FROM users WHERE id = ?";

    // Initialize a prepared statement
    $stmt = $conn->prepare($sql);

    // Bind the user ID to the prepared statement
    $stmt->bind_param("i", $user_id);

    // Execute the query
    if ($stmt->execute()) {
        // If successful, redirect back to the user management page
        header("Location: Manage_user.php?message=User+deleted+successfully");
    } else {
        // If there is an error, display a message
        echo "Error deleting user: " . $conn->error;
    }

    // Close the statement and the connection
    $stmt->close();
    $conn->close();
} else {
    // If no ID is provided, redirect back with an error
    header("Location: Manage_user.php?error=No+user+ID+provided");
}
?>
