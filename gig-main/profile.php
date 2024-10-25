<?php
// Include database connection (replace with your actual connection details)
include 'db.php';

// Start session to track logged-in user (if you have login functionality)
session_start(); // Start the session to access session variables

// Check if the user is logged in
if (isset($_SESSION['contractor_id'])) {
    // If the user is logged in, retrieve session variables
    $contractor_id = $_SESSION['contractor_id'];
    $contractor_name = $_SESSION['contractor_name'];
    $email = $_SESSION['email'];
} else {
    // If the user is not logged in, set default values for guests
    echo "<script>alert('Please log in to view your profile.!');</script>";
    header("Location: home.php");
}

    $email = $_SESSION['email']; // Get the logged-in user's email from the session

// Fetch user data from the database
$sql = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Store the user data
    $user = $result->fetch_assoc();
    $fullName = $user['name'];
    $phone = $user['phone'];
    $category = $user['usertype'];
    $profilePic = $user['profile_picture'];
    $degrees = $user['degrees'];
    $jobPosterId = $user['id'];
} else {
    echo "No user found.";
    exit();
}

// Check if the form is submitted to update the user profile
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Define variables for storing updated user inputs
    $fullName = $_POST['fullName'];
    $phone = $_POST['phone'];
    $category = $_POST['category'];
    $profilePic = $user['profile_picture']; // Keep existing picture by default
    $degrees = $user['degrees']; // Keep existing degrees by default

    // Handle profile picture upload
    if (isset($_FILES['profilePic']) && $_FILES['profilePic']['error'] == 0) {
        $target_dir = "uploads/profile_pics/"; // Directory for profile pics
        $target_file = $target_dir . basename($_FILES["profilePic"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is a valid image
        $check = getimagesize($_FILES["profilePic"]["tmp_name"]);
        if ($check !== false) {
            if (move_uploaded_file($_FILES["profilePic"]["tmp_name"], $target_file)) {
                $profilePic = $target_file; // Update profile picture path
            } else {
                echo "<script>alert('Sorry, there was an error uploading your file.');</script>";
            }
        } else {
            echo "<script>alert('File is not an image.');</script>";
        }
    }

    // Handle degree upload (PDF)
    if (isset($_FILES['degrees']) && $_FILES['degrees']['error'] == 0) {
        $degrees_dir = "uploads/degrees/"; // Directory for degrees
        $degrees_file = $degrees_dir . basename($_FILES["degrees"]["name"]);
        $degreesFileType = strtolower(pathinfo($degrees_file, PATHINFO_EXTENSION));

        // Check if the file is a PDF
        if ($degreesFileType == "pdf") {
            if (move_uploaded_file($_FILES["degrees"]["tmp_name"], $degrees_file)) {
                $degrees = $degrees_file; // Update degree file path
            } else {
                echo "<script>alert('Sorry, there was an error uploading the degree.');</script>";
            }
        } else {
            echo "<script>alert('File must be a PDF.');</script>";
        }
    }

    // Update user data in the database
    $sql = "UPDATE users SET name=?, phone=?, usertype=?, profile_picture=?, degrees=? WHERE email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $fullName, $phone, $category, $profilePic, $degrees, $email);
    
    if ($stmt->execute()) {
        echo "<script>alert('Profile updated successfully!');</script>";
    } else {
        echo "<script>alert('Error updating profile:');</script>";       
    }

}
// fetch review details
$reviews_query = "SELECT * FROM reviews WHERE customer_id = ? ORDER BY review_date DESC";
$stmt = $conn->prepare($reviews_query);
$stmt->bind_param("i", $jobPosterId); // Assuming customer_id is an integer
$stmt->execute();
$reviews_result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Information Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/profile.css">
</head>
<body>
<header>
    <div class="logo">
     <a href="home.php"><img src="img/logo/logo.jpg" alt="GigConnect Logo"></a>   
    </div>
    <nav>
        <a href="home.php">Home</a>
        <a href="view_jobs.php">Jobs</a>
        <a href="post_job.php" class="button">Post Job</a>
        <a href="profile.php" class="profile-icon" title="View Profile">
        <i class="fas fa-user-circle"></i></a>
        <a href="logout.php" class="button">Logout</a>
    </nav>
</header>

    <div class="profile-container">
        <h2 class="profile-header">Manage Profile Information</h2>
        <form action="" method="POST" enctype="multipart/form-data">
    <div class="text-center">
        <img src="<?php echo isset($profilePic) && $profilePic != '' ? $profilePic : 'default-profile.jpg'; ?>" alt="Profile Picture" class="profile-pic" id="profilePicPreview">
        <div class="file-upload">
            <label for="profilePic" class="btn btn-outline-secondary btn-sm">Change Profile Picture</label>
            <input type="file" name="profilePic" id="profilePic" class="form-control d-none" accept="image/*">
        </div>
    </div>

    <div class="mb-3">
        <label for="fullName" class="form-label">Full Name</label>
        <input type="text" class="form-control" name="fullName" id="fullName" value="<?php echo $fullName; ?>" required>
    </div>

    <div class="mb-3">
        <label for="phone" class="form-label">Phone Number</label>
        <input type="text" class="form-control" name="phone" id="phone" value="<?php echo $phone; ?>" >
    </div>

    <div class="mb-3">
        <label for="email" class="form-label">Email Address</label>
        <input type="email" class="form-control" name="email" id="email" value="<?php echo $email; ?>" readonly>
    </div>

    <div class="mb-3">
        <label for="category" class="form-label">Category</label>
        <select class="form-select" name="category" id="category" required>
            <option value="Customer" <?php echo $category = 'Customer' ? 'selected' : ''; ?>>Customer</option>
            <option value="Plumber" <?php echo $category = 'Plumber' ? 'selected' : ''; ?>>Plumber</option>
            <option value="Masonry" <?php echo $category = 'Masonry' ? 'selected' : ''; ?>>Masonry</option>
            <option value="Electrician" <?php echo $category = 'Electrician' ? 'selected' : ''; ?>>Electrician</option>
            <option value="Cleaner" <?php echo $category = 'Plumber' ? 'selected' : ''; ?>>Cleaner</option>
            <option value="Technician" <?php echo $category = 'Masonry' ? 'selected' : ''; ?>>Technician</option>
            <option value="Devoloper" <?php echo $category = 'Electrician' ? 'selected' : ''; ?>>Devoloper</option>
            <option value="Painter" <?php echo $category = 'Plumber' ? 'selected' : ''; ?>>Painter</option>
            <option value="Builder" <?php echo $category = 'Masonry' ? 'selected' : ''; ?>>Builder</option>
        </select>
    </div>


    <div class="mb-3">
    <label for="manage degrees" class="form-label">Manage Degree</label>
    <p><input type="file" id="degrees" name="degrees" accept=".pdf"></div></p>
        <label for="degrees" class="form-label">Current Degree</label>
        <p><a href="<?php echo $degrees; ?>" target="_blank">View Current Degree (PDF)</a></p>
        
        <div class="text-center">
        <button type="submit" class="btn btn-primary">Save Changes</button>
    </div>
    </div>
</form>

    </div>

    <div class="profile-container">
    
    <a href="job_history.php"><button type="button" class="btn btn-primary">View Job History</button></a>
    <a href="job_details.php?job_id=<?php echo $jobPosterId; ?>"><button type="button" class="btn btn-primary">Job Details</button></a>

</div>


<div class="reviews-container">
    <h3>Reviews</h3>
    <?php if ($reviews_result->num_rows > 0): ?>
        <?php while ($review = $reviews_result->fetch_assoc()): ?>
            <div class="review-card">
                <p><strong>Reviewer (Contractor ID):</strong> <?php echo htmlspecialchars($review['contractor_id']); ?></p>
                <p><strong>Rating:</strong> <?php echo str_repeat("★", $review['rating']); ?> (<?php echo $review['rating']; ?>/5)</p>
                <p><strong>Review:</strong> <?php echo htmlspecialchars($review['review']); ?></p>
                <p><strong>Date:</strong> <?php echo date("F j, Y", strtotime($review['review_date'])); ?></p>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No reviews available.</p>
    <?php endif; ?>
</div>

    <script>
        // Script to handle profile picture preview
        document.getElementById('profilePic').addEventListener('change', function(event) {
            const reader = new FileReader();
            reader.onload = function() {
                document.getElementById('profilePicPreview').src = reader.result;
            }
            reader.readAsDataURL(event.target.files[0]);
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
