<?php
    // Inclut le fichier de connexion à la base de données
    require('connectToDB.php');
    
    // Démarre une session
    session_start();
        
    // Récupère le nom d'utilisateur de la session
    $username = $_SESSION['username'];
    
    // Redirige vers la page de connexion si l'utilisateur n'est pas connecté
    if (!isset($username)) {
        header("Location: login.php");
        exit();
    }
    
    // Récupère l'ID du post à partir des paramètres GET
    $postId = $_GET['id'];
    
    // Vérifie si la requête est de type POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Si un commentaire est soumis, appelle la fonction pour poster un commentaire
        if (isset($_POST['comment'])) {
            $comment = $_POST['comment'];
            postComment($comment);
        // Si une demande de suppression de post est soumise, appelle la fonction pour supprimer le post
        } elseif (isset($_POST['delete_post'])) {
            deletePost();
        // Si une demande de suppression de commentaire est soumise, appelle la fonction pour supprimer le commentaire
        } elseif (isset($_POST['delete_comment'])) {
            $commentId = $_POST['comment_id'];
            deleteComment($commentId);
        // Si une demande de modification de post est soumise, appelle la fonction pour modifier le post
        } elseif (isset($_POST['edit_post'])) {
            $newContent = $_POST['new_content'];
            editPost($newContent);
        // Si une demande de modification de commentaire est soumise, appelle la fonction pour modifier le commentaire
        } elseif (isset($_POST['edit_comment'])) {
            $commentId = $_POST['comment_id'];
            $newContent = $_POST['new_content'];
            editComment($commentId, $newContent);
        }
    }

    // Fonction pour afficher le post
    function afficherPost(){
        global $con;
        global $postId;
        global $username;
        
        // Prépare et exécute la requête pour récupérer le post
        $stmt = $con->prepare('SELECT * FROM posts WHERE ID= (?)');
        $stmt->execute([$postId]);
        $result = $stmt->get_result();
        
        // Affiche les détails du post
        while($row = $result->fetch_assoc()) {
            echo("
                <h2> $row[Title] </h2>
                <div class='content'>  $row[Content] </div>
                <div> Rédigé par $row[Author] le  $row[Date] à ...  </div>
            ");
            // Si l'utilisateur est l'auteur du post, affiche des boutons pour modifier et supprimer le post
            if ($row['Author'] === $username) {
                echo("
                    <form method='post' action=''>
                        <input type='hidden' name='delete_post' value='1'>
                        <input type='submit' value='Supprimer l article' style='background-color: red; color: white; border: none; padding: 10px 20px; cursor: pointer; border-radius: 4px; font-size: 1em;'>
                    </form>
                    <button onclick='openEditPostModal(\"$row[Content]\")' style='background-color: blue; color: white; border: none; padding: 10px 20px; cursor: pointer; border-radius: 4px; font-size: 1em;'>Modifier l article</button>
                ");
            }
        }
    }

    // Fonction pour afficher les commentaires
    function afficherComments(){
        global $con;
        global $postId;
        global $username;
        
        // Prépare et exécute la requête pour récupérer les commentaires
        $stmt = $con->prepare('SELECT * FROM comments WHERE ID_Post= (?)');
        $stmt->execute([$postId]);
        $result = $stmt->get_result();
        
        // Affiche les détails des commentaires
        while($row = $result->fetch_assoc()) {
            echo("
            <div class='comment'>
                <h3> Rédigé par $row[Author] </h3>
                <div class='content'>  $row[Content] </div>
            ");
            // Si l'utilisateur est l'auteur du commentaire, affiche des boutons pour modifier et supprimer le commentaire
            if ($row['Author'] === $username) {
                echo("
                    <form method='post' action=''>
                        <input type='hidden' name='delete_comment' value='1'>
                        <input type='hidden' name='comment_id' value='$row[ID]'>
                        <input type='submit' value='Supprimer le commentaire' style='background-color: red; color: white; border: none; padding: 10px 20px; cursor: pointer; border-radius: 4px; font-size: 1em;'>
                    </form>
                    <button onclick='openEditModal($row[ID], \"$row[Content]\")' style='background-color: blue; color: white; border: none; padding: 10px 20px; cursor: pointer; border-radius: 4px; font-size: 1em;'>Modifier le commentaire</button>
                ");
            }
            echo("</div>");
        }
    }

    // Fonction pour poster un commentaire
    function postComment(string $comment){
        global $con;
        global $postId;
        global $username;
        
        // Prépare et exécute la requête pour insérer un nouveau commentaire
        $stmt = $con->prepare('INSERT INTO `comments` (`ID_Post`, `Content`, `Author`) VALUES (?, ?, ?);');
        $stmt->execute([$postId, $comment, $username]);
    }

    // Fonction pour supprimer un post
    function deletePost(){
        global $con;
        global $postId;
        
        // Prépare et exécute la requête pour supprimer le post
        $stmt = $con->prepare('DELETE FROM posts WHERE ID = ?');
        $stmt->execute([$postId]);
        
        // Redirige vers la page d'accueil après suppression
        header("Location: index.php");
        exit();
    }

    // Fonction pour supprimer un commentaire
    function deleteComment($commentId){
        global $con;
        
        // Prépare et exécute la requête pour supprimer le commentaire
        $stmt = $con->prepare('DELETE FROM comments WHERE ID = ?');
        $stmt->execute([$commentId]);
    }

    // Fonction pour modifier un post
    function editPost($newContent){
        global $con;
        global $postId;
        
        // Prépare et exécute la requête pour modifier le post
        $stmt = $con->prepare('UPDATE posts SET Content = ? WHERE ID = ?');
        $stmt->execute([$newContent, $postId]);
    }

    // Fonction pour modifier un commentaire
    function editComment($commentId, $newContent){
        global $con;
        
        // Prépare et exécute la requête pour modifier le commentaire
        $stmt = $con->prepare('UPDATE comments SET Content = ? WHERE ID = ?');
        $stmt->execute([$newContent, $commentId]);
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
        
        // Prépare et exécute la requête pour récupérer le titre du post
        $stmt = $con->prepare('SELECT Title FROM posts WHERE ID= (?)');
        $stmt->execute([$postId]);
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $title = $row['Title'];
    ?>
    <title>Dis des trucs <?php echo $title; ?></title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
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

    <!-- The Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <form method="post" action="">
                <textarea name="new_content" id="modalContent" required></textarea>
                <input type="hidden" name="edit_comment" value="1">
                <input type="hidden" name="comment_id" id="modalCommentId">
                <input type="submit" value="Modifier le commentaire" style="background-color: blue; color: white; border: none; padding: 10px 20px; cursor: pointer; border-radius: 4px; font-size: 1em;">
            </form>
        </div>
    </div>

    <!-- Modal for editing post -->
    <div id="editPostModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <form method="post" action="">
                <textarea name="new_content" id="modalPostContent" required></textarea>
                <input type="hidden" name="edit_post" value="1">
                <input type="submit" value="Modifier l article" style="background-color: blue; color: white; border: none; padding: 10px 20px; cursor: pointer; border-radius: 4px; font-size: 1em;">
            </form>
        </div>
    </div>

    <script>
        // Get the modal
        var modal = document.getElementById("editModal");
        var postModal = document.getElementById("editPostModal");

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close");

        // Function to open the modal for comments
        function openEditModal(commentId, content) {
            document.getElementById("modalCommentId").value = commentId;
            document.getElementById("modalContent").value = content;
            modal.style.display = "block";
        }

        // Function to open the modal for posts
        function openEditPostModal(content) {
            document.getElementById("modalPostContent").value = content;
            postModal.style.display = "block";
        }

        // When the user clicks on <span> (x), close the modal
        for (var i = 0; i < span.length; i++) {
            span[i].onclick = function() {
                modal.style.display = "none";
                postModal.style.display = "none";
            }
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
            if (event.target == postModal) {
                postModal.style.display = "none";
            }
        }
    </script>
</body>
</html>
