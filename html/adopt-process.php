<?php 
session_start();





if(isset($_POST["adopt"])){
    include "db.php";
    $post_id = $_POST["adopt_id"];
    $adopted = "yes";
    $adoptedSQL = "UPDATE posts SET adopted='". $adopted ."' WHERE post_id='". $post_id. "'";
    if(mysqli_query($dbconn,$adoptedSQL)){
        header("location: profile.php");
    }
                        
                    
}



?>