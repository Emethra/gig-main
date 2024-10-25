<?php
//include 'sidebar.php';
include 'db.php';

// Fetch all ads from the database
$sql = "SELECT title, category, location, description, image FROM ads";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Ads</title>
    <link rel="stylesheet" href="css/ads.css">
    <style>
.container1 {
    max-width: 600px;
    margin: 50px auto;
    padding: 20px;
    width: 90%; 
    background: #fff;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
}

    h2 {
    color: #333;
    margin-bottom: 20px;
    }

form {
    align-items: center;
    background-color: #fff;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    margin-bottom: 30px;
}

label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
    color: #333;
}

input[type="text"],
textarea,
input[type="file"] {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}

input[type="submit"] {
    background-color: #28a745;
    color: white;
    border: none;
    padding: 10px 15px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
}

input[type="submit"]:hover {
    background-color: #218838;
}
.review-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}
.review-header h1 {
    font-size: 24px;
}
.back-btn {
    background-color: #2986cc;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    text-decoration: none;
    font-size: 14px;
    cursor: pointer;
}
.back-btn:hover {
    background-color: #1d6fa5;
}

    </style>
</head>
<body>
<div class="review-header">
        <h1>Manage Ads</h1>
        <div><a href="admin_dashboard.php" class="back-btn">Back to Admin Dashboard</a></div>
        
    </div>
<div class="container1">
    <h2>Post an Ad</h2>
    <form action="add_ad.php" method="POST" enctype="multipart/form-data">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" required><br><br>

        <label for="category">Category:</label>
        <input type="text" id="category" name="category" required><br><br>

        <label for="location">Location:</label>
        <input type="text" id="location" name="location" required><br><br>

        <label for="description">Description:</label>
        <textarea id="description" name="description" required></textarea><br><br>

        <label for="image">Upload Image:</label>
        <input type="file" id="image" name="image" accept="image/*" required><br><br>

        <input type="submit" value="Submit">
    </form>
</div>

    <div class="container">
        <h2>Manage Ads</h2>
        <!-- Loop through the ads and display them -->
        <?php if ($result->num_rows > 0): ?>
            <?php while ($ads = $result->fetch_assoc()): ?>
                <div class="ad-card">
                    <div class="ad-header">
                        <div>
                            <img src="<?php echo $ads['image']; ?>" alt="Ad Image" class="job-image">
                            <h3 class="ad-title"><?php echo $ads['title']; ?></h3>
                            <p><strong>Category: </strong><?php echo $ads['category']; ?></p>
                            <p><strong>Location: </strong><?php echo $ads['location']; ?></p>
                            <p><strong>Description: </strong><?php echo $ads['description']; ?></p>
                        </div>
                        <div class="ad-actions">
                            <button class="edit-btn">Edit</button>
                            <button class="delete-btn">Delete</button>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No ads found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
