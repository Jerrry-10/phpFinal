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

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['comment_body'])) {
    $body = $_POST['comment_body'];
    $userid = $_SESSION['userid'];
    if (!insertComment($body, $userid, $postid, $conn)) {
        die("Error inserting comment: " . $conn->error);
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
</head>
<body>
    <h1><?php echo htmlspecialchars($post['title']); ?></h1>
    <p><?php echo htmlspecialchars($post['body']); ?></p>
    <p><small>Posted by <?php echo htmlspecialchars($post['username']); ?> on <?php echo $post['date_created']; ?></small></p>
    <a href="homepage.php">Back to Homepage</a>

    <h2>Comments</h2>
    <form action="" method="post">
        <label for="comment_body">Add a comment:</label><br>
        <textarea id="comment_body" name="comment_body" required></textarea><br><br>
        <input type="submit" value="Post Comment">
    </form>

    <?php if (count($comments) > 0): ?>
        <ul>
            <?php foreach ($comments as $comment): ?>
                <li>
                    <p><?php echo htmlspecialchars($comment['body']); ?></p>
                    <p><small>Commented by <?php echo htmlspecialchars($comment['username']); ?></small></p>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No comments yet. Be the first to comment!</p>
    <?php endif; ?>
</body>
</html>
