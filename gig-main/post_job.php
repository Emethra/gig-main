<?php
include 'db.php'; // Make sure your database connection is here

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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post a Job</title>
    <link rel="stylesheet" href="css/postjob.css">
    <style>
        /* Modal styles */
.modal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1; /* Sit on top */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

.modal-content {
    background-color: #fefefe;
    margin: 15% auto; /* 15% from the top and centered */
    padding: 20px;
    border: 1px solid #888;
    width: 80%; /* Could be more or less, depending on screen size */
    text-align: center;
}

.close-button {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close-button:hover,
.close-button:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}

    </style>
</head>
<body>
<header>
    <div class="logo">
        <img src="img/logo/logo.jpg" alt="GigConnect Logo">
    </div>
    <nav>
        <a href="home.php">Home</a>
        <a href="view_jobs.php">Jobs</a>
        <a href="post_job.php" class="button">Post Job</a>
        <a href="profile.php" class="profile-icon" title="View Profile">
            <img src="<?php echo $profilePic; ?>" alt="P" style="width: 40px; height: 40px; border-radius: 50%;">
        </a>
        <a href="logout.php" class="button">Logout</a>
    </nav>
</header>

<h2>Post a New Job</h2>
<form id="post-job-form" enctype="multipart/form-data">
    <label for="title">Job Title:</label><br>
    <input type="text" id="title" name="title" required><br><br>

    <label for="description">Job Description:</label><br>
    <textarea id="description" name="description" required></textarea><br><br>

    <label for="image">Job Image:</label><br>
    <input type="file" id="image" name="image"><br><br>
    <div id="image-preview"></div>

    <label for="category_id">Category:</label><br>
    <select id="category_id" name="category_id" required>
        <option value="">Select Category</option>
        <?php
        include 'db.php'; // Include your DB connection file
        $result = $conn->query("SELECT id, category_name FROM categories");
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<option value='" . $row['id'] . "'>" . $row['category_name'] . "</option>";
            }
        } else {
            echo "<option value=''>No categories available</option>";
        }
        ?>
    </select><br><br>

    <label for="location">Location:</label><br>
    <input type="text" id="location" name="location" required><br><br>

    <label for="phone">Phone Number:</label><br>
    <input type="tel" id="phone" name="phone" required><br><br>

    <label for="due_date">Due Date:</label><br>
    <input type="date" id="due_date" name="due_date" required><br><br>

    <input type="submit" value="Post Job">
</form>

<!-- Modal for displaying messages -->
<div id="messageModal" class="modal">
    <div class="modal-content">
        <span class="close-button">&times;</span>
        <p id="modalMessage"></p>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // File Preview
        const imageInput = document.getElementById('image');
        const previewContainer = document.getElementById('image-preview');

        imageInput.addEventListener('change', function (event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    previewContainer.innerHTML = `<img src="${e.target.result}" alt="Image Preview" style="max-width: 100%; margin-top: 10px;">`;
                };
                reader.readAsDataURL(file);
            }
        });

        // AJAX Form Submission
        const form = document.getElementById('post-job-form');
        form.addEventListener('submit', function (event) {
            event.preventDefault(); // Prevent default form submission

            const modal = document.getElementById('messageModal');
            const modalMessage = document.getElementById('modalMessage');
            const closeButton = document.querySelector('.close-button');

            // Display processing message in the modal
            modalMessage.textContent = 'Processing...';
            modal.style.display = "block"; // Show the modal

            const formData = new FormData(form); // Create a FormData object

            // Perform AJAX request
            fetch('./auth/job_post.php', { // Point to the backend PHP file
                method: 'POST',
                body: formData,
            })
            .then(response => response.text())
            .then(data => {
                // Set the modal message based on the response
                modalMessage.textContent = data; // Update with server response

                // Reset the form if job posted successfully
                if (data.includes("Job posted successfully")) {
                    form.reset(); // Reset form fields
                    previewContainer.innerHTML = ''; // Clear image preview
                }

                // Close the modal when the close button is clicked
                closeButton.onclick = function() {
                    modal.style.display = "none";
                }

                // Close the modal when clicking anywhere outside of the modal content
                window.onclick = function(event) {
                    if (event.target === modal) {
                        modal.style.display = "none";
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Show error in modal as well
                const errorMessage = 'There was an error posting the job.';
                modalMessage.textContent = errorMessage;
            });
        });
    });
</script>

</body>
</html>
