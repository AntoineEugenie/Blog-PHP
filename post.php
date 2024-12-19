<?php
    require('connectToDB.php');
    $postId = $_GET['id'];
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nom = $_POST['comment'];
        $comment = afficherMessage($comment);
    }

function afficherPost(){
    global $con;
    global $postId;
    $stmt = $con->prepare('SELECT * FROM posts WHERE ID= (?)');
    $stmt->execute([$postId]);
    $result = $stmt->get_result();
    while($row = $result->fetch_assoc()) {
        echo("
        <div class='post'>
            <h2> $row[Title] </h2>
            <div class='content'>  $row[Content] </div>
            <div> Rédigé par $row[Author] le  ... à ...  </div>
        </div>");
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

function postComment(){}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="Dis des trucs" content="width=device-width, initial-scale=1.0">
    <title>Dis des trucs</title>
</head>
<body>
    <div>
        <a href="/.."> Accueil </a>  
        <a href="/admin"> Administration</a>
    </div>
        <?php afficherPost(); ?>
    <div class='comments-container'>
        <?php afficherComments() ?>
    
    </div>
    <form action="" method="get" class="form-comments">
        <div class="form-comments">
            <label for="new-comment">Répondre : </label>
            <input type="text" name="comment" id="comment" required />
        <div class="form-comments">
            <input type="submit" value="Envoyez!" />
        </div>
    </form>
</body>
</html>
