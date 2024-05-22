<?php
include 'db.php';

$username = $_POST['username'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

$sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";

if ($conn->query($sql) === TRUE) {
    echo "
    <!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Signup Success</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f4f4;
                margin: 0;
                padding: 0;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                height: 100vh;
            }

            .container {
                background-color: #fff;
                padding: 30px;
                border-radius: 10px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                text-align: center;
            }

            h1 {
                color: #333;
                margin-bottom: 20px;
            }

            p {
                color: #333;
                margin-bottom: 20px;
            }

            a {
                color: #fff;
                background-color: #007BFF;
                padding: 10px 20px;
                text-decoration: none;
                border-radius: 5px;
                transition: background-color 0.3s;
                display: inline-block;
            }

            a:hover {
                background-color: #0056b3;
            }
        </style>
    </head>
    <body>
        <div class='container'>
            <h1>Signup Successful</h1>
            <p>Thank you for signing up, $username.</p>
            <a href='login.php'>Login here</a>
        </div>
    </body>
    </html>";
} else {
    echo "
    <!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Signup Error</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f4f4;
                margin: 0;
                padding: 0;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                height: 100vh;
            }

            .container {
                background-color: #fff;
                padding: 30px;
                border-radius: 10px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                text-align: center;
            }

            h1 {
                color: #333;
                margin-bottom: 20px;
            }

            p {
                color: #333;
                margin-bottom: 20px;
            }

            a {
                color: #fff;
                background-color: #007BFF;
                padding: 10px 20px;
                text-decoration: none;
                border-radius: 5px;
                transition: background-color 0.3s;
                display: inline-block;
            }

            a:hover {
                background-color: #0056b3;
            }
        </style>
    </head>
    <body>
        <div class='container'>
            <h1>Signup Error</h1>
            <p>Error: " . $sql . "<br>" . $conn->error . "</p>
            <a href='signup.php'>Try Again</a>
        </div>
    </body>
    </html>";
}

$conn->close();
?>
