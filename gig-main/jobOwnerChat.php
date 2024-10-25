<?php
// Start session if required
session_start();

$loginUserID = isset($_SESSION['contractor_id']) ? $_SESSION['contractor_id'] : null;

include 'db.php';
include './chat/fun.php';

if ($loginUserID) {
    $jobs = getJobsByOwnerId($conn, $loginUserID);
} else {
    echo "User is not logged in.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_message_content'])) {
    $message_id = $_POST['message_id'];
    $new_message_content = $_POST['new_message_content'];
    if (insertMessageContent($conn, $message_id, $new_message_content, $loginUserID)) {
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
    <title>Job Owner Chat</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col items-center justify-start">
    <div class="container mx-auto p-6 bg-white shadow-lg rounded-lg">
        <h2 class="text-2xl font-bold mb-6">My Jobs</h2>

        <!-- Display jobs if available -->
        <?php if (!empty($jobs)): ?>
            <div class="overflow-x-auto">
                <table class="table-auto w-full text-left">
                    <thead>
                        <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                            <th class="py-3 px-6">Job ID</th>
                            <th class="py-3 px-6">Title</th>
                            <th class="py-3 px-6">Description</th>
                            <th class="py-3 px-6">Posted Date</th>
                            <th class="py-3 px-6">Messages</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 text-sm font-light">
                    <?php foreach ($jobs as $job): ?>
                        <tr class="border-b border-gray-200 hover:bg-gray-100">
                            <td class="py-3 px-6"><?php echo htmlspecialchars($job['id']); ?></td>
                            <td class="py-3 px-6"><?php echo htmlspecialchars($job['title']); ?></td>
                            <td class="py-3 px-6"><?php echo htmlspecialchars($job['description']); ?></td>
                            <td class="py-3 px-6"><?php echo htmlspecialchars($job['posted_date']); ?></td>
                            <td class="py-3 px-6">
                                <!-- Fetch and display messages for this job -->
                                <?php
                                $messages = getMessagesByJobId($conn, $job['id']);
                                if ($messages):
                                ?>
                                    <div class="bg-white border border-gray-200 p-4 rounded-lg overflow-y-auto h-64">
                                        <div class="flex flex-col space-y-4">
                                            <?php foreach ($messages as $message): ?>
                                                <?php if ($message['sender_id'] != $message['receiver_id']): ?>
                                                    <div class="flex <?php echo $message['sender_id'] === $loginUserID ? 'justify-end' : 'justify-start'; ?>">
                                                        <div class="bg-<?php echo $message['sender_id'] === $loginUserID ? 'blue' : 'gray'; ?>-500 text-white rounded-lg p-3 max-w-xs">
                                                            <p class="text-sm"><?php echo htmlspecialchars($message['id']); ?></p>
                                                            <span class="text-xs text-gray-200"><?php echo htmlspecialchars($message['timestamp']); ?></span>
                                                        </div>
                                                    </div>

                                                    <!-- Fetch and display message content for this message -->
                                                    <?php
                                                    $messageContent = getMessageContentByMessageId($conn, $message['id']);
                                                    if ($messageContent):
                                                    ?>
                                                        <div class="space-y-2">
                                                            <?php foreach ($messageContent as $content): ?>
                                                                <div class="flex <?php echo ($content['sendBy'] === $loginUserID) ? 'justify-end' : 'justify-start'; ?>">
                                                                    <div class="rounded-lg p-3 max-w-xs text-white <?php echo ($content['sendBy'] === $loginUserID) ? 'bg-blue-500' : 'bg-gray-500'; ?>">
                                                                        <p class="text-sm"><?php echo htmlspecialchars($content['content']); ?></p>
                                                                        <span class="text-xs text-gray-200"><?php echo htmlspecialchars($content['timestamp']); ?></span>
                                                                    </div>
                                                                </div>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    <?php else: ?>
                                                        <p>No content available for this message.</p>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <p>No messages found for this job.</p>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p>You have not posted any jobs yet.</p>
        <?php endif; ?>

        <!-- Form to send new message -->
        <div class="mt-6">
            <form action="" method="POST" class="flex items-center space-x-4">
                <input type="hidden" name="message_id" value="<?php echo $message['id']; ?>"> <!-- Hidden field for message_id -->
                <textarea name="new_message_content" rows="2" class="form-textarea mt-1 block w-full p-2 rounded-lg border border-gray-300 focus:ring focus:ring-blue-200" placeholder="Type your message..." required></textarea>
                <button type="submit" class="bg-blue-500 text-white rounded-lg px-4 py-2 hover:bg-blue-600">Send</button>
            </form>
        </div>
    </div>

    <!-- Link to go back or continue -->
    <div class="mt-4">
        <a href="home.php" class="text-blue-500 hover:underline">Go back</a>
    </div>
</body>
</html>
