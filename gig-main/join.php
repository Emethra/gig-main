<?php include('./includes/header.php');
// Include the database connection file
include './auth/db_connection.php';
include './auth/fun.php';
?>
<?php include('./includes/nav.php'); ?>


<section class="flex justify-center items-center w-full h-full py-8">
    <div class="bg-blue-100 p-8 rounded-xl shadow-xl space-y-8 md:w-2/5 w-full">
        <h2 class="text-2xl font-bold text-center text-blue-500">Join Us</h2>
        <form id="registerForm" class="w-full h-full grid md:grid-cols-2 grid-cols-1 gap-8">
            <!-- Name Field -->
            <div>
                <label for="name" class="block text-blue-400">Name:</label>
                <input type="text" id="name" name="name" class="w-full h-12 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <!-- Username Field -->
            <div>
                <label for="username" class="block text-blue-400">Username:</label>
                <input type="text" id="username" name="username" class="w-full h-12 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <!-- Email Field -->
            <div>
                <label for="email" class="block text-blue-400">Email:</label>
                <input type="email" id="email" name="email" class="w-full h-12 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <!-- OTP Field -->
            <div class="w-full flex flex-col justify-center items-start">
                <label for="otp" class="block text-blue-400">OTP:</label>
                <div class="w-full flex justify-center items-center space-x-4">
                    <button type="button" id="sendOtpBtn" class="text-sm h-12 bg-blue-500 text-white px-4 rounded-lg hover:bg-blue-600 transition">SEND OTP</button>
                    <input type="text" id="otp" name="otp" class="w-full h-12 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            <!-- Phone Number Field -->
            <div>
                <label for="phone" class="block text-blue-400">Phone Number:</label>
                <input type="text" id="phone" name="phone" class="w-full h-12 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <!-- Password Field -->
            <div>
                <label for="password" class="block text-blue-400">Password:</label>
                <input type="password" id="password" name="password" class="w-full h-12 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <!-- Confirm Password Field -->
            <div>
                <label for="confirm_password" class="block text-blue-400">Confirm Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" class="w-full h-12 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <!-- User Type Dropdown -->
            <div>
                <label for="usertype" class="block text-blue-400">User Type:</label>
                <select id="usertype" name="usertype" class="w-full h-12 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="customer">Customer</option>
                    <option value="gig_worker">Gig Worker</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <!-- Links for Already have an account and Forgot password -->
            <div class="text-center col-span-2">
                <p class="text-gray-600">Already have an account? 
                    <a href="login.php" class="text-blue-500 hover:underline">Log in</a>
                </p>
                <p class="text-gray-600">
                    <a href="forgot_password.php" class="text-blue-500 hover:underline">Forgot password?</a>
                </p>
            </div>
            <!-- Submit Button -->
            <div class="col-span-2">
                <button type="submit" class="w-full h-12 bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 transition">Join Now</button>
            </div>
        </form>
    </div>
</section>
<!-- OTP Modal -->
<div id="otpModal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-lg max-w-sm w-full text-center">
        <h2 id="modalTitle" class="text-2xl font-bold text-blue-500"></h2>
        <p id="modalMessage" class="mt-4 text-gray-600"></p>
        <button id="closeModal" class="mt-6 bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 transition">Close</button>
    </div>
</div>

<?php include('./includes/footer.php'); ?>


<script>
$(document).ready(function() {
    // Modal elements
    var otpModal = $('#otpModal');
    var modalTitle = $('#modalTitle');
    var modalMessage = $('#modalMessage');
    var closeModal = $('#closeModal');

    // Close modal function
    closeModal.on('click', function() {
        otpModal.addClass('hidden'); // Hide modal
    });

    // Handle OTP sending button click
    $('#sendOtpBtn').on('click', function() {
        var email = $('#email').val();

        if (email === '') {
            modalTitle.text('Error');
            modalMessage.text('Please enter a valid email');
            otpModal.removeClass('hidden');
            return;
        }

        // Show modal with loading message
        modalTitle.text('Please Wait');
        modalMessage.text('Sending the OTP...');
        otpModal.removeClass('hidden');

        // AJAX call to send the OTP
        $.ajax({
            url: './auth/send_otp.php',
            type: 'POST',
            data: { email: email },
            success: function(response) {
                var data = JSON.parse(response);

                if (data.status === 'success') {
                    modalTitle.text('Success');
                    modalMessage.text('OTP sent successfully to ' + email);
                    $('#otp').val(data.otp);
                } else {
                    modalTitle.text('Error');
                    modalMessage.text('Error: ' + data.message);
                }
            },
            error: function(xhr, status, error) {
                modalTitle.text('Error');
                modalMessage.text('An error occurred: ' + error);
            }
        });
    });

    // Handle form submission (registration)
    $('#registerForm').on('submit', function(e) {
        e.preventDefault(); // Prevent form from submitting the default way

        // Collect form data
        var formData = {
            name: $('#name').val(),
            username: $('#username').val(),
            email: $('#email').val(),
            phone: $('#phone').val(),
            password: $('#password').val(),
            confirm_password: $('#confirm_password').val(),
            usertype: $('#usertype').val(),
            otp: $('#otp').val()
        };

        // Show modal with loading message
        modalTitle.text('Please Wait');
        modalMessage.text('Registering...');
        otpModal.removeClass('hidden');

        // AJAX call to handle registration
        $.ajax({
            url: './auth/register.php',
            type: 'POST',
            data: formData,
            success: function(response) {
                var data = JSON.parse(response);

                if (data.status === 'success') {
                    modalTitle.text('Success');
                    modalMessage.text(data.message);
                } else {
                    modalTitle.text('Error');
                    modalMessage.text('Error: ' + data.message);
                }
            },
            error: function(xhr, status, error) {
                modalTitle.text('Error');
                modalMessage.text('An error occurred: ' + error);
            }
        });
    });
});
</script>
