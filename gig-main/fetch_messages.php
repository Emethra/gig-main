<?php
session_start();
include 'db.php'; // Include your database connection

// Get the message ID from the query string
$message_id = filter_var($_GET['message_id'], FILTER_VALIDATE_INT);

if ($message_id) {
    // Prepare the query to fetch messages
    $query = "SELECT * FROM message_content WHERE message_id = ? ORDER BY timestamp ASC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $message_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $messages = [];
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }

    // Return the messages as JSON
    echo json_encode(['success' => true, 'messages' => $messages]);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid message ID.']);
}
?>
