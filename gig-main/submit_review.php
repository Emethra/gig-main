<?php
session_start();

// Include your database connection file
include('db.php');

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $contractor_id = intval($_POST['contractor_id']);
    $customer_id = intval($_POST['customer_id']);
    $job_id = intval($_POST['job_id']);
    $rating = intval($_POST['rating']);
    $review = $_POST['review'];

    // Prepare SQL statement to insert the review
    $sql = "INSERT INTO reviews (contractor_id, customer_id, job_id, rating, review) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    // Bind parameters
    $stmt->bind_param("iiiss", $contractor_id, $customer_id, $job_id, $rating, $review);

    // Execute the statement
    if ($stmt->execute()) {
        echo "<p>Review submitted successfully!</p>";
        header("Location: view_jobs.php");
        // Redirect or show success message here
    } else {
        echo "<p>Error submitting review: " . htmlspecialchars($stmt->error) . "</p>";
    }

    // Close the statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>
