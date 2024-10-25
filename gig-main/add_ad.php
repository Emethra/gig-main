<?php 
include 'db.php';

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $title = $_POST['title'];
    $category = $_POST['category'];
    $location = $_POST['location'];
    $description = $_POST['description'];

    // Handle image upload
    $image = $_FILES['image']['name'];
    $target_dir = "uploads/"; // Directory where images will be saved
    $target_file = $target_dir . basename($image);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if the file is a real image
    $check = getimagesize($_FILES['image']['tmp_name']);
    if ($check === false) {
        die("File is not an image.");
    }

    // Allow only certain file formats (e.g., jpg, png, gif)
    $allowed_types = ['jpg', 'png', 'jpeg', 'gif'];
    if (!in_array($imageFileType, $allowed_types)) {
        die("Sorry, only JPG, JPEG, PNG, & GIF files are allowed.");
    }

    // Move the uploaded file to the server's directory
    if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
        // Prepare and bind the SQL statement to insert form data and image path
        $stmt = $conn->prepare("INSERT INTO ads (title, category, location, description, image) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $title, $category, $location, $description, $target_file);

        // Execute the query
        if ($stmt->execute()) {
            echo "New ad added successfully";
            header("Location: manage_ads.php");
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close the statement and connection
        $stmt->close();
        $conn->close();
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
?>
