<?php
include_once("config.php");

session_start();
$searchValue = mysqli_real_escape_string($conn, $_POST['search']);

$sql = "SELECT * FROM `grupo` WHERE titulo LIKE '%$searchValue%'";

$query = mysqli_query($conn, $sql);

if(mysqli_num_rows($query) > 0){
    /*
    
     <!-- display online users -->
        <!-- uses list -->
        <?php if (isset($_SESSION['rol'])) { ?>


            <div id="onlineUsers">

                <h3 class="tusgrupos">Tus grupos</h3>

            <?php } ?>

            <?php while ($grupo = mysqli_fetch_assoc($gruposQuery)) {
                $user = "SELECT * FROM users WHERE id = {$grupo["id_usuario"]}";

                $userQuery = mysqli_query($conn, $user);

                $userResult = mysqli_fetch_assoc($userQuery);

            ?>
                <a class="profile" href="grupo?id=<?= $grupo['id'] ?>" style="text-decoration: none; color: black;">

                    <?php if ($grupo['imagen'] != "") { ?>
                        <div class="image">
                            <img src="images/Grupos/<?= $grupo['imagen'] ?>" alt="">
                        </div>
                    <?php } ?>

                    <!-- last message -->
                    <div class="info">

                        <h3><?= $grupo['titulo'] ?></h3>
                        <p><?= $grupo['descripcion'] ?></p>
                    </div>
                    <!-- status => Online or Offline -->
                </a>

            <?php
            } ?>
            </div>
    
    Print something like this
    */

    while($grupo = mysqli_fetch_assoc($query)){
        $user = "SELECT * FROM users WHERE id = {$grupo["id_usuario"]}";

        $userQuery = mysqli_query($conn, $user);

        $userResult = mysqli_fetch_assoc($userQuery);
        ?>
        <a class="profile" href="grupo?id=<?= $grupo['id'] ?>" style="text-decoration: none; color: black;">

                    <?php if ($grupo['imagen'] != "") { ?>
                        <div class="image">
                            <img src="images/Grupos/<?= $grupo['imagen'] ?>" alt="">
                        </div>
                    <?php } ?>

                    <!-- last message -->
                    <div class="info">

                        <h3><?= $grupo['titulo'] ?></h3>
                        <p><?= $grupo['descripcion'] ?></p>
                    </div>
                    <!-- status => Online or Offline -->
                </a>
        <?php
    }
}else{
    echo '<div id="errors">Usuario No encontrado</div>';
}
?>