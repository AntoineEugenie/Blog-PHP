<!-- /**
 * Ce fichier est la page d'accueil du blog. Il vérifie si l'utilisateur est connecté,
 * affiche les articles de blog et permet de filtrer les articles par catégorie.
 * 
 * Fonctionnalités principales :
 * - Vérification de la session utilisateur.
 * - Redirection vers la page de connexion si l'utilisateur n'est pas connecté.
 * - Connexion à la base de données.
 * - Filtrage des articles par catégorie.
 * - Affichage des articles de blog.
 * 
 * Variables :
 * - $cookieName : Nom du cookie basé sur le nom d'utilisateur de la session.
 * - $category : Catégorie sélectionnée pour filtrer les articles.
 * - $categoryFilter : Clause SQL pour filtrer les articles par catégorie.
 * - $sql : Requête SQL pour récupérer les articles de blog.
 * - $result : Résultat de la requête SQL.
 * 
 * Sections HTML :
 * - En-tête HTML avec les métadonnées et le lien vers la feuille de style.
 * - Boutons pour filtrer les articles par catégorie.
 * - Boucle pour afficher les articles de blog.
 * - Message si aucun article n'est trouvé.
 * 
 * Note :
 * - Assurez-vous que le fichier 'connectToDB.php' existe et contient les informations de connexion à la base de données.
 * - Les catégories disponibles sont 'lieux', 'jeux' et 'musique'.
 * - Les articles sont affichés par ordre décroissant de date.
 */ -->
<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if ($_SESSION['role'] == "admin") {
    $showAdminButton = true;
}else{
    $showAdminButton = false;
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
    <title>Dis des trucs</title>
</head>
<body>
    <a class="logout" href="logout.php"><button style="background-color: red; color: white; border: none; padding: 10px 20px; cursor: pointer; border-radius: 4px; font-size: 1em;">Déconnexion</button></a>
    <h1>Blog Posts</h1>
    <a href="article.php"><button>Ecrire un article</button></a>
    <div>
        <button class="tous" onclick="window.location.href='index.php'">Tous</button>
        <button class="lieux" onclick="window.location.href='index.php?category=lieux'">Lieux</button>
        <button class="jeux" onclick="window.location.href='index.php?category=jeux'">Jeux</button>
        <button class="musique" onclick="window.location.href='index.php?category=musique'">Musique</button>
    </div>
    <?php
    if ($showAdminButton) {
        echo '<a href="admin.php"><button>Admin Dashboard</button></a>';
    }
    ?>
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