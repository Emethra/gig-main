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
    $contractor_name = "Guest";
}


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

$sql = "SELECT title, category, location, description, image, date FROM ads";
$result = $conn->query($sql);

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="css/home.css">
    <style>
.footer{
background: #034b98;
text-align: center;
}

.footer .share{
padding: 1rem;
}

.footer .share a{
height: 1rem;
width: 5rem;
line-height: 2rem;
font-size: 2rem;
color: black;
border: black;
border-width: 1px;
margin: 0.5rem;
border-radius: 50%;
}

.footer .share a:hover{
background-color: gold;
}

.footer .links{

display: flex;
justify-content: center;
flex-wrap: wrap;
padding: 1rem 1rem;
gap: 0.5rem;

}

.footer .links a{

padding:  .2rem;
color: #fff;
border: gold;
border-width: 5px;
font-size: 1.5rem;

}

.footer .links a:hover{

background: #063c76;

}

.footer .credit{

font-size: 1.2rem;
color: #fff;
font-weight: lighter;
padding: 1.5rem;

}

.footer .credit span{
color: gold;
}

.about .row{
    display: flex;
    align-items: center;
    background: #007bff;
    flex-wrap: wrap;
}

.about .row .image{

    flex: 1 1 45rem;

}

.about .row .image img{

    width: 100%;
    height: 300%;

}

.about .row .content{

    flex: 1 1 45rem;
    padding: 2rem;

}

.about .row .content h3{

    font-size: 2rem;
    color: #fff;

}

.about .row .content p{

    font-size: 1rem;
    color: #ccc;
    padding: 1rem 0;
    line-height: 1.8;

}
    </style>
</head>
<body>
    <h1>Welcome, <?php echo $contractor_name; ?>!</h1>
    <!-- Add more content or functionality here -->


    <!-- header section starts here -->
    <header>
    <div class="logo">
        <img src="img/logo/logo.jpg" alt="GigConnect Logo">
    </div>
    <nav>
        <a href="view_jobs.php">Jobs</a>
        
        <!-- Check if session is active (user is logged in) -->
        <?php if (isset($_SESSION['email'])): ?>
            <a href="post_job.php" class="button">Post Job</a>
            <a href="profile.php" class="profile-icon" title="View Profile">
                <img src="<?php echo $profilePic; ?>" alt="P" style="width: 40px; height: 40px; border-radius: 50%;">
            </a>
        <?php else: ?>
            <a href="login.php">Login</a> <!-- Login Button -->
        <?php endif; ?>
        <a href="logout.php" class="button">Logout</a> <!-- Logout Button -->
    </nav>
</header>

<!-- hero section starts here -->
<section class="hero">
    <div class="hero-text">
        <h1>Jobs large or small, near or far</h1>
        <p>Find the right people to do your job. Whether you’re looking for jobs or hiring, GigConnect is your solution!</p>
        <div class="buttons">
        <h1 >Purpose;</h1>
            <a href="view_jobs.php">Work</a>
            <a href="post_job.php">Hire</a>
        </div>
    </div>
    <div class="hero-image">
        <img src="img/plumb.jpg" alt="Job work image" id="heroImage">
    </div>
</section>

<!-- features section starts here -->
<section class="features">
    <div>
        <img src="icons/job-offer.gif" alt="Post Job Icon">
        <p>Post your job. It's FREE to post!</p>
    </div>
    <div>
        <img src="icons/review.gif" alt="Review Icon">
        <p>Review offers from trusted providers and view profiles.</p>
    </div>
    <div>
        <img src="icons/job-search.gif" alt="Get Done Icon">
        <p>Select the right person and get the job done.</p>
    </div>
</section>

<!--about section-->
<section class="about" id="about">

<h1 class="heading"> <span>About</span> us</h1>
<div class="row">
    <div class="image">
        <img src="img/1234.png" alt="">
    </div>

    <div class="content">
        <h3>What are we doing?</h3>
        <p>We are a passionate team of experienced individuals dedicated to transforming the gig economy in Sri Lanka. At GigConnect, 
            we believe in creating an innovative platform that connects customers with skilled independent contractors across various fields. Our diverse expertise,
             combined with local talent, enables us to develop groundbreaking solutions that enhance the way services are accessed and delivered.<br><br>

GigConnect is our flagship project, designed to revolutionize how customers find and hire professionals for their needs.
 Whether it’s woodworking, plumbing, electrical work, or any other service, our platform simplifies the process of posting jobs 
 and connecting with qualified gig workers.<br><br>

At GigConnect, we cultivate innovation and bring ideas to life. Our first release aims to solve everyday 
challenges by providing a seamless interface for customers and contractors alike. Stay tuned for more exciting 
developments as we continue to expand our offerings and reshape the future of work in Sri Lanka!</p>

    </div>
</div>
</section>

<!--blog section-->
<section class="blogs" id="blogs">

<h1 class="heading"> <span>Advertisements</span></h1>

<div class="box-container">
<?php if ($result->num_rows > 0): ?>
    <?php while ($ads = $result->fetch_assoc()): ?>

    <div class="box">

        <div class="image">

            <img src="<?php echo $ads['image']; ?>" alt="">

        </div>

        <div class="content">
            <a href="#" class="title"><?php echo $ads['description']; ?></a>
            <span><?php echo $ads['date']; ?></span>
            <p><?php echo $ads['category']; ?></p>
            </div>
        
    </div>
    <?php endwhile; ?>
        <?php else: ?>
            <p>No ads found.</p>
        <?php endif; ?>
</section>

<section class="footer">

        <div class="share">
            <a href="https://www.facebook.com/gavindu.prathap.1" class="fab fa-facebook-f"></a>
            <a href="#" class="fab fa-twitter"></a>
            <a href="https://www.instagram.com/crqzyprqdhqp/" class="fab fa-instagram"></a>
            <a href="https://www.linkedin.com/feed/" class="fab fa-linkedin"></a>
            <a href="https://www.pinterest.com/crqzyprqdhqp/" class="fab fa-pinterest"></a>
        </div>

        <div class="links">
            <a href="#">Home</a>
            <a href="#">About</a>
            <a href="view_jobs.php">Jobs</a>
            <a href="#">Advertisements</a>
            
        </div>

        <div class="credit">created by <span>GigConnect @2024</span> | all rights reserved</div>
    </section>

<script>
    const images = [
        'img/plumb.jpg',
        'img/carpenter.jpg',
        'img/mechanic.jpg',
        'img/mason.jpg'
    ];
    let currentIndex = 0;
    const heroImage = document.getElementById('heroImage');

    function changeImage() {
        currentIndex = (currentIndex + 1) % images.length;
        heroImage.style.opacity = 0;
        setTimeout(() => {
            heroImage.src = images[currentIndex];
            heroImage.style.opacity = 1;
        }, 500);
    }

    setInterval(changeImage, 3000);
</script>

</body>
</html>
