<?php
// Include database connection
include 'db.php';

// Get the search term from the query parameter
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';

// Debugging line to check the search term
echo "Search term: " . htmlspecialchars($searchTerm) . "<br>";

// Prepare the SQL statement
$sql = "SELECT * FROM users WHERE name LIKE ? OR email LIKE ? OR phone LIKE ? OR id LIKE ?";
$stmt = $conn->prepare($sql);

// Use wildcard '%' for SQL LIKE search
$searchWildcard = "%" . $searchTerm . "%";
$stmt->bind_param("ssss", $searchWildcard, $searchWildcard, $searchWildcard, $searchWildcard);

// Execute the statement
$stmt->execute();
$result = $stmt->get_result();

// Check if any users were found
if ($result->num_rows > 0) {
    // Loop through and display the results
    while ($row = $result->fetch_assoc()) {
        echo "<div class='user-card'>";
        echo "<img src='" . htmlspecialchars($row['profile_picture']) . "' alt='Profile Photo'>";
        echo "<div>";
        echo "<h3>" . htmlspecialchars($row['name']) . "</h3>";
        echo "<p>Email: " . htmlspecialchars($row['email']) . "</p>";
        echo "<p>Phone: " . htmlspecialchars($row['phone']) . "</p>";
        echo "<p>Category: " . htmlspecialchars($row['usertype']) . "</p>";
        echo "<button class='btn btn-edit' onclick=\"location.href='edit_user.php?id=" . $row['id'] . "'\">Edit</button>";
        echo "<button class='btn btn-delete' onclick=\"if(confirm('Are you sure you want to delete this user?')) location.href='delete_user.php?id=" . $row['id'] . "'\">Delete</button>";
        echo "</div>";
        echo "</div>";
    }
} else {
    echo "<p>No users found matching your search.</p>";
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
