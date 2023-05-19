<?php
session_start();
if (isset($_POST['submit'])) {

    $image_file = $_FILES['update_image'];
    $image_name = $image_file['name'];
    $image_data = ($image_file['tmp_name']);
    $extension = explode('.',$image_name);
    $fileActualExt = strtolower(end($extension));
    $newProfileName = uniqid('', true).".".$fileActualExt;
    $folder='../img/profile-images/';
    
    $image_cover = $_FILES['update_cover'];
    $cover_name = $image_cover['name'];
    $cover_data = ($image_cover['tmp_name']);
    $extensionCover = explode('.',$cover_name);
    $coverActualExt = strtolower(end($extensionCover));
    $newCoverName = uniqid('', true).".".$coverActualExt;
    $coverFolder='../img/cover_images/';


    
    
    $dbconn = require __DIR__ . "/db.php";
    $UID = $_SESSION["user_id"];
    $user_id= "SELECT * FROM account_tb
    WHERE user_id = $UID";

    $result = $dbconn->query($user_id);


    $username = $_POST["update_name"];
    $name = $_POST["update_name"];

 


    

    
    $id = $result->fetch_assoc();

    if(empty($_POST["update_image"])){
        $newProfileName = $id["profile_picture_name"];
    }

    $sql = "UPDATE account_tb set username='" . $_POST["update_username"]. "', email_address='" . $_POST['update_email'] . "', profile_picture_name='" . $newProfileName. "', name='". $name ."', cover_photo='". $newCoverName ."' WHERE user_id='" . $UID . "'";
    $publicSQL = "UPDATE posts set handlebar='" . $_POST["update_username"]. "', public_profile_picture='" . $newProfileName. "', public_name='". $name ."' WHERE user_id='" . $UID . "'";

   if (mysqli_query($dbconn, $sql)){
    mysqli_query($dbconn, $publicSQL);}
    

    $dp = move_uploaded_file($image_data,$folder.$newProfileName);
    $cp = move_uploaded_file($cover_data,$coverFolder.$newCoverName);

    
if($_POST["update_pass"]){
        if (password_verify($_POST["update_pass"],$id["password"])){



            if(($_POST["new_pass"]==$_POST["confirm_pass"])){
                $passwords = password_hash($_POST["new_pass"], PASSWORD_DEFAULT);
                $passwordUpdateSQL =  "UPDATE account_tb set password='" . $passwords. "' WHERE user_id='" . $UID . "'";
                mysqli_query($dbconn,$passwordUpdateSQL);

                header("location: profile.php");
            }else{
                header("location: update_profile.php");
            }
            }else{
                header("location: update_profile.php");
            }

        }



   

   
}