<?php
require('connectToDB.php');
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $stmt = $con->prepare('DELETE FROM posts WHERE ID= (?)');
    $stmt->execute([$id]);
    $stmt2 = $con->prepare('DELETE FROM comments WHERE ID_Post= (?)');
    $stmt2->execute([$id]);
}
header("Location: admin.php");
?>