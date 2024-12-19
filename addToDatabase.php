<?php
global $con;
include("connectToDB.php");
#connexion a la base de donnees

#verifier si ce user n'existe pas deja, si oui retour simplement a la page login
$stmt = $con->prepare("SELECT Username, Email, Password from users WHERE Username = ? AND Email = ?");
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];
$stmt->bind_param("ss", $username, $email);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    header("Location: login.php");
    exit();
} else {
    $stmt->close();
    $password = password_hash($password, PASSWORD_DEFAULT);
    $insert_stmt = $con->prepare("INSERT INTO users (Username, Email, Password) VALUES (?,?,?)");
    $insert_stmt->bind_param("sss", $username, $email, $password);
    $insert_stmt->execute();
    $insert_stmt->close();
    header("Location: login.php");
}
$con->close();


#si non ajouter a la base de donnees, ensuite redirect vers la page login