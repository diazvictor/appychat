<?php
include_once("config.php");

session_start();
if(isset($_GET['id'])){
    $followId = $_GET['id'];
    $followerId = $_SESSION['id'];

    // Check if user is already following
    $checkQuery = "SELECT * FROM `follow` WHERE `user_id` = '{$followerId}' AND `followed_id` = '{$followId}'";
    if($runCheckQuery = mysqli_query($conn, $checkQuery)){
        if(mysqli_num_rows($runCheckQuery) > 0){
            $deleteQuery = "DELETE FROM `follow` WHERE `user_id` = '{$followerId}' AND `followed_id` = '{$followId}'";
            $runDeleteQuery = mysqli_query($conn, $deleteQuery);

            $getUserFollowerData = "SELECT * FROM `users` WHERE `id` = '{$followerId}'";
            $runGetUserFollowerData = mysqli_query($conn, $getUserFollowerData);
            $userFollowerData = mysqli_fetch_assoc($runGetUserFollowerData);
            // Notify
            $notifyQuery = "INSERT INTO `notifications`(`id_user`, `message`) VALUES ('{$followId}','El usuario {$userFollowerData['firstName']} {$userFollowerData['lastName']} ha dejado de seguirte')";
            $runNotifyQuery = mysqli_query($conn, $notifyQuery);
            header("location: ../follows");
        }
    }
}
?>
