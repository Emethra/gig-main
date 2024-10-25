<!-- header.php -->
<?php
session_start();
?>

<header class="h-[5vh] w-full">
    <div class="logo">
        <img src="img/logo/logo.jpg" alt="GigConnect Logo">
    </div>
    <nav>
        <a href="view_jobs.php">Jobs</a>
        <a href="services.php">Services</a>
        <a href="login.php">Login</a>
        <a href="join.php">Join</a>
        <a href="post_job.php" class="button">Post Job</a>
        <?php if (isset($_SESSION['username'])): ?>
            <a href="profile.php" class="profile-icon" title="View Profile">
                <i class="fas fa-user-circle"></i>
            </a>
        <?php endif; ?>
    </nav>
</header>
