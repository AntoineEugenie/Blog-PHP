<?php
require('connectToDB.php');
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $stmt = $con->prepare('DELETE FROM posts WHERE ID= (?)');
    $stmt->execute([$id]);
    $stmt2 = $con->prepare('DELETE FROM comments WHERE Post_ID= (?)');
    $stmt2->execute([$id]);
}
header("Location: admin.php");
?>