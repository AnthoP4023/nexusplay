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
    <?php 
    include("../controladores/cont_register.php"); 
    ?>
    
        <!-- IZQUIERDA - LOGO Y REGISTRO -->
        <div class="left-side">
            <!-- SECCIÓN DEL LOGO -->
            <div class="logo-section">
                <a href="../index.php" class="logo-top">
                    <img src="../images/Logo/img_logo.png" alt="Logo">
                    <span>NexusPlay</span>
                </a>
            </div>
            
            <!-- SECCIÓN DEL REGISTRO -->
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
                        <input type="email" name="email" placeholder="📧 Correo electrónico" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
                        <input type="text" name="username" placeholder="🎮 Nombre de usuario" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" required>
                        <input type="text" name="nombre" placeholder="👤 Nombre" value="<?php echo isset($_POST['nombre']) ? htmlspecialchars($_POST['nombre']) : ''; ?>" required>
                        <input type="text" name="apellido" placeholder="👤 Apellido" value="<?php echo isset($_POST['apellido']) ? htmlspecialchars($_POST['apellido']) : ''; ?>" required>
                        <input type="password" name="password" placeholder="🔒 Contraseña" required>
                        <input type="password" name="confirm_password" placeholder="🔒 Confirmar contraseña" required>
                        
                        <!-- Checkbox de términos y condiciones -->
                        <div class="terms-container">
                            <input type="checkbox" id="terms" name="terms" required>
                            <label for="terms">
                                Estoy de acuerdo con los <a href="../pages/terms.php" target="_blank">Terms</a> 
                                and <a href="../pages/privacy.php" target="_blank">Privacy policy</a>
                            </label>
                        </div>
                        
                        <button type="submit">Registrarse</button>
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