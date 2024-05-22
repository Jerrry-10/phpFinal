<?php
session_start();

if (!isset($_SESSION['userid'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';

if (!isset($_GET['postid'])) {
    die("Post ID not specified.");
}

$postid = $_GET['postid'];

function getPost($postid, $conn) {
    $sql = "SELECT p.*, u.username FROM post p JOIN users u ON p.userid = u.userid WHERE p.postid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $postid);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 0) {
        die("Post not found.");
    }
    return $result->fetch_assoc();
}

function insertComment($body, $userid, $postid, $conn) {
    $stmt = $conn->prepare("INSERT INTO comments (userid, body, postid) VALUES (?, ?, ?)");
    $stmt->bind_param('isi', $userid, $body, $postid);
    return $stmt->execute();
}

function getComments($postid, $conn) {
    $sql = "SELECT c.*, u.username FROM comments c JOIN users u ON c.userid = u.userid WHERE c.postid = ? ORDER BY c.id DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $postid);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

function deleteComment($commentid, $userid, $conn) {
    $stmt = $conn->prepare("DELETE FROM comments WHERE id = ? AND userid = ?");
    $stmt->bind_param('ii', $commentid, $userid);
    return $stmt->execute();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['comment_body'])) {
    $body = $_POST['comment_body'];
    $userid = $_SESSION['userid'];
    if (!insertComment($body, $userid, $postid, $conn)) {
        die("Error inserting comment: " . $conn->error);
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_commentid'])) {
    $commentid = $_POST['delete_commentid'];
    $userid = $_SESSION['userid'];
    if (!deleteComment($commentid, $userid, $conn)) {
        die("Error deleting comment: " . $conn->error);
    }
}

$post = getPost($postid, $conn);
$comments = getComments($postid, $conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($post['title']); ?></title>
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

        li p {
            color: #333;
            margin: 10px 0 0 0;
        }

        li small {
            color: #666;
        }

        /* Updated selector for the delete button */
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
    <h1><?php echo htmlspecialchars($post['title']); ?></h1>
    <p><?php echo htmlspecialchars($post['body']); ?></p>
    <p><small>Posted by <?php echo htmlspecialchars($post['username']); ?> on <?php echo $post['date_created']; ?></small></p>
    <a href="homepage.php">Back to Homepage</a>

    <h2>Comments</h2>
    <form action="" method="post">
        <label for="comment_body">Add a comment:</label>
        <textarea id="comment_body" name="comment_body" required></textarea>
        <input type="submit" value="Post Comment">
    </form>

    <?php if (count($comments) > 0): ?>
        <ul>
            <?php foreach ($comments as $comment): ?>
                <li>
                    <p><?php echo htmlspecialchars($comment['body']); ?></p>
                    <p><small>Commented by <?php echo htmlspecialchars($comment['username']); ?></small></p>
                    <?php if ($comment['userid'] == $_SESSION['userid']): ?>
                        <form action="" method="post">
                            <input type="hidden" name="delete_commentid" value="<?php echo $comment['id']; ?>">
                            <button class="delete-btn" type="submit">Delete</button>
                        </form>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No comments yet. Be the first to comment!</p>
    <?php endif; ?>
</div>
</body>
</html>
