<?php
// getMessages.php
include 'db.php'; // Database connection

// Sanitize and validate sender_id input
$sender_id = isset($_GET['sender_id']) ? intval($_GET['sender_id']) : 0;
$receiver_id = isset($_GET['receiver_id']) ? intval($_GET['receiver_id']) : 0; // Optional: You can pass receiver_id to filter specific chats

if ($sender_id === 0 || $receiver_id === 0) {
    // Handle invalid input
    exit('Invalid sender or receiver.');
}

// Query to fetch messages between the sender and receiver for the specific job
$query = "
    SELECT * 
    FROM messages 
    WHERE (sender_id = ? AND receiver_id = ?) 
       OR (sender_id = ? AND receiver_id = ?) 
    ORDER BY timestamp ASC";
    
$stmt = $conn->prepare($query);
$stmt->bind_param('iiii', $sender_id, $receiver_id, $receiver_id, $sender_id);
$stmt->execute();
$result = $stmt->get_result();

// Output the messages as HTML
while ($message = $result->fetch_assoc()) {
    // If the message was sent by the sender
    if ($message['sender_id'] == $sender_id) {
        echo '<div class="outgoing_msg">'; // For sender's messages
        echo '  <div class="sent_msg">';
        echo '    <p>' . htmlspecialchars($message['content']) . '</p>';
        echo '    <span class="time_date">' . date('h:i A | M d', strtotime($message['timestamp'])) . '</span>';
        echo '  </div>';
        echo '</div>';
    } 
    // If the message was sent by the receiver
    else {
        echo '<div class="incoming_msg">'; // For receiver's messages
        echo '  <div class="incoming_msg_img">';
        echo '    <img src="https://ptetutorials.com/images/user-profile.png" alt="user">';
        echo '  </div>';
        echo '  <div class="received_msg">';
        echo '    <div class="received_withd_msg">';
        echo '      <p>' . htmlspecialchars($message['content']) . '</p>';
        echo '      <span class="time_date">' . date('h:i A | M d', strtotime($message['timestamp'])) . '</span>';
        echo '    </div>';
        echo '  </div>';
        echo '</div>';
    }
}

$stmt->close();
$conn->close();
?>
