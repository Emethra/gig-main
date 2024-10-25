<?php

// Function to get jobs by the job owner (user_id)
function getJobsByOwnerId($conn, $owner_id) {
    // Prepare the SQL query
    $query = "SELECT * FROM jobs WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $owner_id); // Bind the owner_id parameter as an integer
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    // Check if any rows are found and return the jobs
    if ($result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC); // Fetch all jobs as an associative array
    } else {
        return null; // Return null if no jobs are found
    }
}

// Function to fetch messages by job_id
function getMessagesByJobId($conn, $job_id) {
    // Prepare the SQL query
    $query = "SELECT * FROM messages WHERE job_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $job_id); // Bind the job_id parameter as an integer
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    // Check if any rows are found and return the messages
    if ($result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC); // Fetch all messages as an associative array
    } else {
        return null; // Return null if no messages are found
    }
}


function insertMessageContent($conn, $message_id, $content, $sendBy) {
    // Prepare the SQL query
    $query = "INSERT INTO message_content (message_id, content, sendBy) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("isi", $message_id, $content, $sendBy); // Bind parameters

    // Execute the query and return true if successful
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}
// Function definitions (getJobById, getMessagesBySenderId, getMessageContentByMessageId, insertMessageContent) as described earlier
function getJobById($conn, $job_id) {
    // Prepare the SQL query
    $query = "SELECT * FROM jobs WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $job_id); // Bind the job_id parameter as an integer
    $stmt->execute();
    
    // Get the result
    $result = $stmt->get_result();
    
    // Check if a row is found and return the job data
    if ($result->num_rows > 0) {
        return $result->fetch_assoc(); // Return the job data as an associative array
    } else {
        return null; // Return null if no job is found
    }
}

function getMessagesBySenderId($conn, $sender_id) {
    // Prepare the SQL query to fetch messages by sender_id
    $query = "SELECT * FROM messages WHERE sender_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $sender_id); // Bind the sender_id parameter as an integer
    $stmt->execute();
    
    // Get the result
    $result = $stmt->get_result();
    
    // Check if any rows are found and return the messages
    if ($result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC); // Fetch all messages as an associative array
    } else {
        return null; // Return null if no messages are found
    }
}

function getMessageContentByMessageId($conn, $message_id) {
    // Prepare the SQL query to fetch message content by message_id
    $query = "SELECT * FROM message_content WHERE message_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $message_id); // Bind the message_id parameter as an integer
    $stmt->execute();
    
    // Get the result
    $result = $stmt->get_result();
    
    // Check if any rows are found and return the message content
    if ($result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC); // Fetch all message content as an associative array
    } else {
        return null; // Return null if no message content is found
    }
}

?>