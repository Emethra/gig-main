<?php
// Start session if required
session_start();

// Store the contractor's ID from the session into the loginUserID variable
$loginUserID = isset($_SESSION['contractor_id']) ? $_SESSION['contractor_id'] : null; // Check if contractor_id is set, otherwise assign null

// Include database connection and the function to fetch job data
include 'db.php';
include './chat/fun.php';

// Check if 'job_id' is provided in the URL
if (isset($_GET['job_id'])) {
    $job_id = $_GET['job_id'];

    // Fetch the job data using the function
    $job = getJobById($conn, $job_id);

    if ($job === null) {
        echo "Job not found.";
        exit;
    }

    // Fetch messages using the logged-in user's ID (sender_id)
    if ($loginUserID) {
        $messages = getMessagesBySenderId($conn, $loginUserID);
    } else {
        echo "User is not logged in.";
        exit;
    }
} else {
    echo "No job selected.";
    exit;
}

// Check if the form to send a new message is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_message_content'])) {
    // Get form input
    $newMessageContent = $_POST['new_message_content'];
    $selectedMessageId = $_POST['message_id']; // Get message_id from hidden input

    // Insert the new message content
    if (insertMessageContent($conn, $selectedMessageId, $newMessageContent, $loginUserID)) {
        echo "New message content successfully added!";
    } else {
        echo "Failed to add new message content.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Details and Messages</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">

    <div class="max-w-4xl mx-auto bg-white shadow-md rounded-lg p-6">
        <!-- Job Details -->
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Job Details</h2>

        <table class="min-w-full bg-white border border-gray-200">
            <tbody>
                <tr>
                    <th class="text-left p-2 bg-gray-50 border-b">ID</th>
                    <td class="p-2 border-b"><?php echo htmlspecialchars($job['id']); ?></td>
                </tr>
                <tr>
                    <th class="text-left p-2 bg-gray-50 border-b">Title</th>
                    <td class="p-2 border-b"><?php echo htmlspecialchars($job['title']); ?></td>
                </tr>
                <tr>
                    <th class="text-left p-2 bg-gray-50 border-b">Description</th>
                    <td class="p-2 border-b"><?php echo htmlspecialchars($job['description']); ?></td>
                </tr>
                <tr>
                    <th class="text-left p-2 bg-gray-50 border-b">Image</th>
                    <td class="p-2 border-b">
                        <img src="<?php echo htmlspecialchars($job['image']); ?>" alt="Job Image" class="w-48">
                    </td>
                </tr>
                <tr>
                    <th class="text-left p-2 bg-gray-50 border-b">User ID</th>
                    <td class="p-2 border-b"><?php echo htmlspecialchars($job['user_id']); ?></td>
                </tr>
                <tr>
                    <th class="text-left p-2 bg-gray-50 border-b">Location</th>
                    <td class="p-2 border-b"><?php echo htmlspecialchars($job['location']); ?></td>
                </tr>
                <tr>
                    <th class="text-left p-2 bg-gray-50 border-b">Phone</th>
                    <td class="p-2 border-b"><?php echo htmlspecialchars($job['phone']); ?></td>
                </tr>
                <tr>
                    <th class="text-left p-2 bg-gray-50 border-b">Due Date</th>
                    <td class="p-2 border-b"><?php echo htmlspecialchars($job['due_date']); ?></td>
                </tr>
                <tr>
                    <th class="text-left p-2 bg-gray-50 border-b">Posted Date</th>
                    <td class="p-2 border-b"><?php echo htmlspecialchars($job['posted_date']); ?></td>
                </tr>
                <tr>
                    <th class="text-left p-2 bg-gray-50 border-b">Status</th>
                    <td class="p-2 border-b"><?php echo htmlspecialchars($job['status']); ?></td>
                </tr>
            </tbody>
        </table>
<section class="bg-sky-100">
    <!-- User Messages Section -->
    <div class="h-[500px] overflow-y-scroll rounded-lg p-8 bg-sky-50">
        <?php if (!empty($messages)): ?>
            <?php foreach ($messages as $message): ?>
                <?php
                $messageContent = getMessageContentByMessageId($conn, $message['id']);
                foreach ($messageContent as $content): ?>
                <!-- Check if the sender is the logged-in user -->
                <?php if ($content['sendBy'] == $loginUserID): ?>
                    <!-- Message sent by the user (Right aligned) -->
                    <div class="flex justify-end mb-4">
                        <div class="bg-blue-500 text-white p-3 rounded-2xl max-w-xs shadow-lg">
                            <p class="text-sm"><?php echo htmlspecialchars($content['content']); ?></p>
                            <span class="block text-xs text-right text-gray-200 mt-1"><?php echo htmlspecialchars($content['timestamp']); ?></span>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Message received (Left aligned) -->
                    <div class="flex justify-start mb-4">
                        <div class="bg-gray-300 text-black p-3 rounded-2xl max-w-xs shadow-lg">
                            <p class="text-sm"><?php echo htmlspecialchars($content['content']); ?></p>
                            <span class="block text-xs text-left text-gray-500 mt-1"><?php echo htmlspecialchars($content['timestamp']); ?></span>
                        </div>
                    </div>
                <?php endif; ?>
                <?php endforeach; ?>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-gray-500">No messages to display.</p>
        <?php endif; ?>
    </div>

    <!-- Form to send new message content -->
    <div class="w-full bottom-0 left-0 p-4 shadow-md flex items-center">
        <form action="" method="POST" class="flex w-full items-center space-x-2">
            <select name="message_id" id="message_id" class="hidden">
                <?php foreach ($messages as $message): ?>
                    <option value="<?php echo $message['id']; ?>"><?php echo $message['id']; ?></option>
                <?php endforeach; ?>
            </select>
            <textarea name="new_message_content" id="new_message_content" rows="1" class="flex-1 border border-gray-300 rounded-full px-4 py-2 bg-gray-200 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Write a message..." required></textarea>
            <button type="submit" class="px-4 h-12 w-12 bg-blue-600 text-white rounded-full flex items-center justify-center hover:bg-blue-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M2.5 19.5l15-7.5-15-7.5v5l10 2.5-10 2.5v5z"/>
                </svg>
            </button>
        </form>
    </div>
</section>

        <!-- Link to go back -->
        <a href="home.php" class="block mt-6 text-blue-500 hover:underline">Go back</a>
    </div>

</body>
</html>
