<?php
// Start output buffering to prevent "headers already sent" errors
ob_start();

if (!defined('DASHBOARD_ROUTED')) {
    header('Location: dashboard');
    exit();
}

include '../../core/users.php';

$users = new Users();
$userInfo = $users->getUser($_SESSION['user_id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include '../../core/fileUpload.php';
    
    try {
        // First verify password before making any changes
        if (!isset($_POST['password']) || !password_verify($_POST['password'], $userInfo['password'])) {
            throw new Exception('Password is incorrect. No changes were made.');
        }
        
        // Process profile picture update
        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
            $fileUpload = new FileUpload('profiles');
            $fileName = $fileUpload->uploadAvatar($_FILES['profile_picture']);
            $users->updateUserProfilePicture($_SESSION['user_id'], $fileName);
        }
        
        // Process user info update
        if (isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']) && isset($_POST['phone_number'])) {
            $users->updateUserInfo($_SESSION['user_id'], $_POST['first_name'], $_POST['last_name'], $_POST['email'], $_POST['phone_number']);
        }
        
        // Process password update if confirm_password is set
        if (isset($_POST['confirm_password'])) {
            if ($_POST['password'] === $_POST['confirm_password']) {
                $users->updateUserPassword($_SESSION['user_id'], $_POST['password']);
            } else {
                throw new Exception('Passwords do not match. User information not updated.');
            }
        }
        
        $_SESSION['success_message'] = 'Profile updated successfully!';
        header('Location: /dashboard/?page=settings');
        exit();
    } catch (Exception $e) {
        $_SESSION['fail_message'] = $e->getMessage();
        header('Location: /dashboard/?page=settings');
        exit();
    }
}


$successMessage = '';
$failMessage = '';

if (isset($_SESSION['success_message'])) {
    $successMessage = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}
if (isset($_SESSION['fail_message'])) {
    $failMessage = $_SESSION['fail_message'];
    unset($_SESSION['fail_message']);
}
?>

<div class="p-6">
    <h2 class="text-2xl font-bold mb-6">Settings</h2>

    <?php if ($successMessage) : ?>
    <div id="success-alert" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
        role="alert">
        <strong class="font-bold">Success!</strong>
        <span class="block sm:inline"> <?= htmlspecialchars($successMessage) ?></span>
        <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
            <svg onclick="document.getElementById('success-alert').remove()"
                class="fill-current h-6 w-6 text-green-500 cursor-pointer" role="button"
                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                <title>Close</title>
                <path
                    d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 
                    1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z" />
            </svg>
        </span>
    </div>
    <?php endif; ?>

    <?php if ($failMessage) : ?>
    <div id="fail-alert" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4"
        role="alert">
        <strong class="font-bold">Error!</strong>
        <span class="block sm:inline"> <?= htmlspecialchars($failMessage) ?></span>
        <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
            <svg onclick="document.getElementById('fail-alert').remove()"
                class="fill-current h-6 w-6 text-red-500 cursor-pointer" role="button"
                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                <title>Close</title>
                <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 
                    1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 
                    0 1.698z" />
            </svg>
        </span>
    </div>
    <?php endif; ?>

    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="font-bold text-lg mb-4">Account Settings</h3>
        <form id="settings-form" class="w-full" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
            <div class="flex flex-col md:flex-row">
                <!-- Profile picture section -->
                <div class="md:w-1/4 flex flex-col items-center mb-6 md:mb-0">
                    <label for="profile-picture" class="block text-gray-700 mb-2 text-center">Profile:</label>
                    <div class="flex flex-col items-center">
                        <div class="w-40 h-40 rounded-full overflow-hidden border-2 border-gray-300 mb-3 cursor-pointer"
                            onclick="document.getElementById('profile-picture').click()">
                            <?php
                                $profilePicture = $userInfo['profile_picture'] ?? 'default-avatar.png';
                            ?>
                            <img src="/assets/profiles/<?= $profilePicture;?>" alt="Profile Picture"
                                class="w-full h-full object-cover" id="profile-preview">
                        </div>
                        <input type="file" id="profile-picture" name="profile_picture" class="hidden"
                            accept="image/jpeg,image/png,image/jpg" onchange="previewImage(this)">
                        <button onclick="document.getElementById('profile-picture').click()" type="button"
                            class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded mt-2">
                            Change Picture
                        </button>
                    </div>
                </div>

                <!-- Account information section -->
                <div class="md:w-3/4 md:pl-6">
                    <div class="flex space-x-4 mb-4">
                        <div class="w-1/2">
                            <label for="first_name" class="block text-gray-700 font-medium mb-1">First Name</label>
                            <input type="text" id="first_name" name="first_name"
                                value="<?= htmlspecialchars($userInfo['first_name'] ?? '') ?>" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 
                                focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                required>
                        </div>
                        <div class="w-1/2">
                            <label for="last_name" class="block text-gray-700 font-medium mb-1">Last Name</label>
                            <input type="text" id="last_name" name="last_name"
                                value="<?= htmlspecialchars($userInfo['last_name'] ?? '') ?>" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 
                                focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                required>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="email" class="block text-gray-700 font-medium mb-1">Email</label>
                        <input type="email" id="email" name="email"
                            value="<?= htmlspecialchars($userInfo['email'] ?? '') ?>" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 
                            focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                            required>
                    </div>
                    <div class="flex space-x-4 mb-4">
                        <div class="w-3/5">
                            <label for="phoneNumber" class="block text-gray-700 font-medium mb-1">Phone Number</label>
                            <input type="tel" id="phoneNumber" name="phone_number"
                                value="<?= htmlspecialchars($userInfo['phone_number'] ?? '') ?>" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 
                                focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                required>
                        </div>
                        <div class="w-3/5" id="password-section">
                            <label for="password" class="block text-gray-700 font-medium mb-1">Password
                                <a onclick="changePassword()" class="text-blue-500 hover:underline">Change Password</a>
                            </label>
                            <input type="password" id="password" name="password" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 
                                focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                placeholder="Enter password" required>
                        </div>
                        <div class="w-3/5" id="change-password-section" style="display:none">
                            <label for="confirm_password" class="block text-gray-700 font-medium mb-1">Confirm
                                Password</label>
                            <input type="password" id="confirm_password" name="confirm_password" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 
                                focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                placeholder="Re-enter password">
                        </div>
                    </div>
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded transition-colors duration-200">Save
                        Changes</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function changePassword() {    
    const changePasswordSection = document.getElementById('change-password-section');
    
    if (changePasswordSection.style.display === 'none') {
        changePasswordSection.style.display = 'block';
        changePasswordSection.getElementsByTagName('input')[0].required = true;
        document.getElementById('password-section').getElementsByTagName('input')[0].placeholder = 'Enter new password';
    } else {
        changePasswordSection.style.display = 'none';
        changePasswordSection.getElementsByTagName('input')[0].required = false;
        document.getElementById('password-section').getElementsByTagName('input')[0].placeholder = 'Enter password';
    }
}

function validateForm() {
    const changePasswordSection = document.getElementById('change-password-section');
    
    // If change password section is visible, perform validation
    if (changePasswordSection.style.display !== 'none') {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirm_password').value;
        let errorMessage = '';
        if (password !== confirmPassword) {
            errorMessage = 'Passwords do not match';
        }
        if (password.length < 8) {
            errorMessage = 'Password must be at least 8 characters long';
        }
        if (!/[A-Z]/.test(password)) {
            errorMessage = 'Password must contain at least one capital letter';
        }
        if (password.length < 8 && !/[A-Z]/.test(password)) {
            errorMessage = 'Password must be at least 8 characters long and contain at least one capital letter';
        }
        
        // If there's an error, display it and prevent submission
        if (errorMessage) {
            let errorMsg = document.getElementById('password-error');
            if (!errorMsg) {
                errorMsg = document.createElement('div');
                errorMsg.id = 'password-error';
                errorMsg.className = 'text-red-500 text-sm mt-1';
                document.getElementById('confirm_password').parentNode.appendChild(errorMsg);
            }
            errorMsg.textContent = errorMessage;
            errorMsg.style.display = 'block';
            return false; // Prevent form submission
        } else {
            // Hide error message if it exists
            const errorMsg = document.getElementById('password-error');
            if (errorMsg) {
                errorMsg.style.display = 'none';
            }
        }
    }
    
    return true; // Allow form submission
}
</script>
