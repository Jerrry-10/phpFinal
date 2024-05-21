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

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['title']) && isset($_POST['body'])) {
    $title = $_POST['title'];
    $body = $_POST['body'];
    $userid = $_SESSION['userid'];
    if (!insertPost($title, $body, $userid, $conn)) {
        die("Error inserting post: " . $conn->error);
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
</head>
<body>
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
    <a href="logout.php">Logout</a>

    <h2>Create a Post</h2>
    <form action="" method="post">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" required><br><br>
        <label for="body">Body:</label>
        <textarea id="body" name="body" required></textarea><br><br>
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
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No posts available.</p>
    <?php endif; ?>
</body>
</html>
