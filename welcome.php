<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
</head>
<body>
    <?php if (isset($_SESSION['username'])): ?>
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
        <a href="logout.php">Logout</a>
    <?php else: ?>
        <h2>Welcome to our site!</h2>
        <p>You are not logged in.</p>
        <button onclick="window.location.href='login.php'">Login</button>
    <?php endif; ?>
</body>
</html>
