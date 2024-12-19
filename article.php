<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Écrire un Article</title>
</head>
<body>
    <h1>Écrire un Nouvel Article</h1>
    <form action="" method="post">
        <label for="title">Titre:</label><br>
        <input type="text" id="title" name="title" required><br><br>
        
        <label for="content">Texte:</label><br>
        <textarea id="content" name="content" rows="10" cols="50" required></textarea><br><br>
       
        <label for="category">Catégorie:</label><br>
        <select id="category" name="category" required>
            <option value="musique">Musique</option>
            <option value="jeux">Jeux</option>
            <option value="lieux">Lieux</option>
        </select><br><br>
        <input type="submit" value="Soumettre">
        <button type="button" onclick="window.location.href='index.php'">Annuler</button>
    </form>

    <?php
    // Inclure le fichier de connexion
    session_start();
    if (!isset($_SESSION['username'])) {
        header("Location: login.php");
        exit();
    }

    include 'connectToDB.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title = htmlspecialchars(trim($_POST['title']));
        $content = htmlspecialchars(trim($_POST['content']));
        $category = htmlspecialchars(trim($_POST['category']));
        $author = $_SESSION["username"]; // Remplacez par une logique d'auteur réelle si nécessaire
        $date = date('Y-m-d H:i:s');

        // Préparation de la requête
        $sql = "INSERT INTO posts (Title, Content, Date, Author, Category) VALUES (?, ?, ?, ?, ?)";
        $stmt = $con->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("sssss", $title, $content, $date, $author, $category);

            if ($stmt->execute()) {
                echo "Nouvel article créé avec succès.";
                header("Location: index.php");
                exit();
            } else {
                echo "Erreur lors de l'insertion : " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Erreur de préparation de la requête : " . $con->error;
        }

        $con->close();
    }
    ?>
</body>
</html>
