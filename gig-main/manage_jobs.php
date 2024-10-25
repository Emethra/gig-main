<?php
// Include the database connection file
include 'db.php';
include 'sidebar.php';
// Fetch jobs from the database for managing
$jobs = $conn->query("SELECT j.*, c.category_name FROM jobs j JOIN categories c ON j.category_id = c.id ORDER BY j.posted_date DESC");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Jobs</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #2c3e50;
        }

        .button-container {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .button-container a {
            background-color: #3498db;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            margin: 0 10px;
            transition: background-color 0.3s ease;
        }

        .button-container a:hover {
            background-color: #2980b9;
        }

        .job-list {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        .job-card {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 350px;
            position: relative;
        }

        .job-card h2,
        .job-card h3,
        .job-card h4 {
            margin: 10px 0;
            color: #2c3e50;
        }

        .job-card h4 {
            color: #7f8c8d;
        }

        .delete-button {
            background-color: #e74c3c;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            position: absolute;
            bottom: 20px;
            right: 20px;
        }

        .delete-button:hover {
            background-color: #c0392b;
        }

        /* Responsive design for smaller screens */
        @media (max-width: 768px) {
            .job-list {
                flex-direction: column;
                align-items: center;
            }

            .job-card {
                width: 90%;
                margin-bottom: 20px;
            }
        }
    </style>
</head>

<body>

    <div class="container">
        <h1>Manage Jobs</h1>

        <!-- Button container for navigation -->
        <div class="button-container">
            <a href="view_jobs.php">View Jobs</a>
        </div>

        <div class="job-list">
            <?php if ($jobs->num_rows > 0): ?>
                <?php while ($job = $jobs->fetch_assoc()): ?>
                    <div class="job-card">
                        <h2><?php echo htmlspecialchars($job['title']); ?></h2>
                        <h3>Category: <?php echo htmlspecialchars($job['category_name']); ?></h3>
                        <h4>Location: <?php echo htmlspecialchars($job['location']); ?></h4>
                        <h4>Description: <?php echo htmlspecialchars($job['description']); ?></h4>
                        

                        <!-- Manage actions like delete job -->
                        <form method="POST" action="delete_job.php">
                            <input type="hidden" name="job_id" value="<?php echo $job['id']; ?>">
                            <button type="submit" class="delete-button" onclick="return confirm('Are you sure you want to delete this job?')">Delete</button>
                        </form>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No jobs available for management.</p>
            <?php endif; ?>
        </div>
    </div>

</body>

</html>

<?php
// Close the database connection
$conn->close();
?>