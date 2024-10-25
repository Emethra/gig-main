<?php
// Include the database connection file and PHPMailer
include 'db_connection.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../vendor/autoload.php'; // Autoload PHPMailer dependencies

// Function to get category name from category_id
function getCategoryName($category_id, $conn) {
    $category_name = '';
    $stmt = $conn->prepare("SELECT category_name FROM categories WHERE id = ?");
    $stmt->bind_param("i", $category_id);
    $stmt->execute();
    $stmt->bind_result($category_name);
    $stmt->fetch();
    $stmt->close();
    return $category_name;
}

// Function to send the job post email to all registered users
function sendJobPostEmail($jobTitle, $jobDescription, $image, $category_id, $location, $phone, $due_date, $posted_date, $conn) {
    // Fetch the category name using the category ID
    $category_name = getCategoryName($category_id, $conn);

    // Fetch all registered emails from the 'users' table
    $sql = "SELECT email FROM users";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Loop through each user email and send the job post email
        while ($row = $result->fetch_assoc()) {
            $email = $row['email'];

            // Create a new PHPMailer instance
            $mail = new PHPMailer(true);

            try {
                // SMTP configuration
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';            // Use Gmail SMTP server
                $mail->SMTPAuth = true;
                $mail->Username = 'bgavindu.prathap@gmail.com';  // Your Gmail address
                $mail->Password = 'uzau dllg xmln fzet';     // Gmail App password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                // Sender and recipient details
                $mail->setFrom('bgavindu.prathap@gmail.com', 'GigConnect Job Notifications'); // Sender email and name
                $mail->addAddress($email); // Recipient's email

                // Email content with card-style layout
                $mail->isHTML(true);                                  // Set email format to HTML
                $mail->Subject = 'New Job Post: ' . $jobTitle;
                $mail->Body    = '
                <div style="font-family: Arial, sans-serif; color: #333; padding: 20px; background-color: #f4f4f4;">
                    <div style="max-width: 600px; margin: 0 auto; background-color: white; border-radius: 10px; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);">
                        <div style="padding: 20px;">
                            <h2 style="text-align: center; color: #4CAF50;">New Job Posted on GigConnect</h2>
                            <hr style="border: none; border-top: 2px solid #4CAF50; margin-bottom: 20px;">
                            
                            <img src="' . $image . '" alt="Job Image" style="width: 100%; max-height: 250px; object-fit: cover; border-radius: 10px; margin-bottom: 20px;">
                            
                            <p style="font-size: 16px; line-height: 1.6; color: #555;">A new job that might interest you has just been posted. Check out the details below:</p>
                            
                            <div style="background-color: #fafafa; padding: 20px; border-radius: 10px; border: 1px solid #ddd; margin-bottom: 20px;">
                                <h3 style="color: #333;">' . $jobTitle . '</h3>
                                <p><strong>Description:</strong> ' . $jobDescription . '</p>
                                <p><strong>Category:</strong> ' . $category_name . '</p>
                                <p><strong>Location:</strong> ' . $location . '</p>
                                <p><strong>Phone:</strong> ' . $phone . '</p>
                                <p><strong>Due Date:</strong> ' . $due_date . '</p>
                                <p><strong>Posted Date:</strong> ' . $posted_date . '</p>
                            </div>
                            
                            <p style="font-size: 16px; line-height: 1.6;">For more information or to apply for this job, visit <a href="https://gigconnect.com" style="color: #4CAF50; text-decoration: none;">GigConnect</a>.</p>
                            <hr style="border: none; border-top: 1px solid #ddd; margin-top: 20px;">
                            <footer style="text-align: center; font-size: 12px; color: #777;">
                                © 2024 GigConnect, All rights reserved.<br>
                                <a href="https://gigconnect.com" style="color: #4CAF50; text-decoration: none;">Visit our website</a>
                            </footer>
                        </div>
                    </div>
                </div>';

                // Send the email
                $mail->send();
            } catch (Exception $e) {
                // Log the error or take appropriate action
                error_log("Failed to send email to " . $email . ": " . $mail->ErrorInfo);
            }
        }
    }
}

// Include the database connection file
include './db_connection.php';
session_start();

if (!isset($_SESSION['email'])) {
    echo "User is not logged in.";
    exit();
}

$email = $_SESSION['email'];

// Fetch the logged-in user's id
$user_query = $conn->prepare("SELECT id FROM users WHERE email = ?");
$user_query->bind_param("s", $email);
$user_query->execute();
$user_query->bind_result($user_id);
$user_query->fetch();
$user_query->close();

if (!$user_id) {
    echo "User profile not found.";
    exit();
}

// Check if form data is received
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $category_id = $_POST['category_id'];
    $location = $_POST['location'];
    $phone = $_POST['phone'];
    $due_date = $_POST['due_date'];
    $image = '';
    $posted_date = date('Y-m-d H:i:s');

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "../uploads/";
        $target_file = $target_dir . basename($_FILES['image']['name']);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        if (getimagesize($_FILES['image']['tmp_name']) !== false) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                $image = $target_file;
            } else {
                echo "Error uploading the image.";
                exit();
            }
        } else {
            echo "File is not an image.";
            exit();
        }
    }

    // Prepare the SQL insert statement
    $stmt = $conn->prepare("INSERT INTO jobs (user_id, title, description, image, category_id, location, phone, due_date, posted_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssissss", $user_id, $title, $description, $image, $category_id, $location, $phone, $due_date, $posted_date);

    if ($stmt->execute()) {
        // Send email notification after successfully posting the job
        sendJobPostEmail($title, $description, $image, $category_id, $location, $phone, $due_date, $posted_date, $conn);
        echo "Job posted successfully and notifications sent!";
    } else {
        echo "Error posting job: " . $conn->error;
    }

    $stmt->close();
}

$conn->close();
?>
