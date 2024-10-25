<?php
session_start();
include 'sidebar.php';
// Check if the admin is logged in
if (!isset($_SESSION['admin'])) {
    header("Location: login.php"); // Redirect to login if not an admin
    exit();
}

// Log out functionality
if (isset($_POST['logout'])) {
    session_destroy(); // Destroy all session data
    header("Location: login.php"); // Redirect to login page
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
    <div class="container">
        </div>
        <!-- Main content -->
        <div class="main-content">
            <!-- Top bar with Logout button -->
            <div class="top-bar">
                <h2>Admin Dashboard</h2>
                <form method="post">
                    <button class="logout-btn" type="submit" name="logout">Logout</button>
                </form>
            </div>

            <!-- Dashboard content -->
            <div class="dashboard-content">
                <h3>Today's Agenda</h3>
                <div class="task-list">
                    <ul>
                        <li>Team goals discussion</li>
                        <li>Anna's onboarding plan</li>
                        <li>1:1 with Kelly</li>
                    </ul>
                </div>

                <h3>Tomorrow's Agenda</h3>
                <div class="task-list">
                    <ul>
                        <li>1:1 with Ronan</li>
                    </ul>
                </div>

                <div class="calendar">
                    <h3>Calendar</h3>
                    <ul>
                        <li>Marketing Catch-up: 9:00 - 10:00</li>
                        <li><button>Add Event</button></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</body>
</html>