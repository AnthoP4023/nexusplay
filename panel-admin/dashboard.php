<?php
session_start();

require_once __DIR__ . '/functions_panel/fun_auth_panel.php';

if (!isPanelAdminLoggedIn()) {
    header('Location: panel_login.php');
    exit();
}

renewPanelSession();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administrador - NexusPlay</title>
    <link rel="stylesheet" href="/nexusplay/assests/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="css_panel/header.css">
    <link rel="stylesheet" href="css_panel/panel_index.css">
</head>
<body>
    <!-- Incluir el header/sidebar -->
    <?php include 'header.php'; ?>
    
    <!-- Contenido principal -->
    <main class="main-content">
        <div class="panel-container">
            <h1>Bienvenido al Panel de Administrador</h1>
            <div class="welcome-message">
                <p>Hola, <?php echo htmlspecialchars($_SESSION['panel_admin_username']); ?></p>
                <p>Has iniciado sesión exitosamente en el panel de administrador.</p>
            </div>
        </div>
    </main>
</body>
</html>