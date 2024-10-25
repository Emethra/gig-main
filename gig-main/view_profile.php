<?php
session_start(); 

// Check if the user is logged in
if (isset($_SESSION['contractor_id'])) {
    // If the user is logged in, retrieve session variables
$jobPosterId = $_GET['user_id'];
$loggedInUserId = $_GET['logged_in_user_id'];
$jobId = $_GET['job_id'];

} else {
    // If the user is not logged in, set default values for guests
    $contractor_name = "Guest";
}
// Include your database connection file
include('db.php');

// Check if user_id is set in the URL
if (isset($_GET['user_id'])) {
    $user_id = intval($_GET['user_id']);

    // Fetch the user's profile data from the database
    $sql = "SELECT name, email, phone, profile_picture, degrees, usertype FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch user profile details
        $user = $result->fetch_assoc();
    } else {
        // If user not found, show an error message
        echo "<p>User profile not found.</p>";
        exit();
    }
} else {
    echo "<p>No user selected.</p>";
    exit();
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="css/view_profile.css">
</head>
<body>
    
        <div class="profile-header">
            <img src="<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="Profile Picture" class="profile-img">
            <h2><?php echo htmlspecialchars($user['name']); ?></h2>
        </div>
        <div class="profile-details">
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            <p><strong>Phone:</strong> <?php echo htmlspecialchars($user['phone']); ?></p>
            <p><strong>Category:</strong> <?php echo htmlspecialchars($user['usertype']); ?></p>
            <div class="mb-3">
                <label for="degrees" class="form-label"><strong>Current Qualification:</strong></label>
                <a href="<?php echo htmlspecialchars($user['degrees']); ?>" target="_blank">View Current Qualification (PDF)</a>
            </div>
            <a href="view_jobs.php" class="back-button">Back to Jobs</a> <!-- Button to go back to job listings -->
        </div>
    
    <div class="container">
        <h1>Submit a Review</h1>
        <form action="submit_review.php" method="POST">
    <input type="hidden" name="contractor_id" value="<?php echo htmlspecialchars($loggedInUserId); ?>">
    <input type="hidden" name="customer_id" value="<?php echo htmlspecialchars($jobPosterId); ?>">
    <input type="hidden" name="job_id" value="<?php echo htmlspecialchars($jobId); ?>">
    <div class="form-group">
        <label for="rating">Rating:</label>
        <select id="rating" name="rating" required>
            <option value="1">1 Star</option>
            <option value="2">2 Stars</option>
            <option value="3">3 Stars</option>
            <option value="4">4 Stars</option>
            <option value="5">5 Stars</option>
        </select>
    </div>
    <div class="form-group">
        <label for="review">Review:</label>
        <textarea id="review" name="review" rows="4" required></textarea>
    </div>
    <button type="submit">Submit Review</button>
</form>

    </div>

    <div class="reviews-container">
    <h2 class="profile-header">Reviews & Ratings</h2>
    
    <?php
    
$reviews_query = "SELECT * FROM reviews WHERE customer_id = ? ORDER BY review_date DESC";
$stmt = $conn->prepare($reviews_query);
$stmt->bind_param("i", $jobPosterId); // Assuming customer_id is an integer
$stmt->execute();
$reviews_result = $stmt->get_result();

    if ($reviews_result->num_rows > 0):
        while ($review = $reviews_result->fetch_assoc()):
    ?>
            <div class="review-card">
                <p><strong>Reviewer:</strong> <?php echo htmlspecialchars($review['contractor_id']); ?></p>
                <p><strong>Rating:</strong> <?php echo str_repeat("★", $review['rating']); ?> (<?php echo $review['rating']; ?>/5)</p>
                <p><strong>Review:</strong> <?php echo htmlspecialchars($review['review']); ?></p>
                <p><strong>Date:</strong> <?php echo date("F j, Y", strtotime($review['review_date'])); ?></p>
            </div>
    <?php
        endwhile;
    else:
    ?>
        <p>No reviews yet.</p>
    <?php endif; ?>
</div>
</body>
</html>
