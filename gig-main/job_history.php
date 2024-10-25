<?php
// Include your database connection file
include 'db.php';

// Start the session to get the logged-in user's ID
session_start(); // Start the session to access session variables

// Check if the user is logged in
if (isset($_SESSION['contractor_id'])) {
    // If the user is logged in, retrieve session variables
    $contractor_id = $_SESSION['contractor_id'];
    $contractor_name = $_SESSION['contractor_name'];
    $email = $_SESSION['email'];
} else {
    // If the user is not logged in, prompt them to log in
    echo "Please log in to view your profile.";
    exit();
}

// Get the logged-in user's ID
$user_id = $_SESSION['contractor_id'];

// Query to retrieve the user's job history
$query = "SELECT * FROM jobs WHERE user_id = ? ORDER BY posted_date DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Handle form submission to update job status
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['job_id'])) {
    // Loop through the submitted job statuses and update them in the database
    foreach ($_POST['job_id'] as $job_id) {
        $new_status = $_POST['status'][$job_id];  // Get the new status for each job

        // Prepare the SQL statement to update the job status
        $update_query = "UPDATE jobs SET status = ? WHERE id = ? AND user_id = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("sii", $new_status, $job_id, $user_id);
        
        // Execute the statement
        $update_stmt->execute();
        $update_stmt->close();
    }
    
    // Reload the page to reflect updates
    
    header("Location: job_history.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job History</title>
    <link rel="stylesheet" href="css/history.css"> <!-- Link your stylesheet here -->
</head>
<body>

<div class="container">
    <h1>Your Job History</h1><a href="profile.php"><button type="nav" class="btn btn-primary">Back to profile</button></a>

    <?php if ($result->num_rows > 0): ?>
        <!-- Form to update job statuses -->
        <form action="job_history.php" method="POST">
            <table class="table">
            
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Location</th>
                        <th>Due Date</th>
                        <th>Posted Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['title']); ?></td>
                            <td><?php echo htmlspecialchars($row['description']); ?></td>
                            <td><?php echo htmlspecialchars($row['location']); ?></td>
                            <td><?php echo htmlspecialchars($row['due_date']); ?></td>
                            <td><?php echo htmlspecialchars($row['posted_date']); ?></td>
                            <td>
                                <!-- Dropdown to select status -->
                                <select name="status[<?php echo $row['id']; ?>]">
                                    <option value="active" <?php if ($row['status'] == 'active') echo 'selected'; ?>>Active</option>
                                    <option value="ongoing" <?php if ($row['status'] == 'ongoing') echo 'selected'; ?>>Ongoing</option>
                                    <option value="done" <?php if ($row['status'] == 'done') echo 'selected'; ?>>Done</option>
                                </select>
                            </td>
                            <td>
                                <!-- Hidden field to pass the job ID -->
                                <input type="hidden" name="job_id[]" value="<?php echo $row['id']; ?>">
                                <button type="submit" class="btn btn-primary">Update Status</button>
                                <button type="delete" class="btn">Delete</button>
                            </td>
                            
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <!-- Submit button to update job statuses -->
            
        </form>
    <?php else: ?>
        <p>No jobs found.</p>
    <?php endif; ?>

</div>

</body>
</html>

<?php
// Close the statement and the connection
$stmt->close();
$conn->close();
?>
