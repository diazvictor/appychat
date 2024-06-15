<?php
include_once("php/config.php");
session_start();
if (!isset($_SESSION['id'])) {
    header("location: home");
}

$userid = $_GET['userid'];

// Si tiene membresia, redirigir a home

$membershipQuery = "SELECT * FROM `membresias` WHERE id_user = '{$userid}'";
$runMembershipQuery = mysqli_query($conn, $membershipQuery);

if (mysqli_num_rows($runMembershipQuery) > 0) {
    header("location: users");
}
?>

<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Precios AppyChat</title>
    <link rel="stylesheet" href="css/precios.css">
    <link rel="shortcut icon" href="assets/AppyChatBienvenida.jpeg" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css">
</head>

<body>
    <section class="section">
        <div class="container">
            <h1>Membresia de Club AppyChat</h1>
            <div class="row">
                <div class="col-md-4 p-md-0">
                    <div class="table-default table1 grad1">
                        <div>
                            <h2 class="table_header_title">Mes</h2>
                            <p data-price="10" class="table_header_price">
                                <span>💸</span> 10 USD
                            </p>
                        </div>
                        <div class="table_content">
                            <ul class="table_content_list">
                                <li>Obten Aspectos Exclusivos para Tu perfil</li>
                                <li>Recibes una Insignia por ser parte del Club</li>
                                <li>Obten mas de 50 marcos para decorar tu perfil en AppyChat</li>
                                <li>Obten marcos de colaboraciones limitadas AppyChat</li>

                            </ul>
                        </div>
                        <div class="table_footer">
                            <input type="hidden" value="10" id="monthlyPlan">
                            <a href="#" class="button" value="10">Comprar</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 p-md-0">
                    <div class="table-default table1 grad2 recommeded">
                        <div>
                            <h2 class="table_header_title">Trimestral</h2>
                            <p data-price="30" class="table_header_price">
                                <span>💸</span> 30 USD
                            </p>
                        </div>
                        <div class="table_content">
                            <ul class="table_content_list">
                                <li>Obten Aspectos Exclusivos para Tu perfil</li>
                                <li>Recibes una Insignia por ser parte del Club</li>
                                <li>Obten mas de 50 marcos para decorar tu perfil en AppyChat</li>
                                <li>Obten marcos de colaboraciones limitadas AppyChat</li>

                            </ul>
                        </div>
                        <div class="table_footer">
                            <input type="hidden" value="30" id="quarterlyPlan">
                            <a href="#" class="button" value="30">Comprar</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 p-md-0">
                    <div class="table-default table1 grad3">
                        <div>
                            <h2 class="table_header_title">Año</h2>
                            <p data-price="100" class="table_header_price">
                                <span>💸</span> 100 USD
                            </p>
                        </div>
                        <div class="table_content">
                            <ul class="table_content_list">
                                <li>Obten Aspectos Exclusivos para Tu perfil</li>
                                <li>Recibes una Insignia por ser parte del Club</li>
                                <li>Obten mas de 50 marcos para decorar tu perfil en AppyChat</li>
                                <li>Obten marcos de colaboraciones limitadas AppyChat</li>
                            </ul>
                        </div>
                        <div class="table_footer">
                            <input type="hidden" value="100" id="annuallyPlan">
                            <a href="#" class="button">Comprar</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>

    <div class="carrito mx-auto w-75 mt-5 text-center d-flex flex-column gap-3 p-2 justify-content-center">
        <input type="hidden" value="<?= $userid ?>" id="usuario">
        <h4 class="text-center total">Total a pagar: </h4>
        <div id="paypal-button-container" class="w-25 mx-auto"></div>
    </div>

    <!-- Include the PayPal JavaScript SDK -->
    <script src="https://www.paypal.com/sdk/js?client-id=Aea72gTyAjcT-8bNLJYaDSYfnnUZV3-A5bUyBhOerYbhC4SYKvCj4fK4aMcoPhPFm2TpigmHtGbMpu-g&currency=USD"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="sweetalert2.all.min.js"></script>
    <script>
        let button = document.querySelectorAll('.button');
        let precio = 0;
        let cantidad = 0;
        let usuario = document.querySelector('#usuario').value;
        let total = precio * cantidad;

        button.forEach((btn) => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                precio = e.target.parentElement.children[0].value;
                cantidad = 1;
                total = precio * cantidad;
                document.querySelector('.total').innerHTML = `
                <h4 class="text-center">Total a pagar: ${total} USD</h4>
                `;
            })
        })
        // Render the PayPal button into #paypal-button-container
        paypal.Buttons({

            // Call your server to set up the transaction
            createOrder: function(data, actions) {

                // Create toast if total == 0

                if (total == 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'No has seleccionado un plan',
                        footer: '<a href="prices.php">Selecciona un plan</a>'
                    })
                }

                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: precio * cantidad, // Total de la transacción
                            currency_code: 'USD' // Puedes cambiar esto según tu moneda
                        }
                    }]
                });
            },

            // Call your server to finalize the transaction
            onApprove: function(data, actions) {
                return actions.order.capture().then(async function(details) {
                    // Lógica para manejar la aprobación de la transacción
                    await fetch("php/regalar.php", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                            },
                            body: JSON.stringify({
                                price: total,
                                user: usuario
                            }),
                        })
                        .then((response) => response.json())
                        .then((data) => {
                            Swal.fire({
                                icon: 'success',
                                title: '¡Gracias por tu compra!',
                                text: data.message,
                                footer: '<a href="users">Volver al inicio</a>'
                            })
                            console.log(data);
                        }).catch(error => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Algo salio mal, contacta a la administracion si tu pago se debito.',
                                footer: '<a href="users">Volver al inicio</a>'
                            })
                        })
                });
            }

        }).render('#paypal-button-container');
    </script>
</body>

</html>