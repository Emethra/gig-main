<!-- Modal (hidden by default) -->
<div id="infoModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden">
    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-4xl relative">
        <button id="closeModal" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 text-3xl">&times;</button>

        <!-- Chat and Job Information inside Modal -->
        <h2 class="text-2xl font-bold text-gray-700 mb-4">Chat and Job Information</h2>

        <!-- Merged Chat and Job Details Table -->
        <table class="w-full table-auto border-collapse mb-8">
            <thead>
                <tr class="bg-blue-500 text-white">
                    <th class="px-4 py-2">Field</th>
                    <th class="px-4 py-2">Value</th>
                </tr>
            </thead>
            <tbody>
                <!-- Chat Information -->
                <tr class="bg-gray-100">
                    <td class="border px-4 py-2 font-semibold">Logged-in User Name</td>
                    <td class="border px-4 py-2"><?php echo htmlspecialchars($sender_name); ?></td>
                </tr>
                <tr>
                    <td class="border px-4 py-2 font-semibold">Job Poster Name</td>
                    <td class="border px-4 py-2"><?php echo htmlspecialchars($userDetails['name'] ?? 'Unknown'); ?></td>
                </tr>
                
                <!-- Job Details -->
                <tr>
                    <td class="border px-4 py-2 font-semibold">Title</td>
                    <td class="border px-4 py-2"><?php echo htmlspecialchars($jobDetails['title']); ?></td>
                </tr>
                <tr class="bg-gray-100">
                    <td class="border px-4 py-2 font-semibold">Description</td>
                    <td class="border px-4 py-2"><?php echo htmlspecialchars($jobDetails['description']); ?></td>
                </tr>
                <tr>
                    <td class="border px-4 py-2 font-semibold">Location</td>
                    <td class="border px-4 py-2"><?php echo htmlspecialchars($jobDetails['location']); ?></td>
                </tr>
                <tr class="bg-gray-100">
                    <td class="border px-4 py-2 font-semibold">Image</td>
                    <td class="border px-4 py-2">
                        <img src="<?php echo htmlspecialchars($jobDetails['image']); ?>" alt="Job Image" class="max-w-xs rounded-lg shadow-md">
                    </td>
                </tr>
                <tr>
                    <td class="border px-4 py-2 font-semibold">Due Date</td>
                    <td class="border px-4 py-2"><?php echo htmlspecialchars($jobDetails['due_date']); ?></td>
                </tr>
                <tr class="bg-gray-100">
                    <td class="border px-4 py-2 font-semibold">Posted Date</td>
                    <td class="border px-4 py-2"><?php echo htmlspecialchars($jobDetails['posted_date']); ?></td>
                </tr>
                <tr>
                    <td class="border px-4 py-2 font-semibold">Status</td>
                    <td class="border px-4 py-2"><?php echo htmlspecialchars($jobDetails['status']); ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
    // Open and close modal functionality
    document.getElementById('openModal').addEventListener('click', function() {
        document.getElementById('infoModal').classList.remove('hidden');
    });

    document.getElementById('closeModal').addEventListener('click', function() {
        document.getElementById('infoModal').classList.add('hidden');
    });

    window.addEventListener('click', function(event) {
        if (event.target == document.getElementById('infoModal')) {
            document.getElementById('infoModal').classList.add('hidden');
        }
    });
</script>
