<?php
require('connectToDB.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = intval($_POST['id']);  
        $title = $_POST['title'];    
        $content = $_POST['content']; 
        $stmt = $con->prepare('UPDATE posts SET Title = ?, Content = ? WHERE id = ?');
        $stmt->execute([$title, $content, $id]);
    }

header("Location: admin.php");