<?php
// include config file
include_once("config.php");

// if signup button clicked
if(isset($_POST['signup'])){
    if(empty($_POST['fname']) || empty($_POST['lname']) || empty($_POST['email']) || empty($_POST['password']) || empty($_POST['confirmPassword']) || empty($_FILES['image'])){
        echo "Todas las entradas son obligatorias";
    }else{   
    // first name
    $firstName = mysqli_real_escape_string($conn, $_POST["fname"]);
    // last name
    $lastName = mysqli_real_escape_string($conn, $_POST["lname"]);
    // email
    $email = mysqli_real_escape_string($conn, $_POST["email"]);

    // email validation
    $emailQuery = "SELECT * FROM `users` WHERE email = '{$email}'";
    $runEmailQuery = mysqli_query($conn,$emailQuery);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "El formato del correo electrónico no es válido.";
    } else{
            if($runEmailQuery){
                if (mysqli_num_rows($runEmailQuery) > 0) {
                    echo "El correo electrónico ya existe.";
                }else{
                    // check password length
                    if(strlen($_POST["password"]) < 8 || strlen($_POST["confirmPassword"]) < 8){
                        echo "Utiliza 8 o mas caracteres";
                    }else if($_POST["password"] != $_POST['confirmPassword']){
                        // match password
                        echo "Contraseña no coincidente";
                    }else{
                        // store password
                        $password = md5($_POST["password"]);

                        // profile image

                        if (isset($_POST["tmpimage"])) {
							$tmpfname = $_POST["tmpimage"];
							$image = file_get_contents($tmpfname);
							$image_type = mime_content_type($tmpfname);

							$fileinfo = pathinfo($tmpfname);
							$imageName = $fileinfo['basename'];

							// random number
							$random = rand(999999999,111111111);
							$time = time();
							// image unique name 
							$uniqueImageName = $random . $time . $imageName;

							file_put_contents("../images/Profiles/$uniqueImageName", $image);
						} else {
							// image name
							// size
							// temporary name
							// type
							$image = $_FILES['image'];
							$imageName = $image['name'];
							$imageSize = $image['size'];
							$imageTempName = $image['tmp_name'];
							$imageType = $image['type'];

							// get image extension or type
							$explode = explode(".", $imageName);
							$lowercase = strtolower(end($explode));

							// image extension required
							$extension = ["png","gif","jpg","jpeg"];

							// if extension not matched
							if (in_array($lowercase,$extension) == false) {
								echo "Este archivo de extensión no está permitido, elija JPG o PNG GIF.";
							} else {
								// random number
								$random = rand(999999999,111111111);
								$time = time();
								// image unique name 
								$uniqueImageName = $random . $time . $imageName;

								// save image
								move_uploaded_file($imageTempName, "../images/Profiles/" . $uniqueImageName);
							}
						}

						$usertoken = $_POST["token"];

						// user status
						$status = "Offline";

						if (isset($usertoken)) {
							$insertQuery = "
								INSERT INTO `users` (
									firstName,lastName,email,password,image,token,status
								)
								VALUES(
									'{$firstName}','{$lastName}','{$email}','{$password}','{$uniqueImageName}','{$usertoken}','{$status}'
								)
							";
						} else {
							$insertQuery = "
								INSERT INTO `users` (
									firstName,lastName,email,password,image,status
								)
								VALUES(
									'{$firstName}','{$lastName}','{$email}','{$password}','{$uniqueImageName}','{$status}'
								)
							";
						}

						$runInsertQuery = mysqli_query($conn, $insertQuery);
						if(!$runInsertQuery){
							echo "Error al introducir datos en la base de datos";
						}else{
							// Create details

							$idInsertedUser = $conn->insert_id;

							$detailsQuery = "INSERT INTO `details_user` (id_user) VALUES ('{$conn->insert_id}')";
							$runDetailsQuery = mysqli_query($conn, $detailsQuery);

						   // Start session

							session_start();

							// Store data in session variables

							$_SESSION["id"] = $idInsertedUser;
							$_SESSION["rol"] = "Usuario";
							// Redirect user to welcome page

							// update status
							$status = "Online";

							// status query
							$statusQuery = "UPDATE users SET status = '{$status}' WHERE id = '{$_SESSION["id"]}'";
							$runStatusQuery = mysqli_query($conn, $statusQuery);
							

							echo 'Exito';

						}
                    }
                }
            }
        }
    }
}
