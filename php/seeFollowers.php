<?php
// include config file
include_once("config.php");
session_start();

$user_id = $_GET['user'];
$followsQuery = "SELECT * FROM follow WHERE followed_id = {$user_id} ORDER BY id DESC";

// Run Query 

$query = mysqli_query($conn, $followsQuery);
if(!$query){
    echo "La consulta fallo";
} else {


    while($follow = mysqli_fetch_assoc($query)){
        $getUser = "SELECT * FROM users WHERE id = {$follow["user_id"]}";
        $runGetUser = mysqli_query($conn, $getUser);
        $user = mysqli_fetch_assoc($runGetUser); 

        echo '
                <a class="profile" href="profile?id='.$user['id'].'" style="text-decoration:none; color: black;">
                    <!-- profile image -->
                    <div class="image">
                        <img src="images/Profiles/'.$user["image"].'" alt="">
                    </div>
                    <!-- last message -->
                    <p style="margin-left: 75px;">'.$user["firstName"].' '.$user["lastName"].'</p>
                    <!-- status => Online or Offline -->
                    <div class="report-buttons">
                    </div>
                </a>
                
                ';
    }
}

?>