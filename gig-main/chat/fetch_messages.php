<?php
session_start();
include '../db.php'; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the sender and receiver IDs from the POST request
    $sender_id = $_POST['sender_id'];

    // Fetch messages between the sender and receiver
    $messagesQuery = "SELECT * FROM message_content 
                      WHERE sender_id = ?";
    $stmt = $conn->prepare($messagesQuery);
    $stmt->bind_param('iiii', $sender_id, $receiver_id, $receiver_id, $sender_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $messages = $result->fetch_all(MYSQLI_ASSOC);

    // Initialize arrays for sender and receiver messages
    $sender_messages = [];
    $receiver_messages = [];

    // Sort messages into separate arrays
    foreach ($messages as $message) {
        if ($message['sender_id'] == $sender_id) {
            $sender_messages[] = $message; // Messages sent by the sender
        } else {
            $receiver_messages[] = $message; // Messages sent by the receiver
        }
    }

    // Return the sorted messages as a JSON object
    echo json_encode([
        'sender_messages' => $sender_messages,
        'receiver_messages' => $receiver_messages
    ]);
}
?>
