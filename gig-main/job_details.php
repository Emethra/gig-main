<?php
// Include database connection
include 'db.php';

// Start session to check if the user is logged in
session_start();

// Check if the user is logged in
if (!isset($_SESSION['contractor_id'])) {
    echo "<script>alert('Please log in to view job details.');</script>";
    header("Location: login.php");
    exit();
}

// Get the job_id from the URL
if (isset($_GET['job_id'])) {
    $job_id = $_GET['job_id'];

    // Fetch quotations for this job_id from the quotation table
    $sql = "SELECT * FROM quotations WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $job_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Store the fetched data in an array
        $quotations = $result->fetch_all(MYSQLI_ASSOC);
    } else {
        echo "<script>alert('No quotations found for this job.');</script>";
        $quotations = [];
    }
} else {
    echo "<script>alert('Invalid job ID.');</script>";
    header("Location: home.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <h2>Job Details</h2>

    <h3>Quotations</h3>

    <?php if (!empty($quotations)): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Contractor ID</th>
                    <th>User ID</th>
                    <th>File Path</th>
                    <th>Sent At</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($quotations as $quote): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($quote['id']); ?></td>
                        <td><?php echo htmlspecialchars($quote['contractor_id']); ?></td>
                        <td><?php echo htmlspecialchars($quote['user_id']); ?></td>
                        <td><a href="<?php echo htmlspecialchars($quote['file_path']); ?>" target="_blank">View File</a></td>
                        <td><?php echo htmlspecialchars($quote['sent_at']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No quotations found for this job.</p>
    <?php endif; ?>
    
    <a href="job_history.php" class="btn btn-secondary">Back to Job History</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
