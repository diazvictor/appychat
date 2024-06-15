<?php
// include config file
include_once("config.php");
session_start();

$marcosQuery = "SELECT * FROM marcos";



// Run Query 

$query = mysqli_query($conn, $marcosQuery);
if (!$query) {
    echo "La consulta fallo";
} else {


    while ($marco = mysqli_fetch_assoc($query)) {
        echo '
                <a class="profile" href="marcos?set=' . $marco['id'] . '" style="text-decoration:none; color: black;">
                    <!-- profile image -->
                    <div class="image">
                        <img src="images/Marcos/' . $marco["marco_url"] . '" alt="">
                    </div>
                    <!-- last message -->
                    <h3 class="name">' . $marco["nombre"] . '</h3>
                    <p>' . $marco["descripcion"] . '</p>
                    <!-- status => Online or Offline -->
                    <div class="report-buttons">
                    </div>
                </a>
                
                ';
    }
}
