<?php
include("connectToDB.php");
#connexion a la base de donnees

#verifier si ce user n'existe pas deja, si oui retour simplement a la page login
$stmt = $con->prepare("SELECT Username, Email, Password from users WHERE Username = ?, Email = ?, Password = ?");


#si non ajouter a la base de donnees, ensuite redirect vers la page login