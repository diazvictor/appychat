<?php
include_once("php/config.php");
session_start();
if(isset($_SESSION['id'])){
    header("location: home");
}
?>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cuenta Desactivada</title>
    <link rel="stylesheet" href="css/precios.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css">
    <link rel="shortcut icon" href="assets/AppyChatBienvenida.jpeg" type="image/x-icon">
</head>

<body>
    <section class="section h-100 d-grid align-items-center">
        <div class="container d-grid align-items-center justify-content-center text-center h-50">
            <h1>Cuenta Desactivada</h1>
            <p>Si deseas recuperar el acceso a tu cuenta debes contactarnos en nuestro email de contacto: appychatsoporteofficial@gmail.com</p>
        </div>
        
    </section>
</body>

</html>