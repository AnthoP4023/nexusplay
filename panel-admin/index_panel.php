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
    <link rel="stylesheet" href="assests/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="css_panel/panel_index.css">
</head>
<body>
    <div class="panel-container">
        <h1>Bienvenido al Panel de Administrador</h1>
        <div class="welcome-message">
            <p>Hola, <?php echo htmlspecialchars($_SESSION['panel_admin_username']); ?></p>
            <p>Has iniciado sesión exitosamente en el panel de administrador.</p>
        </div>
        
        <a href="panel_logout.php" class="logout-btn">
            <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
        </a>
    </div>
</body>
</html>






