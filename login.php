<?php
include 'db.php';
session_start();

$loginError = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['userid'] = $row['userid'];
            $_SESSION['username'] = $row['username'];
            header("Location: homepage.php");
            exit();
        } else {
            $loginError = 'Invalid password. Please try again.';
        }
    } else {
        $loginError = 'No user found with this email. Please signup.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>
    <form action="" method="post">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>
        <input type="submit" value="Login">
    </form>
    <?php
    if ($loginError) {
        echo "<p style='color:red;'>$loginError</p>";
    }
    ?>
    <p>Don't have an account? <a href="signup.php">Signup here</a></p>
</body>
</html>
