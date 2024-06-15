<?php


    include_once("config.php");
    session_start();


    if($_SESSION['rol'] == "Moderador" || $_SESSION['rol'] == "CEO"){


        $usersQuery = "SELECT * FROM users";
        $runUsersQuery = mysqli_query($conn, $usersQuery);

        if(!$runUsersQuery){
            echo "Query Failed";
        }else {

            if(mysqli_num_rows($runUsersQuery) > 0){
                while($row = mysqli_fetch_assoc($runUsersQuery)){

                    $status = ($row["ban"] == 1) ? "Baneado" : "Activo";
                    $icon = ($row["ban"] == 0) ? "images/prohibido.png" : "images/limpiar.png";

                    $registedUsers = '
                        <div class="profile">
                            <!-- profile image -->
                            <div class="image">
                                <img src="images/Profiles/'.$row["image"].'" alt="">
                            </div>
                            <!-- name -->
                            <h2 class="name">'.$row["firstName"]." ".$row["lastName"].'</h2>
                            <!-- last message -->
                            <p class="lastMessage">Estado de usuario: '.$status.', Rango del usuario: '.$row['type'].'</p>
                            <!-- status => Online or Offline -->
                            <div class="report-buttons">
                                <a class="report-button" href="php/manageRank.php?upgrade='.$row["id"].'">
                                    <img src="images/upload.png" class="chat-icon">
                                </a>
                                <a class="report-button" href="php/manageRank.php?downgrade='.$row["id"].'">
                                    <img src="images/downgrade.png" class="chat-icon">
                                </a>
                                <a class="report-button" href="php/manageRank.php?ban='.$row["id"].'">
                                    <img src="'.$icon.'" class="chat-icon">
                                </a>
                            </div>
                        </div>
                        
                        ';
        
                    echo $registedUsers;
                }
            } else {
                echo "No hay usuarios registrados";
            }

        }
    }
