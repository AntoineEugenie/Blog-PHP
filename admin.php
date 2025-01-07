<?php
    require('connectToDB.php');
    session_start();

    if ($_SESSION['role'] !== 'admin') {
        header('Location: index.php');
    }
    function afficherPosts(){
        global $con;
        $stmt = $con->prepare('SELECT * FROM posts');
        $stmt->execute();
        $result = $stmt->get_result();
        while($row = $result->fetch_assoc()) {
            echo("
                <tr>
                <form action=\"editPost.php\" method=\"post\">
                    <td><input type=\"hidden\" name=\"id\" value=\"$row[ID]\"> </td>
                    <td><input type=\"text\" name=\"title\" placeholder=\" $row[Title]\" required></td>
                    <td><textarea name=\"content\" placeholder=\"Nouveau contenu\" required>$row[Content]</textarea></td>
                    <td>$row[Author]</td>
                    <td>$row[Category]</td>
                    
                
                    <td>
                    
                        <button type=\"submit\">O</button>
                        </form>
                        <form action=\"deletePost.php\" method=\"post\">
                            <input type=\"hidden\" name=\"id\" value=\"$row[ID]\">
                            <button type=\"submit\">X</button>
                        </form>
                        
                            
                            
                            
                            
                        
                    </td>
                </tr>

            ");
        }
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styleAdmin.css">
    <title>Dis des trucs</title>
</head>
<body>
    <div>
        <a href="./index.php"> Accueil </a> 
    </div>
    
    <table>
    <caption>Liste des posts</caption>
        <tbody>
            <tr>
                <td>Titre</td>
                <td>Article</td>
                <td>Auteur</td>
                <td>Catégorie</td>
            </tr>
            <?php afficherPosts(); ?>
        </tbody>
    </table>
    
</body>
</html>