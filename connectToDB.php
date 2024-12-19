<?php
//global $con;
$con = mysqli_connect("localhost", "root", "", "blogdb");

#exemple d'utilisation en dessous

//$stmt = $con->prepare("SELECT * FROM users");
//$stmt->execute();
//$result = $stmt->get_result();
//while($row = $result->fetch_assoc()) {
//    echo $row['Username'] . "<br>";
//}