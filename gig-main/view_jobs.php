<?php 
session_start(); // Start the session to access session variables

// Check if the user is logged in
if (isset($_SESSION['contractor_id'])) {
    // If the user is logged in, retrieve session variables
    $contractor_id = $_SESSION['contractor_id'];
    $contractor_name = $_SESSION['contractor_name'];
    $email = $_SESSION['email'];
} else {
    // If the user is not logged in, set default values for guests
    $contractor_id = "Guest";
}
include 'db.php';

// Fetch the profile picture for the logged-in user
$query = "SELECT profile_picture FROM users WHERE email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $profilePic = $row['profile_picture'];
} else {
    // Default profile picture if none is set
    $profilePic = 'img/default_profile.png';
}

$categories = $conn->query("SELECT id, category_name FROM categories");

// Default category filter (if not set)
$categoryFilter = isset($_GET['category']) ? $_GET['category'] : '';

// Build the query to fetch job postings, including job poster's profile image, and filter by category if selected
$categoryCondition = $categoryFilter ? "WHERE j.category_id = '$categoryFilter'" : '';

$jobsQuery = "SELECT j.*, c.category_name, u.profile_picture 
              FROM jobs j 
              JOIN categories c ON j.category_id = c.id 
              JOIN users u ON j.user_id = u.id 
              $categoryCondition
              ORDER BY j.posted_date DESC";

$jobs = $conn->query($jobsQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Categories and Listings</title>
    <script>
        // JavaScript function to submit the form when a category is clicked
        function filterJobsByCategory() {
            document.getElementById('categoryForm').submit();
        }
    </script>
    <link rel="stylesheet" href="css/viewjobs.css">
</head>
<body>
<header>
    <div class="logo">
        <img src="img/logo/logo.jpg" alt="GigConnect Logo">
    </div>
    <nav>
        <a href="home.php">Home</a>
        <a href="post_job.php" class="button">Post Job</a>
        <a href="profile.php" class="profile-icon" title="View Profile">
            <img src="<?php echo $profilePic; ?>" alt="P" style="width: 40px; height: 40px; border-radius: 50%;">
        </a>

        <a href="logout.php" class="button">Logout</a> <!-- Logout Button -->
    </nav>
</header>
    <div class="container">
        <!-- Left Sidebar for Categories -->
        <div class="sidebar">
            <h2>Categories</h2>
            <form id="categoryForm" action="" method="GET">
                <ul>
                    <li>
                        <label>
                            <!-- Add "All Categories" option -->
                            <input type="radio" name="category" value="" 
                                   <?php echo ($categoryFilter == '') ? 'checked' : ''; ?>
                                   onchange="filterJobsByCategory()">
                            All Categories
                        </label>
                    </li>
                    <?php if ($categories->num_rows > 0): ?>
                        <?php while ($row = $categories->fetch_assoc()): ?>
                            <li>
                                <label>
                                    <input type="radio" name="category" value="<?php echo htmlspecialchars($row['id']); ?>" 
                                           <?php echo ($categoryFilter == $row['id']) ? 'checked' : ''; ?>
                                           onchange="filterJobsByCategory()">
                                    <?php echo htmlspecialchars($row['category_name']); ?>
                                </label>
                            </li>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <li>No categories available</li>
                    <?php endif; ?>
                </ul>
            </form>
        </div>

        <!-- Main Content for Job Listings -->
        <div class="job-listings">
            <?php if ($jobs->num_rows > 0): ?>
                <?php while ($job = $jobs->fetch_assoc()): ?>
                    <div class="job-card">
                        <div class="profile-icon">
                            <!-- Link to view the job poster's profile with their user ID, contractor's ID, and job ID -->
                            <a href="view_profile.php?user_id=<?php echo $job['user_id']; ?>&logged_in_user_id=<?php echo $contractor_id; ?>&job_id=<?php echo $job['id']; ?>">
                                <img src="<?php echo htmlspecialchars($job['profile_picture']); ?>" alt="Profile Picture">
                            </a>
                        </div>

                        <div class="job-details">
                            <h3><?php echo htmlspecialchars($job['title']); ?></h3>
                            <p><strong>Description:</strong> <?php echo htmlspecialchars($job['description']); ?></p>
                            <img src="<?php echo htmlspecialchars($job['image']); ?>" alt="Job Image" class="job-image">
                            <p><strong>Category:</strong> <?php echo htmlspecialchars($job['category_name']); ?></p>
                            <p><strong>Location:</strong> <?php echo htmlspecialchars($job['location']); ?></p>
                            <p><strong>Phone:</strong> <?php echo htmlspecialchars($job['phone']); ?></p>
                            <p><strong>Due Date:</strong> <?php echo htmlspecialchars($job['due_date']); ?></p>
                            <p><strong>Posted Date:</strong> <?php echo htmlspecialchars($job['posted_date']); ?></p>
                            <p><strong>Status:</strong> <?php echo htmlspecialchars($job['status']); ?></p>

                            <div class="job-buttons">
                                <?php if ($contractor_id != "Guest"): ?>
                                    <a href="chat.php?job_id=<?php echo $job['id']; ?>">
                                        <button class="message-btn" style="background-color: #28a745; color: #fff; padding: 10px 15px; text-decoration: none; border-radius: 5px; font-size: 13px; border: none; font-weight: lighter; transition: background-color 0.3s ease;" 
                                        onmouseover="this.style.backgroundColor='#218838'" onmouseout="this.style.backgroundColor='#28a745'">
                                            Message Customer
                                        </button>
                                    </a>
                                <?php else: ?>
                                    <button class="message-btn" disabled style="background-color: gray; color: #fff; padding: 10px 15px; text-decoration: none; border-radius: 5px; font-size: 13px; border: none; font-weight: lighter;">
                                        Log in to Message Customer
                                    </button>
                                <?php endif; ?>
                                <button class="inspect-btn">Inspect Site</button>
                                <button class="quotation-btn" onclick="showUploadForm(<?php echo $job['user_id']; ?>, <?php echo $job['id']; ?>)">Send Quotation</button>

                                <!-- Hidden form for sending a PDF file -->
                                <!-- Hidden form for sending a PDF file -->
<form class="quotation-form" id="quotation-form-<?php echo $job['user_id']; ?>" 
      action="send_quotation.php" method="POST" enctype="multipart/form-data" style="display:none;">
    <input type="hidden" name="job_id" value="<?php echo $job['id']; ?>">
    <input type="hidden" name="user_id" value="<?php echo $job['user_id']; ?>">
    <label for="quotation-file-<?php echo $job['user_id']; ?>">Upload PDF Quotation:</label>
    <input type="file" name="quotation_file" id="quotation-file-<?php echo $job['user_id']; ?>" 
           accept=".pdf" required>
    <button type="submit">Send Quotation</button>
</form>

                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No job postings available.</p>
            <?php endif; ?>
        </div>
    </div>
    <script>
    // Function to display the hidden form for sending a quotation
    function showUploadForm(userId, jobId) {
        // Construct the form ID dynamically based on user and job ID
        var formId = 'quotation-form-' + userId;
        var form = document.getElementById(formId);
        
        // Check if the form is found and toggle its visibility
        if (form) {
            if (form.style.display === 'none') {
                form.style.display = 'block'; // Show the form
            } else {
                form.style.display = 'none'; // Hide the form (toggle behavior)
            }
        }
    }
</script>
</body>
</html>
