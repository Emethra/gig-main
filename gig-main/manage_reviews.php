<?php
include 'db.php';


// Delete review
if (isset($_POST['delete'])) {
    $review_id = $_POST['review_id'];
    $delete_query = "DELETE FROM reviews WHERE id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $review_id);
    $stmt->execute();
    $stmt->close();
}

// Retrieve reviews
$sql = "SELECT id, contractor_id, customer_id, job_id, rating, review, review_date FROM reviews";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Reviews</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/reviews.css">
</head>
<body>

<div class="container">
    <div class="review-header">
        <h1>Reviews and Ratings</h1>
        <a href="admin_dashboard.php" class="back-btn">Back to Admin Dashboard</a>
    </div>

    <?php while($row = $result->fetch_assoc()) { ?>
        <div class="review-card">
            <div class="user-info">
                <img src="profile.jpg" alt="User Profile">
                <div class="user-details">
                    <h4><?php echo $row['contractor_id']; ?></h4>
                    <span><?php echo $row['review_date']; ?></span>
                </div>
            </div>
            <div class="rating">
                <?php for ($i = 0; $i < floor($row['rating']); $i++) { ?>
                    <i class="fas fa-star"></i>
                <?php } ?>
                <?php if ($row['rating'] - floor($row['rating']) > 0) { ?>
                    <i class="fas fa-star-half-alt"></i>
                <?php } ?>
            </div>
            <div class="review-text">
                <?php echo $row['review']; ?>
            </div>
            <div class="review-actions">
                
                <form method="post" style="display:inline-block;">
                    <input type="hidden" name="review_id" value="<?php echo $row['id']; ?>">
                    <button type="submit" name="delete" class="delete-btn">Delete</button>
                </form>
            </div>
        </div>
    <?php } ?>

</div>

</body>
</html>
