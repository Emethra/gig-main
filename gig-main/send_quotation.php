<?php
session_start();

// Check if user_id and job_id are passed in the URL
if (isset($_GET['user_id']) && isset($_GET['job_id'])) {
    // Store the values in session variables
    $_SESSION['user_id'] = $_GET['user_id'];
    $_SESSION['job_id'] = $_GET['job_id'];
}
    

require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get job ID, user ID, and contractor ID from the form
    $job_id = $_POST['job_id'];
    $user_id = $_POST['user_id'];
    $contractor_id = $_SESSION['contractor_id']; // Assuming contractor ID is stored in the session

    // File upload handling
    $target_dir = "uploads/quotations/";
    $target_file = $target_dir . basename($_FILES["quotation_file"]["name"]);
    $uploadOk = 1;
    $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if the file is a valid PDF
    if ($fileType != "pdf") {
        echo "Sorry, only PDF files are allowed.";
        $uploadOk = 0;
    }

    // Move the uploaded file to the server
    if ($uploadOk && move_uploaded_file($_FILES["quotation_file"]["tmp_name"], $target_file)) {
        // Save the file path and details in the database
        $sql = "INSERT INTO quotations (job_id, contractor_id, user_id, file_path) 
                VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiis", $job_id, $contractor_id, $user_id, $target_file);

        if ($stmt->execute()) {
            echo "Quotation saved successfully!";
            header("Location: view_jobs.php");
        } else {
            echo "Error saving quotation.";
        }

        // Send the email logic here (as shown earlier)
        $to = getJobPosterEmail($user_id);
        $subject = "Job Quotation for Job ID: $job_id";
        $message = "Please find the attached quotation for the job.";
        $headers = "From: contractor@example.com";
        // Email sending code continues...
    } else {
        echo "Error uploading file.";
    }
}

// Function to get job poster email
function getJobPosterEmail($user_id) {
    // Query to fetch email from the database
    global $conn;
    $sql = "SELECT email FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['email'];
    } else {
        return null;
    }
}
?>
