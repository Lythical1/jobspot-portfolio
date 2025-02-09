<?php

require_once '../../core/users.php';
$user = new Users();

if (isset($_SESSION['id'])) {
    $id = $_SESSION['id'];
    $user = $user->getUser($id);
    
    if ($user) {
        $name = htmlspecialchars($user['name']);
        $email = htmlspecialchars($user['email']);
    } else {
        echo "<p>User not found.</p>";
        exit;
    }
} else {
    echo "<p>No user ID provided.</p>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    $user->updateUser($id, $name, $email, $password);
    header('Location: /User/Profile');
    exit;
}

?>

<DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>Edit Profile</title>
    </head>
    <body>
        <form method="POST" class="max-w-md mx-auto mt-10 p-6 bg-white shadow-md rounded">
            <div class="mb-4">
                <label for="name" class="block text-gray-700 mb-2">Name</label>
                <input type="text" id="name" name="name" value="<?php echo $name; ?>" class="w-full px-3 py-2 border rounded focus:outline-none focus:border-blue-500">
            </div>
            <div class="mb-4">
                <label for="email" class="block text-gray-700 mb-2">Email</label>
                <input type="email" id="email" name="email" value="<?php echo $email; ?>" class="w-full px-3 py-2 border rounded focus:outline-none focus:border-blue-500">
            </div>
            <div class="mb-4">
                <label for="password" class="block text-gray-700 mb-2">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter new password" class="w-full px-3 py-2 border rounded focus:outline-none focus:border-blue-500">
            </div>
            <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded hover:bg-blue-600">Update Profile</button>
        </form>
    </body>