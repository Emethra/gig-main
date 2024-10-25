<?php
session_start();
include '../db.php'; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the message ID, sender ID, receiver ID, and content from the POST request
    $message_id = $_POST['message_id'];
    $sender_id = $_POST['sender_id']; // Send this from AJAX
    $message_content = $_POST['message_content'];

    // Prepare and execute the insert statement
    $insertQuery = "INSERT INTO message_content (message_id, sender_id, content, timestamp) VALUES (?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param('iiis', $message_id, $sender_id, $receiver_id, $message_content);

    if ($stmt->execute()) {
        // Send back success response
        echo json_encode(['success' => true]);
    } else {
        // Send back error response
        echo json_encode(['success' => false, 'message' => 'Failed to send message.']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
