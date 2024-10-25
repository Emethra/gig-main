<?php
  // Start the session
  session_start();
  include 'db.php'; // Include your database connection

  // Get the sender (contractor) and receiver (job poster) IDs from the query string
  $receiver_id = $_GET['user_id'];  // Job poster
  $job_id = $_GET['job_id'];  // Job ID
  $contractor_id = $_SESSION['contractor_id'];
  // Initialize the sender ID and name
  if (isset($_SESSION['contractor_id'])) {
      $sender_id = $_SESSION['contractor_id']; // Contractor (logged-in user)
      $sender_name = $_SESSION['contractor_name']; // Name of the logged-in contractor
  } else {
      $sender_id = 0; // Default sender ID for guests
      $sender_name = "Guest"; // Default name for guests
  }

  // Fetch job details from the database
  $jobQuery = "SELECT * FROM jobs WHERE id = ?";
  $stmt = $conn->prepare($jobQuery);
  $stmt->bind_param('i', $job_id);
  $stmt->execute();
  $jobResult = $stmt->get_result();
  $jobDetails = $jobResult->fetch_assoc();

  // Fetch the receiver's name from the users table
  $userQuery = "SELECT name FROM users WHERE id = ?";
  $stmt = $conn->prepare($userQuery);
  $stmt->bind_param('i', $receiver_id);
  $stmt->execute();

  $userResult = $stmt->get_result(); // This works if the MySQL native driver (mysqlnd) is installed

  if ($userResult && $userResult->num_rows > 0) {
      $userDetails = $userResult->fetch_assoc(); // Fetch the result into an associative array
  } else {
      $userDetails = ['name' => 'Unknown']; // Default value if no user is found
  }

  // Check if a chat entry already exists between these users for the given job, regardless of who is sender/receiver
  $checkQuery = "SELECT * FROM messages WHERE (sender_id = ? AND receiver_id = ? AND job_id = ?) 
                OR (sender_id = ? AND receiver_id = ? AND job_id = ?)";
  $stmt = $conn->prepare($checkQuery);
  $stmt->bind_param('iiiiii', $sender_id, $receiver_id, $job_id, $receiver_id, $sender_id, $job_id);
  $stmt->execute();
  $checkResult = $stmt->get_result();

  // If no existing entry, insert a new one
  if ($checkResult->num_rows == 0) {
      // Insert new chat entry
      $insertQuery = "INSERT INTO messages (sender_id, receiver_id, job_id, timestamp) VALUES (?, ?, ?, NOW())";
      $insertStmt = $conn->prepare($insertQuery);
      $insertStmt->bind_param('iii', $sender_id, $receiver_id, $job_id);
      $insertStmt->execute();
      $message_id = $insertStmt->insert_id; // Get the last inserted message ID
      $insertStmt->close();
  } else {
      // Use the existing message row
      $messageRow = $checkResult->fetch_assoc();
      $message_id = $messageRow['id']; // Get the existing message ID
  }

  // Fetch messages where sender or receiver is involved in the conversation
  $messagesQuery = "SELECT * FROM message_content 
                    WHERE (sender_id = ? AND receiver_id = ?)
                      OR (sender_id = ? AND receiver_id = ?)
                    AND message_id = ?";
  $messagesStmt = $conn->prepare($messagesQuery);
  $messagesStmt->bind_param('iiiii', $sender_id, $receiver_id, $receiver_id, $sender_id, $message_id);
  $messagesStmt->execute();
  $messagesResult = $messagesStmt->get_result();
  $messages = $messagesResult->fetch_all(MYSQLI_ASSOC);

function getMessagesByContractorId($contractor_id) {
    // Access the global $conn variable for the database connection
    global $conn;

    // Prepare the SQL query to get messages where contractor_id is either in sender_id or receiver_id
    $query = "SELECT * FROM messages WHERE sender_id = ? OR receiver_id = ?";

    // Prepare the statement
    $stmt = $conn->prepare($query);

    // Bind the contractor_id for both sender_id and receiver_id
    $stmt->bind_param("ii", $contractor_id, $contractor_id);

    // Execute the query
    $stmt->execute();

    // Fetch the results
    $result = $stmt->get_result();

    // Return the results as an array of rows
    return $result->fetch_all(MYSQLI_ASSOC);
}

?>