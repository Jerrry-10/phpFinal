<?php
include 'db.php';

$email = $_POST['email'];
$password = $_POST['password'];

$sql = "SELECT * FROM users WHERE email = '$email'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if (password_verify($password, $row['password'])) {
        session_start();
        $_SESSION['userid'] = $row['userid'];
        $_SESSION['username'] = $row['username'];
        header("Location: homepage.php");
    } else {
        echo "Invalid password. <a href='login.php'>Try again</a>";
    }
} else {
    echo "No user found with this email. <a href='signup.php'>Signup here</a>";
}

$conn->close();
?>
