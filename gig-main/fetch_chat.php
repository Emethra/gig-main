<?php
session_start();
include 'db.php'; // Include your database connection

if (isset($_GET['message_id'])) {
    $message_id = $_GET['message_id'];

    // Prepare and execute the query to fetch messages
    $messagesQuery = "SELECT mc.content, mc.timestamp, u.name AS sender_name, m.sender_id
                      FROM message_content mc
                      JOIN messages m ON mc.message_id = m.id
                      JOIN users u ON m.sender_id = u.id
                      WHERE m.id = ?";
    $stmt = $conn->prepare($messagesQuery);
    $stmt->bind_param('i', $message_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $messages = [];
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row; // Append each message to the array
    }

    // Return messages as JSON
    echo json_encode($messages);
} else {
    echo json_encode(['success' => false, 'message' => 'Message ID not provided.']);
}
?>
