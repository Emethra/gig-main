<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; // Autoload PHPMailer dependencies

// Function to generate a random code of specified length
function generateRandomCode($length = 6) {
    $characters = '0123456789'; // You can add more characters if needed
    $charactersLength = strlen($characters);
    $randomCode = '';

    for ($i = 0; $i < $length; $i++) {
        $randomCode .= $characters[rand(0, $charactersLength - 1)];
    }

    return $randomCode;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the email from the request
    $email = $_POST['email'];

    // Basic email validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid email address']);
        exit();
    }

    // Check if the user is allowed to send a new OTP (2-minute rule)
    // $currentTime = time();
    // if (isset($_SESSION['last_otp_time']) && ($currentTime - $_SESSION['last_otp_time']) < 120) {
    //     $remainingTime = 120 - ($currentTime - $_SESSION['last_otp_time']);
    //     echo json_encode(['status' => 'error', 'message' => 'Please wait ' . $remainingTime . ' seconds before requesting a new OTP']);
    //     exit();
    // }

    // Generate a random OTP
    $otp = generateRandomCode();

    // Store the OTP in the session for validation later
    $_SESSION['otp'] = $otp;

    // Store the current time as the last OTP send time
    // $_SESSION['last_otp_time'] = $currentTime;

    // Send the OTP via email using PHPMailer and Gmail SMTP
    $mail = new PHPMailer(true);
    try {
        // SMTP configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';            // Use Gmail SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'bgavindu.prathap@gmail.com';  // Your Gmail address
        $mail->Password = 'uzau dllg xmln fzet';   // Gmail App password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Sender and recipient details
        $mail->setFrom('bgavindu.prathap@gmail.com', 'GigConnect OTP Service'); // Sender email and name
        $mail->addAddress($email); // Recipient's email

        // Improved Email content with styling
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = 'Your OTP Code for GigConnect';
        $mail->Body    = '
        <div style="font-family: Arial, sans-serif; color: #333;">
            <div style="background-color: #f7f7f7; padding: 20px; border-radius: 10px; max-width: 600px; margin: 0 auto;">
                <h2 style="color: #4CAF50; text-align: center;">GigConnect OTP Verification</h2>
                <p style="font-size: 16px; line-height: 1.6;">
                    Hello,
                </p>
                <p style="font-size: 16px; line-height: 1.6;">
                    To complete your registration, please use the OTP code below:
                </p>
                <div style="text-align: center; padding: 20px;">
                    <span style="font-size: 22px; font-weight: bold; background-color: #f0f0f0; padding: 10px 20px; border-radius: 5px; color: #333;">
                        ' . $otp . '
                    </span>
                </div>
                <p style="font-size: 16px; line-height: 1.6;">
                    If you did not request this, please ignore this email.
                </p>
                <p style="font-size: 16px; line-height: 1.6;">
                    Best regards,<br>
                    The GigConnect Team
                </p>
                <hr style="border: 1px solid #e0e0e0;">
                <footer style="text-align: center; font-size: 12px; color: #777;">
                    © 2024 GigConnect, All rights reserved.<br>
                    <a href="https://gigconnect.com" style="color: #4CAF50;">Visit our website</a>
                </footer>
            </div>
        </div>';

        // Send the email
        if ($mail->send()) {
            echo json_encode(['status' => 'success', 'message' => 'OTP sent successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to send OTP']);
        }
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => "Mailer Error: {$mail->ErrorInfo}"]);
    }
}
?>
