<?php

require_once '../../core/users.php';

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $error = "";

    if (!empty($email) && !empty($password)) {
        $user = new Users();
        $loginResult = $user->LoginUser($email, $password);

        if ($loginResult) {
            session_start();
            $_SESSION['user_id'] = $loginResult['id'];
            $_SESSION['user_email'] = $loginResult['email'];
            header('Location: dashboard');
            exit();
        } else {
            $error = "Invalid email or password";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JobSpot - Login</title>
</head>

<body class="bg-gray-100">
    <?php include '../../core/navbar.php'; ?>
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4 w-full max-w-md">
            <h1 class="block text-gray-700 text-lg font-bold mb-6 text-center">Login</h1>
            <form action="login" method="post" class="space-y-4">
                <div>
                    <input type="email" name="email" placeholder="Email" required
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div>
                    <input type="password" name="password" placeholder="Password" required
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="flex items-center justify-center">
                    <button type="submit" name="submit"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Submit
                    </button>
                </div>
                <?php if (!empty($error)): ?>
                    <div class="text-gray-600 text-sm text-center">
                        <div class="text-red-500"><?= htmlspecialchars($error) ?></div>
                    </div>
                    <div class="text-gray-600 text-sm text-center">
                        <a href="register">Don't have an account? Register here.</a>
                    </div>
                <?php endif; ?>
                <div class="text-gray-600 text-sm text-center">
                    <a href="forgot_password">Forgot your password?</a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>