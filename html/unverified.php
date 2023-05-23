<?php
session_start();
$dbconn = require __DIR__ . "/db.php";
$UID = $_SESSION["user_id"];
$sql = "SELECT * FROM account_tb
WHERE user_id = $UID";
$result = $dbconn->query($sql);
$output = $result->fetch_assoc();
$verification = $output["verification"];

if($verification == 'verified'){
    header("location: login.php");
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://classless.de/classless.css">
    <style> 

#corner {
  border-radius: 25px;
  border: 2px solid cyan;
  padding: 20px; 
  width: 800px;
  height: 300px;
}


</style>
    

</head>
<body>
    <div id="corner">
    <h1>Please check your email for the verification process<br></h1>
<h3>Done with verification? <a href="login.php">click here</a></h3>
</div>
</body>
</html>