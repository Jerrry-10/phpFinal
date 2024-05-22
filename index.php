<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landing Page</title>
    <style>
        /* General Styles */
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
            text-align: center;
            max-width: 800px;
            margin: 0 auto;
        }

        h1 {
            color: #333;
            margin-bottom: 20px;
        }

        p {
            color: #666;
            margin-bottom: 20px;
        }

        a {
            color: #fff;
            background-color: #007BFF;
            padding: 10px 20px;
            margin: 10px;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        a:hover {
            background-color: #0056b3;
        }

        img {
            max-width: 100%;
            border-radius: 10px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="https://thedigitalphilatelist.com/virtual/discussion-boards/v-ie-01/" alt="Discussion Board Image">
        <h1>Welcome to Our Messaging Board Service</h1>
        <p>Connect with others, share ideas, and engage in meaningful conversations. Sign up now to join the discussion!</p>
        <a href="signup.php">Sign Up</a>
        <a href="login.php">Login</a>
    </div>
</body>
</html>
