<?php
// Include the database connection file
include 'db.php';

// Function to add a category
if (isset($_POST['add_category'])) {
    $category_name = $_POST['category_name'];

    // Check if category_name is not empty
    if (!empty($category_name)) {
        // Prepare and bind the SQL statement
        $stmt = $conn->prepare("INSERT INTO categories (category_name) VALUES (?)");
        $stmt->bind_param("s", $category_name);

        // Execute the statement and check for errors
        if ($stmt->execute()) {
            echo "Category added successfully!";
        } else {
            echo "Error adding category: " . $conn->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Category name cannot be empty!";
    }
}

// Close the database connection
$conn->close();
?>

<!-- HTML Form to add categories -->
<!DOCTYPE html>
<html>
<head>
    <title>Add Category</title>
    <link rel="stylesheet" href="css/category.css">
</head>
<body>
    <form method="POST" action="">
    <h2>Add New Category</h2>
        <label for="category_name">Category Name:</label><br>
        <input type="text" id="category_name" name="category_name"><br><br>
        <input type="submit" name="add_category" value="Add Category">
    </form>
</body>
</html>
