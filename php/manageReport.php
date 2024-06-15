<?php



if($_SERVER["REQUEST_METHOD"] == "GET"){
    include_once("config.php");
    session_start();

    if(isset($_GET["report"])){
        $report = $_GET["report"];

        $deleteReportQuery = "DELETE FROM reports WHERE id = '{$report}'";
        $runDeleteReportQuery = mysqli_query($conn, $deleteReportQuery);
        if(!$runDeleteReportQuery){
            header ("location: ../reports");
        }else{
            header ("location: ../reports");
        }
    }

    if(isset($_GET["ban"])){
        $banned = $_GET["ban"];

        $banUserQuery = "UPDATE users SET ban = 1 WHERE id = '{$banned}'";
        $runBanUserQuery = mysqli_query($conn, $banUserQuery);

        $deleteReportsUserBanned = "DELETE FROM reports WHERE reported_id = '{$banned}'";
        $runDeleteReportsUserBanned = mysqli_query($conn, $deleteReportsUserBanned);

        if(!$runBanUserQuery){
            header ("location: ../reports");
        }else{
            header ("location: ../reports");
        }
    }


}
