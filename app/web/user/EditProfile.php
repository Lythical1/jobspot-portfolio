<?php

require_once '../../core/users';
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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile</title>
</head>
<body class="bg-gray-100">
    <?php include '../../core/navbar'; ?>
    <div class="max-w-md mx-auto mt-10 bg-white p-8 rounded shadow">
        <h1 class="text-2xl font-bold mb-6 text-center">Edit Profile</h1>
        <form method="post">
            <div class="mb-4">
                <label for="name" class="block text-gray-700">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo $name; ?>" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300">
            </div>
            <div class="mb-4">
                <label for="email" class="block text-gray-700">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo $email; ?>" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300">
            </div>
            <div class="mb-6">
                <label for="password" class="block text-gray-700">Password:</label>
                <input type="password" id="password" name="password" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300">
            </div>
            <div class="text-center">
                <input type="submit" value="Save" class="px-6 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 cursor-pointer">
            </div>
        </form>
    </div>
</body>
</html>