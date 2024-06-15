<?php
// include config file
include_once("config.php");
session_start();
$getUsers = "
	SELECT
		u.id,
		u.firstName,
		u.lastName,
		u.type,
		u.image,
		u.status,
		f.user_id AS following_id
	FROM users AS u
	LEFT JOIN follow AS f ON u.id = f.followed_id AND f.user_id = {$_SESSION["id"]}
	WHERE u.status = 'Online' AND u.type = 'Verificado' AND NOT u.id = {$_SESSION["id"]}
";
$query = mysqli_query($conn, $getUsers);

if(!$query){
    echo "La consulta fallo";
}else{
    if(mysqli_num_rows($query) == 0){
        echo '<div id="errors-online">No hay usuarios activos ahora</div>';
    }elseif(mysqli_num_rows($query) > 0){
        include_once("follow.php");
    }else{
        echo "Hubo un fallo, mientras se buscaba usuarios";
    }
}

