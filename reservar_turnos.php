<?php
session_start();

// Verifica si el usuario está logueado
if (!isset($_SESSION['correo'])) {
    header('Location: login.php');
    exit();
}

// Inicializa la sesión de servicios si no existe (esto usualmente sería gestionado por el administrador)
if (!isset($_SESSION['servicios'])) {
    $_SESSION['servicios'] = [
        ['nombre_servicio' => 'Corte de cabello', 'valor_servicio' => 10.00],
        ['nombre_servicio' => 'Afeitado', 'valor_servicio' => 8.00],
        ['nombre_servicio' => 'Tinte', 'valor_servicio' => 15.00],
    ];
}

// Carga los servicios desde la sesión
$servicios = $_SESSION['servicios'];

// Ruta predeterminada para la foto (opcional para estilo)
$fotoPredeterminada = 'logo-01.jpg';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservar Turno</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #000;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .carnet {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(250, 248, 248, 0.993);
            padding: 20px;
            width: 90%;
            max-width: 400px;
            text-align: center;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        label {
            margin: 10px 0 5px;
            display: block;
        }

        input[type="text"],
        select,
        input[type="submit"] {
            width: 90%;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        input[type="submit"] {
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #218838;
        }

        .error {
            color: red;
            font-size: 14px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="carnet">
        <h1>Reservar Turno</h1>
        <form action="procesar_reserva.php" method="POST">
            <label for="nombre_cliente">Nombre del Cliente:</label>
            <input type="text" id="nombre_cliente" name="nombre_cliente" placeholder="Ingrese su nombre" required>

            <label for="nombre_servicio">Seleccione el Servicio:</label>
            <select id="nombre_servicio" name="nombre_servicio" required>
                <option value="" disabled selected>Seleccione un servicio</option>
                <?php foreach ($servicios as $servicio): ?>
                    <option value="<?= htmlspecialchars($servicio['nombre_servicio']) ?>">
                        <?= htmlspecialchars($servicio['nombre_servicio']) ?> - $<?= number_format($servicio['valor_servicio'], 2) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <input type="submit" value="Reservar">
        </form>
    </div>
</body>
</html>
