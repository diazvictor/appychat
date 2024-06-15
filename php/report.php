<?php


include_once("config.php");
session_start();
$reporterid = $_SESSION['id'];

$postData = json_decode(file_get_contents('php://input'), true);
$reportedid = $postData['reported'];
$reason = $postData['reason'];

$reportQuery = "INSERT INTO `reports`(`reported_id`, `reporter_id`, `reason`) VALUES ('{$reportedid}','{$reporterid}', '{$reason}')";
$runReportQuery = mysqli_query($conn, $reportQuery);

if (!$runReportQuery) {
    // JSON response code & success message
    header("Content-Type: application/json");
    header("HTTP/1.1 " . 400);
    echo json_encode(array("message" => "Error al reportar"));
} else {
    header("Content-Type: application/json");
    header("HTTP/1.1 " . 200);
    echo json_encode(array("message" => "Reportado correctamente"));
}