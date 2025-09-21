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
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            background: #f5f5f5;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .panel-container {
            text-align: center;
            padding: 40px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .welcome-message {
            color: #333;
            margin-bottom: 20px;
        }
        
        .logout-btn {
            background: #dc3545;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
        }
        
        .logout-btn:hover {
            background: #c82333;
        }
    </style>
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