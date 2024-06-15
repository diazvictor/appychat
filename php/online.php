<?php
// include config file
include_once("config.php");
session_start();
$sql = "SELECT * FROM `users` WHERE id != '{$_SESSION["id"]}' AND status = 'Online'";
$query = mysqli_query($conn, $sql);

if(!$query){
    echo "La consulta fallo";
}else{
    if(mysqli_num_rows($query) == 0){
        echo '<div id="errors-online">No hay usuarios activos ahora</div>';
    }elseif(mysqli_num_rows($query) > 0){
        include_once("data.php");
    }else{
        echo "Hubo un fallo, mientras se buscaba usuarios";
    }
}
?>