<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$cookieName = $_SESSION['username'];
setcookie($cookieName);

include 'connectToDB.php';

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

$category = isset($_GET['category']) ? $_GET['category'] : '';

$categoryFilter = '';
if ($category) {
    $categoryFilter = "WHERE Category = '" . $con->real_escape_string($category) . "'";
}

// Fetch blog posts
$sql = "SELECT Title, Content, Author, Date FROM posts $categoryFilter ORDER BY Date DESC";
$result = $con->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog</title>
</head>
<body>
    <h1>Blog Posts</h1>
    <a href="article.php"><button>Ecrire un article</button></a>
    <div>
        <button onclick="window.location.href='index.php?category=lieux'">Lieux</button>
        <button onclick="window.location.href='index.php?category=jeux'">Jeux</button>
        <button onclick="window.location.href='index.php?category=musique'">Musique</button>
    </div>
    <?php
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<div>";
            echo "<h2>" . $row["Title"] . "</h2>";
            echo "<p>" . $row["Content"] . "</p>";
            echo "<p><em>By " . $row["Author"] . " on " . $row["Date"] . "</em></p>";
            echo "</div>";
        }
    } else {
        echo "<p>No blog posts found.</p>";
    }
    $con->close();
    ?>
</body>
</html>
