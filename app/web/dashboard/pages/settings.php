<?php
// Start output buffering to prevent "headers already sent" errors
ob_start();

if (!defined('DASHBOARD_ROUTED')) {
    header('Location: dashboard');
    exit();
}

include '../../core/users.php';

$users = new Users();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include '../../core/fileUpload.php';
    
    $fileUpload = new FileUpload('profiles');

    if (isset($_FILES['profile_picture'])) {
        try {
            $fileName = $fileUpload->uploadAvatar($_FILES['profile_picture']);
            $users->updateUserProfilePicture($_SESSION['user_id'], $fileName);
            header('Location: /dashboard/?page=settings');
            exit();
        } catch (Exception $e) {
            echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
        }
    } else {
        $users->updateUserInfo($_SESSION['user_id'], $_POST['first_name'], $_POST['last_name'], $_POST['email'], $_POST['phone_number']);
        header('Location: /dashboard/?page=settings');
        exit();
    }
}

$userInfo = $users->getUser($_SESSION['user_id']);

?>

<div class="p-6">
    <h2 class="text-2xl font-bold mb-6">Settings</h2>
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="font-bold text-lg mb-4">Account Settings</h3>
        
        <div class="flex flex-col md:flex-row">
            <!-- Profile picture section -->
            <div class="md:w-1/4 flex flex-col items-center mb-6 md:mb-0">
                <form method="POST" enctype="multipart/form-data" id="profile-picture-form">
                    <label for="profile-picture" class="block text-gray-700 mb-2 text-center">Profile:</label>
                    <div class="flex flex-col items-center">
                        <div class="w-24 h-24 rounded-full overflow-hidden border-2 border-gray-300 mb-3 cursor-pointer"
                            onclick="document.getElementById('profile-picture').click()">
                            <?php
                                $profilePicture = $userInfo['profile_picture'] ?? 'default-avatar.png';
                            ?>
                            <img src="/assets/profiles/<?= $profilePicture;?>" alt="Profile Picture"
                                class="w-full h-full object-cover" id="profile-preview">
                        </div>
                    </div>
                    <input type="file" id="profile-picture" name="profile_picture" class="hidden" accept="image/jpeg,image/png,image/jpg"
                        onchange="previewImage(this)">
                    <input type="hidden" name="user_id" value="<?= $_SESSION['user_id'] ?>">
                    <button type="submit" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded mt-2">
                        Update Picture
                    </button>
                </form>
            </div>
            
            <!-- Account information section -->
            <div class="md:w-3/4 md:pl-6">
                <form id="settings-form" class="w-full" method="POST" action="/dashboard/settings">
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
                        <div class="w-3/5">
                            <label for="password" class="block text-gray-700 font-medium mb-1">Password</label>
                            <input type="password" id="password" name="password" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 
                                focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                placeholder="Enter new or previous password">
                        </div>
                    </div>

                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded transition-colors duration-200">Save
                        Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

