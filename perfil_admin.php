<?php
session_start();
if (!isset($_SESSION['correo']) || $_SESSION['rol'] !== 'administrador') {
    header("Location: index.html"); // Redirige al login si no es admin
    exit();
}

// Inicializa las listas de servicios y turnos desde la sesión si existen
$servicios = $_SESSION['servicios'] ?? [];
$turnos = $_SESSION['turnos'] ?? [];

// Procesar acciones del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    // Agregar o editar un servicio
    if ($_POST['action'] === 'add_or_edit') {
        $nombre_servicio = $_POST['nombre_servicio'];
        $descripcion_servicio = $_POST['descripcion_servicio'];
        $valor_servicio = floatval($_POST['valor_servicio']); // Convertir a float el valor del servicio

        if (isset($_POST['edit_index']) && $_POST['edit_index'] !== '') {
            // Editar el servicio existente
            $index = intval($_POST['edit_index']);
            if (isset($servicios[$index])) {
                $servicios[$index] = [
                    'nombre_servicio' => $nombre_servicio,
                    'descripcion_servicio' => $descripcion_servicio,
                    'valor_servicio' => $valor_servicio,
                ];
                $_SESSION['message'] = "Servicio actualizado con éxito.";
            }
        } else {
            // Agregar un nuevo servicio
            $servicios[] = [
                'nombre_servicio' => $nombre_servicio,
                'descripcion_servicio' => $descripcion_servicio,
                'valor_servicio' => $valor_servicio,
            ];
            $_SESSION['message'] = "Servicio agregado con éxito.";
        }

        $_SESSION['servicios'] = $servicios;
    }

    // Limpiar datos de los turnos reservados
    if ($_POST['action'] === 'clear_turnos') {
        $turnos = [];
        $_SESSION['turnos'] = $turnos;
        $_SESSION['message'] = "Turnos reservados limpiados exitosamente.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil Administrador</title>
    <link rel="stylesheet" href="admin.css">
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script&display=swap" rel="stylesheet">

    <style>
        /* Aquí puedes agregar el CSS existente */
    </style>
</head>

<body>
    <div class="perfil-container">

        <!-- Formulario para agregar o editar servicio -->
        <div class="form-container">
         

            <h2 id="form-title">Agregar Servicio</h2>
            <form method="POST" action="">
                <label for="nombre_servicio">Nombre del servicio:</label>
                <input type="text" id="nombre_servicio" name="nombre_servicio" required>

                <label for="descripcion_servicio">Descripción:</label>
                <input type="text" id="descripcion_servicio" name="descripcion_servicio" required>

                <label for="valor_servicio">Valor del servicio ($):</label>
                <input type="number" step="0.01" id="valor_servicio" name="valor_servicio" required>

                <input type="hidden" name="action" value="add_or_edit">
                <input type="hidden" id="edit_index" name="edit_index" value="">
                <button type="submit" id="form-submit-btn">Agregar Servicio</button>
            </form>

            <!-- Botón Limpiar Turnos Reservados -->
            <form id="clear-turnos-form" method="POST" action="" style="display:inline;">
                <input type="hidden" name="action" value="clear_turnos">
                <button type="button" id="clear-turnos-btn" class="logout-btn">Limpiar Turnos Reservados</button>
            </form>

            <!-- Formulario para cerrar sesión -->
            <form method="POST" action="logout.php" style="text-align: center;">
                <button type="submit" class="logout-btn">Cerrar sesión</button>
            </form>
        </div>

        <!-- Acordeón para Servicios y Turnos -->
        <div class="accordion-container">
            <!-- Servicios -->
            <div class="accordion-item">
                <div class="accordion-header">Servicios Disponibles para Clientes</div>
                <div class="accordion-content">
                    <?php if (!empty($servicios)): ?>
                        <ul>
                            <?php foreach ($servicios as $index => $servicio): ?>
                                <li>
                                    <?= htmlspecialchars($servicio['nombre_servicio']) ?> -
                                    <?= htmlspecialchars($servicio['descripcion_servicio']) ?> -
                                    $<?= number_format($servicio['valor_servicio'], 2) ?>
                                    <button class="edit-btn" data-index="<?= $index ?>"
                                        data-nombre="<?= htmlspecialchars($servicio['nombre_servicio']) ?>"
                                        data-descripcion="<?= htmlspecialchars($servicio['descripcion_servicio']) ?>"
                                        data-valor="<?= $servicio['valor_servicio'] ?>">Editar</button>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p>No hay servicios disponibles.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Turnos -->
            <div class="accordion-item">
                <div class="accordion-header">Turnos Reservados</div>
                <div class="accordion-content">
                    <?php if (!empty($turnos)): ?>
                        <ul>
                            <?php foreach ($turnos as $turno): ?>
                                <li>Cliente: <?= htmlspecialchars($turno['nombre_cliente']) ?> - Servicio:
                                    <?= htmlspecialchars($turno['nombre_servicio']) ?> - Valor:
                                    $<?= number_format($turno['valor_servicio'], 2) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p>No hay turnos reservados.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Acordeón para expandir o colapsar secciones
        document.querySelectorAll('.accordion-header').forEach(header => {
            header.addEventListener('click', () => {
                const content = header.nextElementSibling;
                content.classList.toggle('open');
                header.style.backgroundColor = content.classList.contains('open') ? '#0056b3' : '#007bff';
            });
        });

        // Confirmación antes de limpiar los turnos
        document.getElementById('clear-turnos-btn').addEventListener('click', () => {
            if (confirm("¿Estás seguro de que deseas limpiar los turnos reservados?")) {
                document.getElementById('clear-turnos-form').submit();
            }
        });

        // Mostrar datos del servicio en el formulario principal al editar
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', () => {
                const index = button.dataset.index;
                const nombre = button.dataset.nombre;
                const descripcion = button.dataset.descripcion;
                const valor = button.dataset.valor;

                document.getElementById('nombre_servicio').value = nombre;
                document.getElementById('descripcion_servicio').value = descripcion;
                document.getElementById('valor_servicio').value = valor;
                document.getElementById('edit_index').value = index;

                document.getElementById('form-title').textContent = "Editar Servicio";
                document.getElementById('form-submit-btn').textContent = "Guardar Cambios";
            });
        });
    </script>
</body>

</html>