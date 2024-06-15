<?php
// include config file
include_once("config.php");
session_start();

// if "login" button clicked
if(isset($_POST['login'])){
    // store email
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    // store password
    $password = md5($_POST['password']);

    // check email is unique or not
    $emailQuery = "SELECT * FROM `users` WHERE email = '$email'";
    $runEmailQuery = mysqli_query($conn, $emailQuery);

    if(!$runEmailQuery){
        echo "Consulta Fallida";
    }else{
        if(mysqli_num_rows($runEmailQuery) > 0){
            $row = mysqli_fetch_assoc($runEmailQuery);

            if($row['ban'] == 1){
                echo 'Desactivado';
                exit();
            }

            $passwordQuery = "SELECT * FROM `users` WHERE email = '$email' AND password = '$password'";
            $runPasswordQuery = mysqli_query($conn, $passwordQuery);

            if(!$runPasswordQuery){
                echo "Consulta Fallida";
            }else{
                if(mysqli_num_rows($runPasswordQuery) > 0){
                    $fetchData = mysqli_fetch_assoc($runPasswordQuery);
                    $_SESSION['id'] = $fetchData['id'];
                    $_SESSION['rol'] = $fetchData['type'];
                    // update status
                    $status = "Online";

                    // status query
                    $statusQuery = "UPDATE users SET status = '{$status}' WHERE id = '{$_SESSION["id"]}'";
                    $runStatusQuery = mysqli_query($conn, $statusQuery);
                    if(!$runStatusQuery){
                        echo "Fallo al actualizar estado";
                    }else{
                           echo "Exito";
                      
                    }
                }else{
                    echo "Contraseña Incorrecta";
                }
            }
        }else{
            echo "Email invalido";
        }
    }
}
?>