<?php
	include_once("php/config.php");
	session_start();

	if (($_SESSION['rol']) != "CEO" && ($_SESSION['rol']) != "Moderador") {
		header("location: home");
	}
?> 
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Administrar | Appy</title>
    <!-- css linked -->
    <link rel="stylesheet" href="css/editadmin.css">
    <link rel="stylesheet" href="css/online.css">

    <!-- Icons -->
    <link rel="shortcut icon" href="assets/AppyChatBienvenida.jpeg" type="image/x-icon">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />

    <!-- fontawesome CDN -->
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
</head>

<body>
    <div class="circle"></div>
    <div class="circle circle2"></div>
    <div style="overflow:hidden" id="container">
        <!-- header -->
        <div id="header">
            <!-- profile image -->
			<div id="details-online">
				<div class="divide">
					<img src="images/appyonline.png" class="onlineimg">
					<h1 id="announce">Grupos</h1>
				</div>

				<div class="btn-group">
					<button class="noselect">
						<a>
							<span class="text">Regresar</span>
						</a>
						<span class="icon">
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
								<path d="M24 20.188l-8.315-8.209 8.2-8.282-3.697-3.697-8.212 8.318-8.31-8.203-3.666 3.666 8.321 8.24-8.206 8.313 3.666 3.666 8.237-8.318 8.285 8.203z" />
							</svg>
						</span>
					</button>
				</div>
			</div>
        </div>

        <!-- Buscador box -->
        <div id="searchBox">
            <!-- Visita o vista "fontawesome.com" for icons  -->
            <input type="text" id="search" placeholder="Buscador Global" autocomplete="OFF">
            <i class="fas fa-search searchButton"></i>
        </div>

        <!-- groups list -->
        <div style="overflow-y:scroll; padding-bottom:80px" id="onlineUsers"></div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="js/manageGroups.js"></script>
</body>

</html>

