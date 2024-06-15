<?php
// include config file
include_once "config.php";

session_start();
// if signup button clicked
if (isset($_POST['edit'])) {
    if (empty($_POST['edit'])) {
        echo "Debes ingresar algun campo";
    } else {
        $userId = $_SESSION["id"];
        // first name
        $firstName = mysqli_real_escape_string($conn, $_POST["fname"]);

        //Check if first name isnt null or empty

        if (strlen($firstName) > 0) {
            $firstName = mysqli_real_escape_string($conn, $_POST["fname"]);
            $queryName = "UPDATE users SET firstName = '$firstName' WHERE id = $userId";
            $executeQuery = mysqli_query($conn, $queryName);
        }
        // last name
        $lastName = mysqli_real_escape_string($conn, $_POST["lname"]);

        if (strlen($lastName) > 0) {
            $queryLast = "UPDATE users SET lastName = '$lastName' WHERE id = $userId";
            $executeQuery = mysqli_query($conn, $queryLast);
        }

        $bio = mysqli_real_escape_string($conn, $_POST["bio"]);

        if (strlen($bio) > 0) {
            $queryBio = "UPDATE users SET description = '$bio' WHERE id = $userId";
            $executeQuery = mysqli_query($conn, $queryBio);
        }

        $queryDetailsUser = "SELECT * FROM details_user WHERE id_user = $userId";
        $executeQuery = mysqli_query($conn, $queryDetailsUser);
        $fetchedDetails = null;

        // ask if have length > 0

        if (mysqli_num_rows($executeQuery) == 0) {
            $newDetailsUser = "INSERT INTO details_user (instagram,facebook,twitter,fondo,id_user) VALUES ('','','','','$userId')";
            $runNewDetailsUser = mysqli_query($conn, $newDetailsUser);
        } else {
            $fetchedDetails = mysqli_fetch_array($executeQuery);
        }

        $instagram = $fetchedDetails[1];
        $facebook = $fetchedDetails[2];
        $twitter = $fetchedDetails[3];
        $youtube = $fetchedDetails[6];
        $twitch = $fetchedDetails[7];

        $toggleComments = $_POST['data'];

        // Get user data

        $queryUser = "SELECT * FROM users WHERE id = $userId";
        $executeQuery = mysqli_query($conn, $queryUser);
        $fetchedUser = mysqli_fetch_array($executeQuery);

        // Get user details

        if ($_POST["instagram"] != "") {
            $instagram = mysqli_real_escape_string($conn, $_POST["instagram"]);
        }

        if ($_POST["facebook"] != "") {
            $facebook = mysqli_real_escape_string($conn, $_POST["facebook"]);
        }

        if ($_POST["twitter"] != "") {
            $twitter = mysqli_real_escape_string($conn, $_POST["twitter"]);
        }

        if ($_POST["youtube"] != "") {
            $youtube = mysqli_real_escape_string($conn, $_POST["youtube"]);
        }

        if ($_POST["twitch"] != "") {
            $twitch = mysqli_real_escape_string($conn, $_POST["twitch"]);
        }

        // Resto del código para el flujo normal
        // Hash password.
        $password = "";
        // length
        if (!empty($_POST["password"]) || $_POST["password"] != "" || strlen($_POST["password"]) > 0) {
            $password = md5($_POST["password"]);
        } else {
            $password = $fetchedUser[4];
        }

        // If images exist save them.

        $fondoImageName = $fetchedDetails[4];
        $bannerImageName = $fetchedUser[11];
        $iconImageName = $fetchedUser[5];

        $rawFondo = $_FILES['fondo'];

        if ($rawFondo['size'] > 0) {
            $fondoImageName = saveFile($_FILES['fondo'], "../images/Fondos/");
        }

        $rawBanner = $_FILES['banner'];

        if ($rawBanner['size'] > 0) {
            $bannerImageName = saveFile($_FILES['banner'], "../images/Banners/");
        }

        $rawImage = $_FILES['image'];

        if ($rawImage['size'] > 0) {
            $iconImageName = saveFile($_FILES['image'], "../images/Profiles/");
        }

        $insertQuery = "UPDATE `users` SET `password`='$password',`image`='$iconImageName', `toggledData` = '$toggleComments', `banner`='$bannerImageName' WHERE id = $userId";

        // Save details.

        $insertDetailsUser = "UPDATE details_user SET youtube = '$youtube', twitch = '$twitch', facebook = '$facebook', instagram = '$instagram', twitter = '$twitter', fondo='$fondoImageName' WHERE id_user = $userId";

        $runDetailsQuery = mysqli_query($conn, $insertDetailsUser);

        $runInsertQuery = mysqli_query($conn, $insertQuery);
        if (!$runInsertQuery) {
            echo "Error al intentar actualizar datos";
        } else {
            echo "Se actualizaron tus datos correctamente";
        }
    }
}

function saveFile($file, $dir)
{
    $image = $file;

    // image name
    // size
    // temporary name
    // type

    $imageName = $image['name'];
    $imageSize = $image['size'];
    $imageTempName = $image['tmp_name'];
    $imageType = $image['type'];

    // get image extension or type
    $explode = explode(".", $imageName);
    $lowercase = strtolower(end($explode));

    // image extension required
    $extension = ["png", "gif", "jpg", "jpeg"];

    // if extension not matched

    if (in_array($lowercase, $extension) == false) {
        echo "Este archivo de extensión no está permitido, elija JPG o PNG GIF.";
    } else { // random number
        $random = rand(999999999, 111111111);
        $time = time();
        // image unique name
        $uniqueImageName = $random . $time . $imageName;

        // save image
        move_uploaded_file($imageTempName, $dir . $uniqueImageName);

        return $uniqueImageName;
    }
}
