<?php
    require('connectToDB.php');
    session_start();
    $username = $_SESSION['username'];
    if (!isset($username)) {
        header("Location: login.php");
        exit();
    }
    $postId = $_GET['id'];
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $comment = $_POST['comment'];
        postComment($comment);
    }

function afficherPost(){
    global $con;
    global $postId;
    $stmt = $con->prepare('SELECT * FROM posts WHERE ID= (?)');
    $stmt->execute([$postId]);
    $result = $stmt->get_result();
    while($row = $result->fetch_assoc()) {
        echo("
        
            <h2> $row[Title] </h2>
            <div class='content'>  $row[Content] </div>
            <div> Rédigé par $row[Author] le  $row[Date] à ...  </div>
        ");
    }
}
function afficherComments(){
    global $con;
    global $postId;
    
    $stmt = $con->prepare('SELECT * FROM comments WHERE ID_Post= (?)');
    $stmt->execute([$postId]);
    $result = $stmt->get_result();
    while($row = $result->fetch_assoc()) {
        echo("
        <div class='comment'>
            <h3> Rédigé par $row[Author] </h3>
            <div class='content'>  $row[Content] </div>
        </div>");
    }
   
}

function postComment(string $comment){
    global $con;
    global $postId;
    global $username;
    $stmt = $con->prepare('INSERT INTO `comments` (`ID_Post`, `Content`, `Author`) VALUES (?, ?, ?);');
    $stmt->execute([$postId,$comment,$username]);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="Dis des trucs" content="width=device-width, initial-scale=1.0">
    <?php
        global $con;
        global $postId;
        $stmt = $con->prepare('SELECT Title FROM posts WHERE ID= (?)');
        $stmt->execute([$postId]);
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $title = $row['Title'];
    ?>
    <title>Dis des trucs <?php echo $title; ?></title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <a class="logout" href="logout.php"><button style="background-color: red; color: white; border: none; padding: 10px 20px; cursor: pointer; border-radius: 4px; font-size: 1em;">Déconnexion</button></a>
    <div>
        
        <a href="./index.php"> Accueil </a>  
        <a href="./admin.php"> Administration</a>
    </div>
    <div class='post'>
        <?php afficherPost(); ?>
        <?php afficherComments() ?>
    <form action="" method="post" class="form-comments">
        <div class="form-comments">
            <label for="new-comment">Répondre : </label>
            <input type="text" name="comment" id="comment" required />
            <input type="submit" value="Envoyez!" />
        </div>
    </form>
    </div>
</body>
</html>
