<?php
session_start();

if (!isset($_SESSION['userid'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';

function insertPost($title, $body, $userid, $conn) {
    $stmt = $conn->prepare("INSERT INTO post (title, body, userid, date_created) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param('ssi', $title, $body, $userid);
    return $stmt->execute();
}

function getPosts($conn) {
    $sql = "SELECT p.*, u.username FROM post p JOIN users u ON p.userid = u.userid";
    $result = $conn->query($sql);
    if ($result === false) {
        die("Error executing query: " . $conn->error);
    }
    return $result->fetch_all(MYSQLI_ASSOC);
}

function deletePost($postid, $userid, $conn) {
    $stmt = $conn->prepare("DELETE FROM post WHERE postid = ? AND userid = ?");
    $stmt->bind_param('ii', $postid, $userid);
    return $stmt->execute();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['title']) && isset($_POST['body'])) {
    $title = $_POST['title'];
    $body = $_POST['body'];
    $userid = $_SESSION['userid'];
    if (!insertPost($title, $body, $userid, $conn)) {
        die("Error inserting post: " . $conn->error);
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_postid'])) {
    $postid = $_POST['delete_postid'];
    $userid = $_SESSION['userid'];
    if (!deletePost($postid, $userid, $conn)) {
        die("Error deleting post: " . $conn->error);
    }
}

$posts = getPosts($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            min-height: 100vh;
        }

        .container {
            width: 80%;
            max-width: 800px;
            background-color: #fff;
            padding: 20px;
            margin-top: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #333;
            text-align: center;
        }

        a {
            color: #007BFF;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 20px;
        }

        a:hover {
            text-decoration: underline;
        }

        h2 {
            color: #333;
            border-bottom: 2px solid #007BFF;
            padding-bottom: 10px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-top: 10px;
            color: #333;
        }

        input[type="text"],
        textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        input[type="submit"] {
            background-color: #007BFF;
            color: #fff;
            padding: 10px;
            margin-top: 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            background-color: #f9f9f9;
            margin-top: 10px;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        li h3 {
            margin: 0;
            color: #007BFF;
        }

        li p {
            color: #333;
            margin: 10px 0 0 0;
        }

        li small {
            color: #666;
        }

        .delete-btn {
            position: absolute;
            top: 5px;
            right: 5px;
            background-color: #dc3545;
            color: #fff;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .delete-btn:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
        <a href="logout.php">Logout</a>

        <h2>Create a Post</h2>
        <form action="" method="post">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" required>
            <label for="body">Body:</label>
            <textarea id="body" name="body" required></textarea>
            <input type="submit" value="Post">
        </form>

        <h2>All Posts</h2>
        <?php if (count($posts) > 0): ?>
            <ul>
                <?php foreach ($posts as $post): ?>
                    <li>
                        <h3><a href="post.php?postid=<?php echo $post['postid']; ?>"><?php echo htmlspecialchars($post['title']); ?></a></h3>
                        <p><?php echo htmlspecialchars($post['body']); ?></p>
                        <p><small>Posted by <?php echo htmlspecialchars($post['username']); ?> on <?php echo $post['date_created']; ?></small></p>
                        <?php if ($post['userid'] == $_SESSION['userid']): ?>
                            <form action="" method="post">
                                <input type="hidden" name="delete_postid" value="<?php echo $post['postid']; ?>">
                                <button class="delete-btn" type="submit">Delete</button>
                            </form>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No posts available.</p>
        <?php endif; ?>
    </div>
</body>
</html>
