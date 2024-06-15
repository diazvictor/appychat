<?php



if($_SERVER["REQUEST_METHOD"] == "GET"){
    include_once("config.php");
    session_start();
    $id_staff = $_SESSION['id'];

    if(isset($_GET["upgrade"])){
        $id = $_GET["upgrade"];


        $roleQuery = "SELECT * FROM users WHERE id = '{$id}'";
        $runRoleQuery = mysqli_query($conn, $roleQuery);
        $row = mysqli_fetch_assoc($runRoleQuery);

        if($row["type"] == "CEO"){
            header ("location: ../manageUsers");
            exit();
        }

        if ($id_staff == $id) {
            header ("location: ../manageUsers");
            exit();
        }

        $role = ($row["type"] == "Usuario") ? "Verificado" : "Moderador";
        $role = ($row["type"] == "Moderador") ? "CEO" : $role;


        $getStaffData = "SELECT * FROM users WHERE id = '{$id_staff}'";
        $runStaffData = mysqli_query($conn, $getStaffData);
        $staffData = mysqli_fetch_assoc($runStaffData);    
    
        if($staffData["type"] == "Moderador" && ($row["type"] == "Verificado" || $row["type"] == "Moderador")){
            header ("location: ../manageUsers");
            exit();
        }

        $upgradeQuery = "UPDATE users SET type = '{$role}' WHERE id = '{$id}'";
        $runUpgradeQuery = mysqli_query($conn, $upgradeQuery);

        
        $notifyQuery = "INSERT INTO notifications (id_user, message) VALUES ('{$id}', 'Tu rango ha sido cambiado a {$role}')";
        $runNotifyQuery = mysqli_query($conn, $notifyQuery);


        if(!$runUpgradeQuery){
            header ("location: ../manageUsers.php");
        }else{
            header ("location: ../manageUsers.php");
        }
    }

    if(isset($_GET["downgrade"])){
        $id = $_GET["downgrade"];

        $roleQuery = "SELECT * FROM users WHERE id = '{$id}'";
        $runRoleQuery = mysqli_query($conn, $roleQuery);
        $row = mysqli_fetch_assoc($runRoleQuery);



        if($row["type"] == "Usuario"){
            header ("location: ../manageUsers.php");
            exit();
        }

        if($id_staff == $id){
            header ("location: ../manageUsers.php");
            exit();
        }

        $role = ($row["type"] == "CEO") ? "Moderador" : "Usuario";
        $role = ($row["type"] == "Moderador") ? "Verificado" : $role;

        $getStaffData = "SELECT * FROM users WHERE id = '{$id_staff}'";
        $runStaffData = mysqli_query($conn, $getStaffData);
        $staffData = mysqli_fetch_assoc($runStaffData);

        if($staffData["type"] == "Moderador" && ($row["type"] == "CEO" || $row["type"] == "Moderador")){
            header ("location: ../manageUsers.php");
            exit();
        }

        $downgradeQuery = "UPDATE users SET type = '{$role}' WHERE id = '{$id}'";
        $runDowngradeQuery = mysqli_query($conn, $downgradeQuery);

        
        // Notify

        $notifyQuery = "INSERT INTO notifications (id_user, message) VALUES ('{$id}', 'Tu rango ha sido cambiado a {$role}')";
        $runNotifyQuery = mysqli_query($conn, $notifyQuery);

        if(!$runDowngradeQuery){
            header ("location: ../manageUsers.php");
        }else{
            header ("location: ../manageUsers.php");
        }
    }

    
    if(isset($_GET["ban"])){
        $banned = $_GET["ban"];

        $roleQuery = "SELECT * FROM users WHERE id = '{$banned}'";
        $runRoleQuery = mysqli_query($conn, $roleQuery);
        $row = mysqli_fetch_assoc($runRoleQuery);

        if($row["ban"] == 1){
            $banUserQuery = "UPDATE users SET ban = 0 WHERE id = '{$banned}'";
            $runBanUserQuery = mysqli_query($conn, $banUserQuery);
            $notifyQuery = "INSERT INTO notifications (id_user, message) VALUES ('{$id}', 'Fuiste desbaneado de la Aplicacion')";
            $runNotifyQuery = mysqli_query($conn, $notifyQuery);

            header ("location: ../manageUsers.php");
            die();
        }

        $banUserQuery = "UPDATE users SET ban = 1 WHERE id = '{$banned}'";
        $runBanUserQuery = mysqli_query($conn, $banUserQuery);

        
        $notifyQuery = "INSERT INTO notifications (id_user, message) VALUES ('{$id}', 'Fuiste baneado de la Aplicacion')";
        $runNotifyQuery = mysqli_query($conn, $notifyQuery);

        header ("location: ../manageUsers.php");
    }


}
