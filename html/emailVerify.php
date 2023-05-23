<?php 
session_start();
require __DIR__ . "/db.php";

$UID = $_SESSION["user_id"];

$verify = "verified";

$authSQL = "SELECT * FROM customer WHERE cid = $UID";
$authQuery = $dbconn -> query($authSQL);
$VerifySQL = "UPDATE account_tb set  verification='" . $verify .  "' WHERE email='" . $UID. "'";

if(mysqli_query($dbconn, $VerifySQL)){
    header("Location: login.php");
}


?>