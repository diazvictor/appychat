<?php
include_once("php/config.php");
session_start();
if(!isset($_SESSION['id'])){
    header("location: home");
}

$id = $_SESSION['id'];

// Preguntamos si tiene membresia.

$membresiaQuery = "SELECT * FROM membresias WHERE id_user = '$id'";
$runMembresiaQuery = mysqli_query($conn, $membresiaQuery);
$membresia = "";
if(mysqli_num_rows($runMembresiaQuery) > 0){
    $membresia = mysqli_fetch_assoc($runMembresiaQuery);
} else {
    // Redireccionar a home
    header("location: home");
}

// Preguntamos si el expire_date coincide o ya expiro

if($membresia != ""){
    $expire_date = $membresia['expire_date'];
    $today = date("Y-m-d");
    if($expire_date < $today){
        $membresia = "";
    }
}

if($membresia == ""){
    // Redireccionar

    header("location: home");
}

if(isset($_GET['set'])){
    $set = $_GET['set'];
    $marcosQuery = "SELECT * FROM marcos WHERE id = '$set'";
    $runMarcosQuery = mysqli_query($conn, $marcosQuery);
    $marco = mysqli_fetch_assoc($runMarcosQuery);
    $marco_url = $marco['marco_url'];

    $updateMarcoQuery = "UPDATE users SET marco = '$marco_url' WHERE id = '$id'";
    $runUpdateMarcoQuery = mysqli_query($conn, $updateMarcoQuery);
    
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Tienda de Marcos | Appy</title>
    <!-- css linked -->
    <link rel="stylesheet" href="css/marcos.css">
    <link rel="stylesheet" href="css/online.css">

    <link rel="shortcut icon" href="assets/AppyChatBienvenida.jpeg" type="image/x-icon">
    <!-- Icons --> 

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />

    <!-- fontawesome CDN -->
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
</head>
<body>
    <div class="circle"></div>
    <div class="circle circle2"></div>
    <div id="container">
        <!-- header -->
        <div id="header">
        <?php
        $headerQuery = "SELECT * FROM `users` WHERE id = '{$_SESSION["id"]}'";
        $runHeaderQuery = mysqli_query($conn, $headerQuery);

        if(!$runHeaderQuery){
            echo "connection failed";
        }else{
            $info = mysqli_fetch_assoc($runHeaderQuery);
        ?>
            <!-- profile image -->
            <div id="details-online">

                <div class="divide">
                <img src="images/appyonline.png" class="onlineimg">
                <h1 id="announce">Marcos</h1>
                </div>
                
                <div class="btn-group">
                <button class="noselect"><a><span class='text'>Regresar</span></a><span class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M24 20.188l-8.315-8.209 8.2-8.282-3.697-3.697-8.212 8.318-8.31-8.203-3.666 3.666 8.321 8.24-8.206 8.313 3.666 3.666 8.237-8.318 8.285 8.203z"/></svg></span></button>
                </div>
            </div>
            <?php
            }
            ?>
            <!-- cerrar button -->
 
    
        </div>

        

        <!-- display online users -->
        <!-- uses list -->
        <div id="onlineUsers">
            <!-- <a href="#">
                <div class="profile"> -->
                    <!-- profile image -->
                    <!-- <div class="image">
                        <img src="assets/image1.jpg" alt="">
                    </div> -->
                    <!-- name -->
                    <!-- <h2 class="name">Code Smacher</h2> -->
                    <!-- last message -->
                    <!-- <p class="lastMessage">Lorem ipsum dolor sit amet consectetur adipisicing elit. Molestiae dignissimos tempora odio ducimus natus temporibus beatae sapiente, eveniet modi veniam?</p> -->
                    <!-- status => Online or Offline -->
                    <!-- <div class="status online"></div>
                </div>
            </a> -->
            <!-- <div id="errors">No user are avilable to chat</div> -->
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="js/marcos.js"></script>
</body>
</html>