<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

// Habilitar reporte de errores de MySQLi (solo entorno de pruebas)
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if (isLoggedIn()) {
    header('Location: ../index.php');
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $terms_accepted = isset($_POST['terms']);

    if (empty($username) || empty($email) || empty($nombre) || empty($apellido) || empty($password) || empty($confirm_password)) {
        $error = 'Complete todos los campos';
    } elseif ($password !== $confirm_password) {
        $error = 'Las contraseñas no coinciden';
    } elseif (strlen($password) < 6) {
        $error = 'La contraseña debe tener al menos 6 caracteres';
    } elseif (!$terms_accepted) {
        $error = 'Debe aceptar los términos y condiciones';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'El email no es válido';
    } else {
        // Verificar si el usuario o email ya existen
        $check_query = "SELECT * FROM usuarios WHERE username = '$username' OR email = '$email'";
        
        try {
            $check_result = $conn->query($check_query);
            
            if ($check_result && $check_result->num_rows > 0) {
                $error = 'El usuario o email ya están registrados';
            } else {
                // VULNERABLE: Inserción sin prepared statements
                $password_hash = md5($password);
                $insert_query = "INSERT INTO usuarios (email, username, password, nombre, apellido, imagen_perfil, tipo_usuario, fecha_registro) 
                               VALUES ('$email', '$username', '$password_hash', '$nombre', '$apellido', 'default-avatar.png', 'normal', NOW())";
                
                if ($conn->query($insert_query)) {
                    $success = 'Usuario registrado exitosamente. Puedes iniciar sesión ahora.';
                } else {
                    $error = 'Error al registrar usuario';
                }
            }
        } catch (mysqli_sql_exception $e) {
            // Mostrar error de SQL directamente (solo pruebas)
            die("Error en la consulta: " . $e->getMessage());
        }
    }
}
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