<?php
require('connectToDB.php');
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $stmt = $con->prepare('DELETE FROM posts WHERE ID= (?)');
    $stmt->execute([$id]);
}
header("Location: admin.php");
?>