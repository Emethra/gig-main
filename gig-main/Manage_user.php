<?php
// Include database connection
include 'db.php';
include 'sidebar.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="stylesheet" href="css/users.css">
</head>

<body>

    <div class="container">
        <div class="main-content">
            <h1>Manage Users</h1>

            <!-- Search Bar -->
            <div class="search-bar">
                <input type="text" id="searchInput" placeholder="Search by ID, Name, Email, or Phone">
            </div>
            <div id="searchResults"></div> <!-- Container for search results -->

            <script>
                // Function to perform AJAX search
                document.getElementById('searchInput').addEventListener('input', function () {
                    const input = this.value;

                    // Create a new AJAX request
                    const xhr = new XMLHttpRequest();
                    xhr.open('GET', 'search_user.php?search=' + encodeURIComponent(input), true);

                    xhr.onload = function () {
                        if (xhr.status === 200) {
                            // Update the search results container with the returned data
                            document.getElementById('searchResults').innerHTML = xhr.responseText;
                        }
                    };

                    xhr.send();
                });
            </script>

            <?php
            // Fetch users from the database
            $sql = "SELECT * FROM users";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
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
                echo "<p>No users found</p>";
            }
            ?>
        </div>
    </div>

</body>

</html>
