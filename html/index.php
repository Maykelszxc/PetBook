<?php
$likedPost = false;

session_start();
$UID = $_SESSION["user_id"];


if (isset($_SESSION["user_id"])) {


    $dbconn = require __DIR__ . "/db.php";



    $sql = "SELECT * FROM account_tb
            WHERE user_id = $UID";

    $result = $dbconn->query($sql);

    $user = $result->fetch_assoc();
    $name = $user["name"];

    $dp = $user["profile_picture_name"];


        $posts_sql = "SELECT * FROM posts ORDER BY date_created DESC";

    $posts_result = $dbconn->query($posts_sql);

    $message_sql = "SELECT * FROM messages WHERE incoming_msg_id=$UID Except
    SELECT * FROM messages WHERE outgoing_msg_id = $UID ORDER BY msg_id DESC";

    $message_result = $dbconn->query($message_sql);






};
if ($_SERVER["REQUEST_METHOD"] === "POST"){

if (isset($_POST["submit"])){
//for images
    $image_file = $_FILES['image'];
    $image_name = $image_file['name'];
    $image_data = ($image_file['tmp_name']);
    $extension = explode('.',$image_name);
    $fileActualExt = strtolower(end($extension));
    $newName = uniqid('', true).".".$fileActualExt;




    $folder='../img/post_images/';

//post the content to database

    $dbconn = require __DIR__ . "/db.php";
    $publicProfileSql = "SELECT * FROM account_tb WHERE user_id = $UID";
    $publicProfileSqlResult = $dbconn ->query($publicProfileSql);
    $publicProfile = $publicProfileSqlResult->fetch_assoc();
    $publicUser = $publicProfile["name"];
    $postProfile = $publicProfile["profile_picture_name"];
    $userhandle = $publicProfile["username"];
    $newPostSql = "INSERT INTO posts (user_id, public_name, public_profile_picture, post_content,post_image, handlebar) VALUES (?,?,?,?,?,?)";


    $stmt = $dbconn->stmt_init();
    $stmt->prepare($newPostSql);
    $stmt->bind_param("isssss",
                    $UID,
                    $publicUser,
                    $postProfile,
                    $_POST["post_content"],
                    $newName,
                    $userhandle
                    );


    if($stmt->execute()){
        move_uploaded_file($image_data,$folder.$newName);
    header("location: index.php");
    };


                };




if(isset($_POST["like"])){
    $likePost = $_POST["post_id_like"];



    $likeSQL = "UPDATE posts SET likes = likes + 1 WHERE post_id = $likePost";

    
    
    mysqli_query($dbconn,$likeSQL);
    
}
}
if (! isset($user)){

    header("location: login.php");
    exit;
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="shortcut icon" type="x-icon" href="Logo1.png">

    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <title>PetBook</title>

</head>

<body>

    <!--NAV BAR-->

    <nav>
        <div class="container">
            <a href="index.php">
                <h2 class="logo">
                   PetBook
                </h2>
            </a>



            <div class="create">
                <label class="btn btn-primary" for="create-post">Create</label>

                <div class="profile-picture">
                    <a href = "profile.php"><img src="../img/profile-images/<?=$dp?>"></a>
                </div>

            </div>
        </div>
    </nav>

    <!--MAIN CONTENT-->

    <main>
        <div class="container">

            <!--LEFT CONTENT-->

            <div class="left">

                <a class="profile">
                    <div class="profile-picture">
                        <img src="../img/profile-images/<?=$dp?>">
                    </div>
                    <div class="handle">
                        <h4><?=$name?></h4>
                        <p class="text-muted">
                            @<?=$user["username"]?>
                        </p>
                    </div>
                </a>

                <!--LEFT SIDE BAR-->

                <div class="sidebar">

                    <a class="menu-item active">

                        <span><i class="uil uil-home"></i></span>
                        <h3>Home</h3>

                    </a>

                    <!--NOTIFICATION
                        <a class="menu-item" id="notifications">
                        <span><i class="uil uil-bell"><small class="notification-count">5+</small></i></span>
                        <h3>Notifications</h3>
                        NOTIFICATION POPUP
                        <div class="notification-popup">
                            
                            <div>
                                <div class="profile-picture">
                                    <img src="../img/Developers/Developer Mayks.png">
                                </div>
    
                                <div class="notification-body">
                                    <b>Michael Jose</b> accepted your pet request
                                    <small class="text-muted"> 2 DAYS AGO</small>
                                </div>
                            </div>
                            <div>
                                <div class="profile-picture">
                                    <img src="../img/Developers/Developer Pyro.png">
                                </div>
    
                                <div class="notification-body">
                                    <b>Pyro Bansuelo</b> commented on your post
                                    <small class="text-muted"> 1 HOUR AGO</small>
                                </div>
                            </div>
                        
                            <div>
                                <div class="profile-picture">
                                    <img src="../img/Developers/Developer Calvin.png">
                                </div>
    
                                <div class="notification-body">
                                    <b>Calvin De Luna</b> liked your post
                                    <small class="text-muted"> 4 MINS AGO</small>
                                </div>
                            </div>
                            <div>
                                <div class="profile-picture">
                                    <img src="../img/Developers/Developer AJ.png">
                                </div>
    
                                <div class="notification-body">
                                    <b>Anette Babatio</b> accepted your pet request
                                    <small class="text-muted"> 5 SECONDS AGO</small>
                                </div>
                            </div>
                        </div>
                    </a>
                    END OF NOTIFICATION POPUP-->

                    <a href = "chat/index.php" class="menu-item" id="messages-notifications">

                        <span><i class="uil uil-envelope-alt"></i></span>
                        <h3>Messages</h3>

                    </a>

                    <a class="menu-item" id="theme">

                        <span><i class="uil uil-palette"></i></i></span>
                        <h3>Theme</h3>
                    </a>

                    <!--<a class="menu-item">
                        <span><i class="uil uil-setting"></i></span>
                        <h3>Settings</h3>
                    </a>-->

                    <a class="menu-item" href = "logout.php">

                        <span><i class="uil uil-signout"></i></span>
                        <h3>Logout</h3>

                    </a>

                </div>
                <!--END OF LEFT SIDEBAR-->

                <label for="create-post" class="btn btn-primary">Create Post</label>

            </div>



            <!--MIDDLE CONTENT-->

            <div class="middle">

                <!--CREATE POST-->
                <form id="content_post" name="content_post" class="create-post" method = "post" enctype = "multipart/form-data">
                    <div class="create-post-input">
                        <img src="../img/profile-images/<?=$dp?>">
                        <input type = "text" id ="post_content" name ="post_content" placeholder="Post your pets..." >
                    </div>

                    <div class="create-post-links">
                        <input type="file" id="file" name="image" accept="image/*" required>
                        <label for="file"><i class="uil uil-camera"></i>Photos</label>

                        <input type="file" id="file" accept="video/*">
                        <label for="file"><i class="uil uil-video"></i>Videos</label>

                        <input type="file" id="file">
                        <label for="file"><i class="uil uil-file-upload-alt"></i>Documents</label>

                        <button name = "submit" class="btn btn-primary" type = "submit" id="submit">Post</li>
                    </div>

                </form>

                <!--NEWS FEEDS-->

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
                    $adopted = $user["adopted"];
                    $like = $user["likes"];?>      

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
                                <h1>Adopted</h1>
                                <?php endif; ?>
                                <form action="" method="post">
                                    <input type="hidden" id="post_id_like" name="post_id_like" value="<?=$post_id?>">
                                    
                                <button  type="submit" onclick="Toggle1()"id="like" name="like" class="btnh1"><i class="fa-solid fa-heart"></i><h5 style="margin-left: 20px"><?=$like?> likes</h5></button>


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




                <!--END OF NEWS FEEDS-->

            </div>


            <!--END OF MIDDLE-->

            <!--RIGHT CONTENT-->

            <div class="right">

                <div class="messages">

                    <div class="heading">

                        <h4>Messages</h4>

                    </div>

                    <!--SEARCH BAR-->

                    <div class="search-box">
                        <i class="uil uil-search"></i>
                        <input type="search" placeholder="Search Messages" id="message-search">
                    </div>

                    <!--MESSAGE CATEGORY-->

                    <div class="category">
                        <h6 class="active">Primary</h6>
                    </div>

                    <!--MESSAGE-->
                    <?php while($message = $message_result->fetch_assoc()):
                    $msg_content = $message["msg"];
                    $msg_id = $message["msg_id"];
                    $inc_msg_id = $message["incoming_msg_id"];
                    $out_msg_id = $message["outgoing_msg_id"];

                                $chatUserSQL = "SELECT name,profile_picture_name FROM account_tb WHERE user_id = $out_msg_id";
                                $chatUser = $dbconn->query($chatUserSQL);
                                $chatUserResult = $chatUser->fetch_assoc();
                                $chatName = $chatUserResult["name"];
                                $chatProfile = $chatUserResult["profile_picture_name"];
                            
                            
                            ?>



                    <div class="message">

                        <div class="profile-picture">
                            <img src="../img/profile-images/<?=$chatProfile?>">
                        </div>

                        <div class="message-body">
                            
                            <h5><?=$chatName?></h5>
                            <p class="text-muted"><?=$msg_content?></p>
                        </div>

                    </div>
                    <?php endwhile;?>

                    <div class="message">

                        <div class="profile-picture">
                            <img src="../img/profile-images/<?=$chatProfile?>">
                        </div>

                        <div class="message-body">
                            <h5>Michael Jose</h5>
                            <p class="text-bold">3 new messages</p>
                        </div>

                    </div>

                    <div class="message">

                        <div class="profile-picture">
                            <img src="../img/Developers/Developer AJ.png">
                            <div class="active"></div>
                        </div>

                        <div class="message-body">
                            <h5>Anette Babatio</h5>
                            <p class="text-bold">Edi waw!</p>
                        </div>

                    </div>

                    <!--END OF MESSAGE-->


                </div>

                <!--FRIEND REQUESTS

                <div class="friend-requests">

                    <h4>Requests</h4>

                    <div class="request">

                        <div class="info">

                            <div class="profile-picture">

                                <img src="../img/Developers/Developer Mayks.png">

                            </div>

                            <div>

                                <h5>Michael Jose</h5>
                                <p class="text-muted">
                                    69 mutual friends
                                </p>


                            </div>

                        </div>

                        <div class="action">

                            <button class="btn btn-primary">
                                Accept
                            </button>

                            <button class="btn">
                                Decline
                            </button>
                        </div>

                    </div>

                    <div class="request">

                        <div class="info">

                            <div class="profile-picture">

                                <img src="../img/Developers/Developer Calvin.png">

                            </div>

                            <div>

                                <h5>Calvin De Luna</h5>
                                <p class="text-muted">
                                    13 mutual friends
                                </p>


                            </div>

                        </div>

                        <div class="action">

                            <button class="btn btn-primary">
                                Accept
                            </button>

                            <button class="btn">
                                Decline
                            </button>
                        </div>

                    </div>

                </div>

            </div>

       END OF FRIEND REQUESTS-->


            <!-- CUSTOMIZATION OF THEME-->

            <div class="customize-theme">

                <div class="card">

                    <h2>Customize your view</h2>
                    <p class="text-muted">Manage your font size, color, and background</p>

                    <!-- FONT SIZES-->

                    <div class="font-size">
                        <h4>Font Size</h4>

                        <div>
                            <h6>Aa</h6>

                            <div class="choose-size">
                                <span class="font-size-1"></span>
                                <span class="font-size-2 active"></span>
                                <span class="font-size-3"></span>
                                <span class="font-size-4"></span>
                                <span class="font-size-5"></span>
                            </div>

                            <h3>Aa</h3>

                        </div>
                    </div>

                    <!-- PRIMARY COLORS-->

                    <div class="color">
                        <h4>Color</h4>

                        <div class="choose-color">
                            <span class="color-1 active"></span>
                            <span class="color-2"></span>
                            <span class="color-3"></span>
                            <span class="color-4"></span>
                            <span class="color-5"></span>
                        </div>

                    </div>

                    <!-- BACKGROUND COLORS-->

                    <div class="background">
                        <h4>Background</h4>

                        <div class="choose-bg">
                            <div class="bg-1 active">
                                <span></span>
                                <h5 for="bg-1">Light</h5>
                            </div>

                            <div class="bg-2">
                                <span></span>
                                <h5 for="bg-2">Dim</h5>
                            </div>

                            <div class="bg-3">
                                <span></span>
                                <h5 for="bg-3">Lights Out</h5>
                            </div>
                        </div>

                    </div>

                </div>
            </div>

        </div>
    </main>




<script src="java.js"></script>

</body>
</html>