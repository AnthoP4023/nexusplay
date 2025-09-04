<?php 
require_once '../config_DB/database.php';
require_once '../controladores/functions.php';
include("../controladores/cont_register.php"); 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - NexusPlay</title>
    <link rel="stylesheet" href="../css/acc_cuent/login.css">
    <link rel="stylesheet" href="../css/acc_cuent/logo.css">
    <link rel="stylesheet" href="../css/acc_cuent/responsive.css">
    <link rel="stylesheet" href="../css/acc_cuent/register.css">
</head>
<body>
    <div class="login-container">

        <!-- IZQUIERDA - LOGO Y FORMULARIO -->
        <div class="left-side">
            <div class="logo-section">
                <a href="../index.php" class="logo-top">
                    <img src="../images/Logo/img_logo.png" alt="Logo">
                    <span>NexusPlay</span>
                </a>
            </div>

            <div class="login-section">
                <div class="form-center">
                    <h2>Crear Cuenta</h2>

                    <?php if ($error): ?>
                        <div class="error"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <?php if ($success): ?>
                        <div class="success"><?php echo $success; ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <input type="email" name="correo_elect" placeholder="📧 Correo electrónico" required>
                        <input type="text" name="nombre_usuario" placeholder="🎮 Nombre de usuario" required>
                        <input type="text" name="nombres" placeholder="👤 Nombre" required>
                        <input type="text" name="apellidos" placeholder="👤 Apellido" required>
                        <input type="password" name="clave" placeholder="🔒 Contraseña" required>
                        <input type="password" name="confirm_clave" placeholder="🔒 Confirmar contraseña" required>

                        <div class="terms-container">
                            <input type="checkbox" id="terms" name="terms" required>
                            <label for="terms">
                                Estoy de acuerdo con los <a href="../pages/terms.php" target="_blank">Terms</a> 
                                y <a href="../pages/privacy.php" target="_blank">Privacy policy</a>
                            </label>
                        </div>

                        <button type="submit" name="registro">Registrarse</button>
                    </form>

                    <div class="links">
                        <a href="login.php">¿Ya tienes cuenta? Inicia sesión</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- DERECHA - IMAGEN -->
        <div class="right-side">
            <a href="../index.php" class="close-btn">
                <span>&times;</span>
            </a>
            <img src="../images/Logo/img_login.jpg" alt="Gaming">
        </div>

    </div>
</body>
</html>
