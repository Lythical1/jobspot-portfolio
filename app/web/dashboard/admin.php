<?php

if ($_SESSION['user_role'] !== 'admin') {
    header('Location: /dashboard');
    exit();
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
</head>
<body>

<?php include '../../core/navbar.php'; ?>

</body>
</html>