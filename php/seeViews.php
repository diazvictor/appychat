<?php
// include config file
include_once("config.php");
session_start();

$user_id = $_GET['user'];
$viewsQuery = "SELECT * FROM vistas_perfil WHERE id_profile = {$user_id} ORDER BY id DESC";

// Run Query 

$query = mysqli_query($conn, $viewsQuery);
if(!$query){
    echo "La consulta fallo";
} else {


    while($view = mysqli_fetch_assoc($query)){
        $getUser = "SELECT * FROM users WHERE id = {$view["id_user"]}";
        $runGetUser = mysqli_query($conn, $getUser);
        $user = mysqli_fetch_assoc($runGetUser); 

        echo '
                <a class="profile" href="profile?id='.$user['id'].'" style="text-decoration:none; color: black;">
                    <!-- profile image -->
                    <div class="image">
                        <img src="images/Profiles/'.$user["image"].'" alt="">
                    </div>
                    <!-- last message -->
                    <p style="margin-left: 75px;">El usuario '.$user["firstName"].' '.$user["lastName"].' vio el perfil: '.$view['times'].' veces</p>
                    <!-- status => Online or Offline -->
                    <div class="report-buttons">
                    </div>
                </a>
                
                ';
    }
}

?>