<?php

if ($_SESSION['user_role'] == 'user' || !defined('DASHBOARD_ROUTED')) {
    header('Location: /dashboard');
    exit();
}



?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Employer Dashboard</title>
</head>
<body>

<?php include '../../core/navbar.php'; ?>

</body>
</html>
