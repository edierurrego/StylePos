<?php
session_start();

// Inicializa la sesión de turnos si no existe
if (!isset($_SESSION['turnos'])) {
    $_SESSION['turnos'] = [];
}

// Verifica si hay servicios disponibles en la sesión
$servicios = $_SESSION['servicios'] ?? [];

// Obtén los datos del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_cliente = $_POST['nombre_cliente'] ?? '';
    $nombre_servicio = $_POST['nombre_servicio'] ?? '';

    // Encuentra el valor del servicio seleccionado
    $valor_servicio = 0;
    foreach ($servicios as $servicio) {
        if ($servicio['nombre_servicio'] === $nombre_servicio) {
            $valor_servicio = $servicio['valor_servicio'];
            break;
        }
    }

    // Valida los datos
    if (!empty($nombre_cliente) && !empty($nombre_servicio)) {
        // Guarda la reserva en la sesión
        $_SESSION['turnos'][] = [
            'nombre_cliente' => htmlspecialchars($nombre_cliente),
            'nombre_servicio' => htmlspecialchars($nombre_servicio),
            'valor_servicio' => number_format($valor_servicio, 2),
            
        ];

        // Mensaje de confirmación y redirección
        echo "
        <script>
            alert('Reserva realizada con éxito.');
            window.location.href = 'index.html';  // Redirige a la página principal
        </script>";
        exit();
    } else {
        // Mensaje de error y redirección al formulario
        echo "
        <script>
            alert('Error: Todos los campos son obligatorios.');
            window.location.href = 'reservar_turnos.php';  // Redirige de vuelta al formulario
        </script>";
        exit();
    }
} else {
    // Si el formulario no se envió mediante POST, redirige a la página de reserva
    header('Location: reservar_turnos.php');
    exit();
}
?>
