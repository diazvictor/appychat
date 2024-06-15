<?php
include_once("config.php");
session_start();

// get message query
$getMsgQuery = "SELECT * FROM reports ORDER BY id DESC";
$runGetMsgQuery = mysqli_query($conn, $getMsgQuery);
if(!$runGetMsgQuery){
    echo "Query Failed";
}else{
    if(mysqli_num_rows($runGetMsgQuery) > 0){
        while($row = mysqli_fetch_assoc($runGetMsgQuery)){

            $fetchReportedData = "SELECT * FROM users WHERE id = '{$row["reported_id"]}'";
            $runFetchReportedData = mysqli_query($conn, $fetchReportedData);
            $reportedData = mysqli_fetch_assoc($runFetchReportedData);
            $fetchReporterData = "SELECT * FROM users WHERE id = '{$row["reporter_id"]}'";
            $runFetchReporterData = mysqli_query($conn, $fetchReporterData);
            $reporterData = mysqli_fetch_assoc($runFetchReporterData);

			if ($reporterData['type'] === 'Verificado') {
				$insignia = '<img width="20px" height="20px" src="images/Iconos/Verificacion.png" alt="Verificado">';
			} else if ($reporterData['type'] === 'CEO') {
				$insignia = '<img width="20px" height="20px" src="images/Iconos/CEO.png" alt="CEO">';
			} else if ($reporterData['type'] === 'Moderador') {
				$insignia = '<img width="20px" height="20px" src="images/Iconos/Moderador.png" alt="CEO">';
			} else {
				$insignia = '';
			}

			if ($reportedData['type'] === 'Verificado') {
				$insignia2 = '<img width="20px" height="20px" src="images/Iconos/Verificacion.png" alt="Verificado">';
			} else if ($reportedData['type'] === 'CEO') {
				$insignia2 = '<img width="20px" height="20px" src="images/Iconos/CEO.png" alt="CEO">';
			} else if ($reportedData['type'] === 'Moderador') {
				$insignia = '<img width="20px" height="20px" src="images/Iconos/Moderador.png" alt="CEO">';
			} else {
				$insignia2 = '';
			}

            $onlineUsers = '
                <div class="profile">
                    <!-- profile image -->
                    <div class="image">
                        <img src="images/Profiles/'.($reporterData["image"] ? $reporterData["image"] : $reportedData["image"]).'" alt="">
                    </div>
                    <!-- name -->
                    <h2 class="name">'.$reporterData["firstName"]." ".$reporterData["lastName"] . ' ' . $insignia . ' ha reportado a: '.$reportedData["firstName"]." ".$reportedData["lastName"]. ' ' . $insignia2 . '</h2>
                    <!-- last message -->
                    <p class="lastMessage">Razon del reporte: '.$row["reason"].'</p>
                    <!-- status => Online or Offline -->
                    <div class="report-buttons">
                        <a class="report-button" href="php/manageReport.php?report='.$row["id"].'">
                            <img src="images/limpiar.png" class="chat-icon">
                        </a>
                        <a class="report-button" href="php/manageReport.php?ban='.$reportedData["id"].'">
                            <img src="images/prohibido.png" class="chat-icon">
                        </a>
                    </div>
                </div>
                
                ';

            echo $onlineUsers;
        }
    }
    else{
        echo '<div id="errors">No hay reportes disponibles</div>';
    }
}

?>


