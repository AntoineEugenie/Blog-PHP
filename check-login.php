<?php
global $con;
include("connectToDB.php");
#connexion a la base de donnees (a voir si on le fait avec un fichier include ou pas)

#verifier si ce user existe deja, si oui on peut passer a homePage.php, sinon on redirect vers la page de login avec un message d'erreur (mdp ou user incorrect)
$stmt = $con->prepare("SELECT Username, Password from users where Username = ?");
$username = $_POST['username'];
$password = $_POST['password'];
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
if ($result->num_rows > 0) {
    if (password_verify($password, $result->fetch_row()[1])) {
        session_start();
        $_SESSION['username'] = $username;
        $_SESSION['password'] = $password;
        header("Location: homePage.php");
        exit();
    }
}