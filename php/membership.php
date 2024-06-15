<?php

try {
    include_once("config.php");
    session_start();

    $data = json_decode(file_get_contents('php://input'), true);
    $membershiprice = $data['price'];

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

    $memberid = $_SESSION['id'];

    // SEARCH IF MEMBER HAVE MEMBERSHIP

    $memberQuery = "SELECT * FROM membresias WHERE id_user = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $memberQuery);
    mysqli_stmt_bind_param($stmt, "s", $memberid);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if(mysqli_num_rows($result) > 0){
        $deleteMembership = "DELETE FROM membresias WHERE id_user = ?";
        $stmt = mysqli_prepare($conn, $deleteMembership);
        mysqli_stmt_bind_param($stmt, "s", $memberid);
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
    
    $notifyQuery = "INSERT INTO notifications (id_user, message, type) VALUES ('{$memberid}', 'Felicidades, te has unido al programa de miembros de Appy Chat!', 0)";
    $runNotifyQuery = mysqli_query($conn, $notifyQuery);

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
    mysqli_stmt_bind_param($stmt, "ss", $memberid, $expiration);
    $runQuery = mysqli_stmt_execute($stmt);

    if (!$runQuery) {
        // JSON response code & success message
        header("Content-Type: application/json");
        header("HTTP/1.1 " . 400);
        echo json_encode(array("error" => "Error al asignarte una membresia, contacta a un administrador."));
        exit;
    } else {
        header("Content-Type: application/json");
        header("HTTP/1.1 " . 200);
        echo json_encode(array("message" => "Se te asigno una Membresia {$membership} correctamente. Tu membresia expira el {$expiration}"));
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
