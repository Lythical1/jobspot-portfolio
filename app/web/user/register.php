<?php

include_once("../../core/users.php");

$users = new Users();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    $password_confirm = $_POST['confirm_password'];

    switch (true) {
        case ($password == $password_confirm):
            // Hash the password before storing it
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Create user
            $users->createUser($first_name, $last_name, $email, $hashed_password, $role);
            
            // Redirect to login page after successful registration
            header("Location: /user/login");
            exit();
        
        default:
            // Handle password mismatch
            echo "<script>alert('Passwords do not match. Please try again.');</script>";
            break;
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>

<body>
    <?php require_once("../../core/navbar.php"); ?>
    <div class="container mx-auto mt-10 p-6 max-w-md bg-white rounded-lg shadow-md">
        <h2 class="text-2xl font-bold text-center mb-6">Register</h2>
        <form method="POST" action="" class="space-y-4">
            <div>
                <label for="first_name" class="block text-sm font-medium text-gray-700">First Name:</label>
                <input type="text" id="first_name" name="first_name" required
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div>
                <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name:</label>
                <input type="text" id="last_name" name="last_name" required
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email:</label>
                <input type="email" id="email" name="email" required
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password:</label>
                <input type="password" id="password" name="password" required
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div>
                <label for="confirm_password" class="block text-sm font-medium text-gray-700">Confirm Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" required
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div>
                <label for="role" class="block text-sm font-medium text-gray-700">Role:</label>
                <select id="role" name="role" required
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="user">User</option>
                    <option value="employer">Employer</option>
                </select>
            </div>
            <div>
                <button type="submit"
                    class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Register
                </button>
            </div>
        </form>
        <p class="text-center text-sm text-gray-600 mt-4">
            Already have an account? <a href="/login.php" class="text-indigo-600 hover:underline">Login here</a>
        </p>
    </div>
</body>

</html>
