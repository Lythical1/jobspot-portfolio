<?php
session_start();
include_once("../../core/users.php");

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: /user/login");
    exit();
}

$users = new Users();
$user = $users->getUser($_SESSION['user_id']);
$message = '';
$error = '';

// Handle profile picture upload
if (isset($_POST['upload_picture'])) {
    // Check if file was uploaded without errors
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['profile_picture']['name'];
        $filesize = $_FILES['profile_picture']['size'];
        $filetype = $_FILES['profile_picture']['type'];
        $tmp_name = $_FILES['profile_picture']['tmp_name'];
        
        // Validate file extension
        $file_extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        if (!in_array($file_extension, $allowed)) {
            $error = "Error! Only JPG, JPEG, PNG, and GIF files are allowed.";
        } else if ($filesize > 5000000) { // 5MB max
            $error = "Error! File size must be less than 5MB.";
        } else {
            // Create unique filename
            $new_filename = uniqid() . '.' . $file_extension;
            $upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/assets/profiles/';
            
            // Check if upload directory exists, create if it doesn't
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            // Ensure the upload directory has the correct permissions
            if (!is_writable($upload_dir)) {
                chmod($upload_dir, 0755);
            }
            
            $upload_path = $upload_dir . $new_filename;
            
            // Upload file and update database
            if (move_uploaded_file($tmp_name, $upload_path)) {
                try {
                    $users->updateUserProfilePicture($_SESSION['user_id'], $new_filename);
                    $message = "Profile picture updated successfully!";
                    // Refresh user data
                    $user = $users->getUser($_SESSION['user_id']);
                } catch (Exception $e) {
                    $error = "Database error: " . $e->getMessage();
                }
            } else {
                $error = "Error uploading the file. Please check directory permissions.";
            }
        }
    } else {
        $error = "Error! " . $_FILES['profile_picture']['error'];
    }
}

// Handle profile information update
if (isset($_POST['update_profile'])) {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'] ?? '';

    try {
        $users->updateUserInfo($_SESSION['user_id'], $first_name, $last_name, $email, $phone_number);
        $message = "Profile updated successfully!";
        // Refresh user data
        $user = $users->getUser($_SESSION['user_id']);
    } catch (Exception $e) {
        $error = "Error updating profile: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
</head>
<body>
    <?php require_once("../../core/navbar.php"); ?>
    
    <div class="container mx-auto mt-10 p-6 max-w-4xl bg-white rounded-lg shadow-md">
        <h2 class="text-2xl font-bold text-center mb-6">User Profile</h2>
        
        <?php if ($message): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <div class="flex flex-col md:flex-row gap-6">
            <!-- Profile Picture Section -->
            <div class="w-full md:w-1/3">
                <div class="text-center p-4 border rounded-lg">
                    <h3 class="text-lg font-semibold mb-3">Profile Picture</h3>
                    <div class="mb-4">
                        <img 
                            src="/assets/profiles/<?php echo htmlspecialchars($user['profile_picture'] ?? 'default-avatar.png'); ?>" 
                            alt="Profile Picture"
                            class="w-40 h-40 rounded-full mx-auto object-cover border-2 border-gray-200"
                        >
                    </div>
                    
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <input 
                                type="file" 
                                name="profile_picture" 
                                id="profile_picture" 
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                            >
                        </div>
                        <button 
                            type="submit" 
                            name="upload_picture" 
                            class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        >
                            Upload New Picture
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Profile Information Section -->
            <div class="w-full md:w-2/3">
                <div class="p-4 border rounded-lg">
                    <h3 class="text-lg font-semibold mb-3">Personal Information</h3>
                    
                    <form action="" method="POST" class="space-y-4">
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700">First Name:</label>
                            <input 
                                type="text" 
                                id="first_name" 
                                name="first_name" 
                                value="<?php echo htmlspecialchars($user['first_name'] ?? ''); ?>" 
                                required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                            >
                        </div>
                        
                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name:</label>
                            <input 
                                type="text" 
                                id="last_name" 
                                name="last_name" 
                                value="<?php echo htmlspecialchars($user['last_name'] ?? ''); ?>" 
                                required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                            >
                        </div>
                        
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email:</label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" 
                                required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                            >
                        </div>
                        
                        <div>
                            <label for="phone_number" class="block text-sm font-medium text-gray-700">Phone Number:</label>
                            <input 
                                type="tel" 
                                id="phone_number" 
                                name="phone_number" 
                                value="<?php echo htmlspecialchars($user['phone_number'] ?? ''); ?>" 
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                            >
                        </div>
                        
                        <div class="pt-2">
                            <button 
                                type="submit" 
                                name="update_profile" 
                                class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            >
                                Update Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
