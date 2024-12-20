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
$sql = "SELECT id, Title, Content, Author, Date, Category FROM posts $categoryFilter ORDER BY Date DESC";
$result = $con->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Blog</title>
</head>
<body>
    <h1>Blog Posts</h1>
    <a href="article.php"><button>Ecrire un article</button></a>
    <div>
        <button class="tous" onclick="window.location.href='index.php'">Tous</button>
        <button class="lieux" onclick="window.location.href='index.php?category=lieux'">Lieux</button>
        <button class="jeux" onclick="window.location.href='index.php?category=jeux'">Jeux</button>
        <button class="musique" onclick="window.location.href='index.php?category=musique'">Musique</button>
    </div>
    <?php
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $categoryClass = strtolower($row["Category"]);
            echo "<div class='post-preview $categoryClass'>";
            echo "<h2><a href='post.php?id=" . $row["id"] . "'>" . $row["Title"] . "</a></h2>";
            echo "<p>" . $row["Content"] . "</p>";
            echo "<p><em>By " . $row["Author"] . " on " . $row["Date"] . "</em></p>";
            echo "</div>";
        }
    } else {
        echo "<p>No blog posts found.</p>";
        echo $_SESSION['username'];
    }
    $con->close();
    ?>
</body>
</html>
