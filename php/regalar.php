<?php

try {
    include_once("config.php");
    session_start();

    $data = json_decode(file_get_contents('php://input'), true);
    $membershiprice = $data['price'];
    $userid = $data['user'];

    if (!isset($_SESSION['id'])) {
        header("Content-Type: application/json");
        header("HTTP/1.1 " . 400);
        echo json_encode(array("error" => "No esta autenticado."));
        exit;
    }

    if (!isset($data['price'])) {
        // JSON response code & error message for missing price
        header("Content-Type: application/json");
        header("HTTP/1.1 " . 400);
        echo json_encode(array("error" => "Precio no proporcionado."));
        exit;
    }

    if (!isset($data['user'])) {
        // JSON response code & error message for missing price
        header("Content-Type: application/json");
        header("HTTP/1.1 " . 400);
        echo json_encode(array("error" => "Usuario a regalar no proporcionado."));
        exit;
    }

    $memberid = $_SESSION['id'];

    $userQuery = "SELECT * FROM users WHERE id = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $userQuery);
    mysqli_stmt_bind_param($stmt, "s", $userid);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $assoc = mysqli_fetch_assoc($result);

    $gifter = "SELECT * FROM users WHERE id = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $gifter);
    mysqli_stmt_bind_param($stmt, "s", $memberid);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $gifter = mysqli_fetch_assoc($result);

    // SEARCH IF MEMBER HAVE MEMBERSHIP

    $memberQuery = "SELECT * FROM membresias WHERE id_user = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $memberQuery);
    mysqli_stmt_bind_param($stmt, "s", $userid);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if(mysqli_num_rows($result) > 0){
        $deleteMembership = "DELETE FROM membresias WHERE id_user = ?";
        $stmt = mysqli_prepare($conn, $deleteMembership);
        mysqli_stmt_bind_param($stmt, "s", $userid);
        mysqli_stmt_execute($stmt);
    }

    $membership = "";

    switch ($membershiprice) {
        case 10:
            $membership = "Mensual";
            break;
        case 30:
            $membership = "Trimestral";
            break;
        case 100:
            $membership = "Anual";
            break;
        default:
            // JSON response code & error message for invalid price
            header("Content-Type: application/json");
            header("HTTP/1.1 " . 400);
            echo json_encode(array("error" => "Precio no válido."));
            exit;
    }

    // CREATE DATE OF EXPIRATION FOR SQL QUERY

    $today = date("Y-m-d");
    $expiration = "";
    if ($membership == "Mensual") {
        $expiration = date('Y-m-d', strtotime($today . ' + 30 days'));
    }
    if ($membership == "Trimestral") {
        $expiration = date('Y-m-d', strtotime($today . ' + 90 days'));
    }
    if ($membership == "Anual") {
        $expiration = date('Y-m-d', strtotime($today . ' + 365 days'));
    }


    $memberQuery = "INSERT INTO membresias(id_user, expire_date) VALUES(?, ?)";
    $stmt = mysqli_prepare($conn, $memberQuery);
    mysqli_stmt_bind_param($stmt, "ss", $userid, $expiration);
    $runQuery = mysqli_stmt_execute($stmt);

    
    $notifyQuery = "INSERT INTO notifications (id_user, message, type) VALUES ('{$userid}', 'El usuario {$gifter['firstName']} {$gifter['lastName']} te ha regalado una membresia, felicidades!', 0)";
    $runNotifyQuery = mysqli_query($conn, $notifyQuery);

    if (!$runQuery) {
        // JSON response code & success message
        header("Content-Type: application/json");
        header("HTTP/1.1 " . 400);
        echo json_encode(array("error" => "Error al asignarte una membresia, contacta a un administrador."));
        exit;
    } else {
        header("Content-Type: application/json");
        header("HTTP/1.1 " . 200);
        echo json_encode(array("message" => "Se le asigno al usuario {$assoc['firstName']} {$assoc['lastName']} una Membresia {$membership} correctamente. Tu membresia expira el {$expiration}"));
        exit;
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
} catch (\Throwable $th) {
    //throw $th;
    header("Content-Type: application/json");
    header("HTTP/1.1 " . 400);
    echo json_encode(array("error" => "{$th}"));
    exit;
}
