<?php
session_start();

// Simulación de usuarios almacenados (puedes reemplazar esto con una base de datos)
$usuarios = [
    'admin' => ['password' => '2025**', 'rol' => 'administrador', 'correo' => 'admin@example.com'],
    'user' => ['password' => '5678', 'rol' => 'usuario', 'correo' => 'user@example.com']
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validar usuario
    if (isset($usuarios[$username]) && $usuarios[$username]['password'] === $password) {
        $_SESSION['correo'] = $usuarios[$username]['correo'];
        $_SESSION['rol'] = $usuarios[$username]['rol'];

        // Redirigir según el rol
        if ($_SESSION['rol'] === 'administrador') {
            header("Location: perfil_admin.php");
        } else {
            header("Location: user_dashboard.php"); // Redirige a un panel de usuario
        }
        exit();
    } else {
        // Credenciales inválidas
        $_SESSION['message'] = "Nombre de usuario o contraseña incorrectos.";
        header("Location: index.html");
        exit();
    }
}
?>
