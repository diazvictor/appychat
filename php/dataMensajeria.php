<?php
	// include config file
	include_once("config.php");

	// Mostrar los mensajes
	while($row = mysqli_fetch_assoc($query)){
		// show last message send
		// $diffID = $row['id_outgoing'] == $_SESSION['id'] ? $row['id_incoming'] : $row['id_outgoing'];
		$sql = "SELECT * FROM users WHERE id = {$row['id']}";
		$result = mysqli_query($conn, $sql);

		if ($result) {
			$user = mysqli_fetch_assoc($result);
		} else {
			echo "Query Failed";
		}

		// Buscar ultimo mensaje entre diff y sessionid
		$lastMessage = "SELECT * FROM messages WHERE (outgoing = '{$_SESSION["id"]}' AND incoming = '{$user['id']}') OR (outgoing = '{$user['id']}' AND incoming = '{$_SESSION["id"]}') ORDER BY messages_id DESC LIMIT 1";
		$runQuery = mysqli_query($conn, $lastMessage);
		// $lastMessage = $row["last_message"];

		$sqlTotalUnreadMessages = "
			SELECT
				count(messages_id) AS messages_not_see
			FROM messages
			WHERE outgoing = {$user['id']} AND incoming = {$_SESSION['id']}
			AND see = 0
		";
		$runTotalUnreadMessages = mysqli_query($conn, $sqlTotalUnreadMessages);
		$totalUnreadMessages = mysqli_fetch_assoc($runTotalUnreadMessages)["messages_not_see"];

		$checkMembresia = "SELECT * FROM membresias WHERE id_user = {$user['id']}";
		$runCheckMembresia = mysqli_query($conn, $checkMembresia);

		if($runQuery){
			$row3 = mysqli_fetch_assoc($runQuery);
			if(mysqli_num_rows($runQuery) > 0){
				$lastMessage = $row3['messages'];
			}else{
				$lastMessage = "No hay mensajes disponibles";
			}
		}

		// show status online or offine
		if($user['status'] == "Online"){
			$status = "online";
		}else{
			$status = "offline";
		}

		// Aquí añadimos un identificador único para cada mensaje
	  
		$messageId = $user['id'];
		$insignia = "";
		if ($user['type'] === 'Verificado') {
			$insignia = '<img width="20px" height="20px" src="images/Iconos/Verificacion.png" alt="Verificado">';
		} else if ($user['type'] === 'CEO') {
			$insignia = '<img width="20px" height="20px" src="images/Iconos/CEO.png" alt="CEO">';
		} else if ($user['type'] === 'Moderador') {
			$insignia = '<img width="20px" height="20px" src="images/Iconos/Moderador.png" alt="CEO">';
		}
		$insigniaMembresia = "";
		if (mysqli_num_rows($runCheckMembresia) > 0) {
			$insigniaMembresia = '<img width="20px" height="20px" src="images/Iconos/Insignia.png" alt="Insignia">';
		}

		$onlineUsers = '
		<div>
			<a href="messages?userid='.$user["id"].'">
				<div class="profile">
					<div class="image" style="top:10px">
						<img src="images/Profiles/'.$user["image"].'" alt="">
					</div>

					<h2 style="display:flex; flex-direction:row; gap:5px;  align-items:center" class="name">
						'.$user["firstName"] .' '. $user["lastName"] .'
						'. $insignia .'
						'. $insigniaMembresia .'
					</h2>

					<p class="lastMessage">'.$lastMessage.'</p>

					<div style="display:flex;flex-direction:row; gap:5px; justify-content:center; align-items:center;" class="status '.$status.'">
						<a href="javascript:void(0);" style="text-decoration: none">
							<span style="width: 25px; height: 25px;color:white; padding: 4px; background-color: red; border-radius: 50%; display: flex; flex-direction:column;justify-content:center; align-items:center; right:0px; animation: pulse 1s infinite;" class="see">
								'. $totalUnreadMessages .'
							</span>
						</a>
						<a href="javascript:void(0);">
							<img style="display: block" src="images/chat.png" class="chat-icon">
						</a>
						<a href="php/mensajeria?delete='.$user["id"].'">
							<img style="display: block" src="images/Iconos/Basura.png" class="chat-icon">
						</a>
					</div>
				</div>
			</a>
		</div>
	';
	echo $onlineUsers;

	};

?>
