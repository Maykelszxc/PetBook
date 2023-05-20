<?php

session_start();

if (isset($_SESSION["user_id"])) {
    
    
    $dbconn = require __DIR__ . "/db.php";

    $UID = $_SESSION["user_id"];
    
    $sql = "SELECT * FROM account_tb
            WHERE user_id = $UID";
            
    $result = $dbconn->query($sql);
    
    $user = $result->fetch_assoc();
    
    $dp = $user["profile_picture_name"];
    $name = $user["name"];

};

if(! isset($_SESSION["user_id"])){
    header("location: login.php");
    exit;
}

$posts_sql = "SELECT * FROM posts WHERE user_id=$UID ORDER BY date_created DESC";
            
$posts_result = $dbconn->query($posts_sql);

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <link rel="shortcut icon" type="x-icon" href="Logo1.png">
        <link rel="stylesheet" href="../css/profile.css">
        <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

        <title>Profile page</title>
    </head>

    <body>
        
        <nav>
            <div class="container">
                <a href="index.php">
                    <h2 class="logo">
                        PetBook
                    </h2>
                </a>
                
    
                <div class="search-box">
                    <i class="uil uil-search" ></i>
                    <input type="search" placeholder="Search">
                </div>
    
                <div class="create">
                    <label class="btn btn-primary" for="create-post">Create</label>
    
                    <div class="profile-picture">
                        <img src="../img/profile-images/<?=$dp?>">
                    </div>
    
                </div>
            </div>
        </nav>

<!-------nav bar close ------->
<div class="container">

    <div class="profile-main">

        <div class="profile-container">
            <img src="../img/cover_images/<?=$user["cover_photo"]?>" width="400" height="500">

            <div class="profile-container-inner">
                <img src="../img/profile-images/<?=($user["profile_picture_name"])?>" class="profile-pic">

                <h1><?=$name?></h1>
                <h3>Only account</h3>
                <p>See more about <?=$name?><a href="#"> Contact Info</a>            
                </p>

                <div class="mutual-connection">
                    <img src="../img/Developers/Developer Pyro.png">
                    <img src="../img/Developers/Developer AJ.png">
                    <img src="../img/Developers/Developer Calvin.png">
                    <img src="../img/Developers/Developer Mayks.png">
                    <span>4 mutual Friends</span>
                    


                </div>

                <div class="profile-btn">
                    <a href="#"><img src="../img//message.png">Message</a>
                    <a href="update_profile.php" class="primary-btn"><img src="../img/dots.png">Edit Profile</a>

                </div>
            </div>
        </div>
    </div>

               
    <div class="feeds">
  
  <div class="feed">
  <?php while($user = $posts_result->fetch_assoc()):
      $identifier = $user["public_name"];
      $post_content = $user["post_content"];
      $date_created = $user["date_created"];
      $publicDP = $user["public_profile_picture"];
      $post_image = $user["post_image"];
      $handlebar = $user["handlebar"];
      $post_id = $user["post_id"];
      $adopted = $user["adopted"];?>      
      
          <div class="head">

              <div class="user">

                  <div class="profile-picture">

                      <img src="../img/profile-images/<?=$publicDP?>">

                  </div>

                  <div class="info">

                      <h3><?=$identifier?></h3>
                      <p class ="text-muted">@<?=$handlebar?></p>

                      <small><?=$date_created?></small>

                  </div>

              </div>

              <span class="edit">
                      
                  <i class="uil uil-ellipsis-h"></i>

              </span>

          </div>

          <div class="photo">

              <img src="../img/post_images/<?=$post_image?>">

          </div>

          <div class="action-buttons">
              <div class="interaction-buttons">
              <?php if($adopted=="yes"):?>
                                <h3>Adopted</h3>
                                <?php endif; ?>

                  <button  onclick="Toggle1()"id="btnh1" class="btnh1"><i class="fa-solid fa-heart"></i></button>
                  <button id="btnh1" class="btnh1"><i class="uil uil-comment-dots"></i></button>
                  <form action="adopt-process.php" method="post">
                    <input type="hidden" id="adopt_id" name="adopt_id" value="<?=$post_id?>">
                  <button type="submit" id="adopt" name="adopt" class="btnh1"><i class="uil uil-house-user"></i></button>
     </form>

              </div>
              
              <form action="comment.php" method = "post">
              <div class="comment">
                  <input type ="hidden" name="post_id" id="post_id" value="<?=$post_id?>">
                  <input id="comment" name ="comment" type="text" class="comment-section" placeholder="Write a comment...">
                  <button id="submit_comment" name ="submit_comment" class="btn btn-comments" type="submit"><i class="uil uil-message"></i></button>
              </div>
  </form>
  <?php 




?>


              <div class="home">
                  <button id="btnh1" class="btnh1"></i></button>
              </div>
          </div>


          <div class="captions">
              <p><b><?=$identifier?></b><?=" ", $post_content?></p>
          </div>
          <div class="comments">
              
              <?php include "showComment.php";
              while ($userComment = $queryComments -> fetch_assoc()):
                  $commenter = $userComment["username"];
                  $commentContent = $userComment["content"];
              
              
              ?>
              <p><b><?=$commenter?></b><?=" ", $commentContent?></p>
              <?php endwhile;?>
              
          </div>
          


          <div style="height: 10px; background-color: #EAEFF5; margin-bottom: 12px" ></div>

          <?php endwhile;?>


        </div>
    </div>
    <script src="java.js"></script>
</body>

</html>