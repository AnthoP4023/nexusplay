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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']); // Definir password

    if (empty($username) || empty($password)) {
        $error = 'Complete todos los campos';
    } else {
        // VULNERABLE: Consulta SQL sin prepared statements
        $password_hash = md5($password);

        // Se agregan paréntesis para forzar errores de SQL cuando la inyección es incorrecta
        $query = "SELECT * FROM usuarios WHERE (username = '$username' OR email = '$username') AND password = '$password_hash'";

        // Ejecutar consulta vulnerable y capturar errores de SQL
        try {
            $result = $conn->query($query);
            
            if ($result && $result->num_rows > 0) {
                $user = $result->fetch_assoc();

                // Login exitoso
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['user_type'] = $user['tipo_usuario'];

                header('Location: ../index.php');
                exit();
            } else {
                $error = 'Usuario o contraseña incorrectos';
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
    <title>Login - NexusPlay</title>
    <link rel="stylesheet" href="../css/acc_cuent/login.css">
    <link rel="stylesheet" href="../css/acc_cuent/logo.css">
    <link rel="stylesheet" href="../css/acc_cuent/responsive.css">
</head>
<body>
    <div class="login-container">
        <!-- IZQUIERDA - LOGO Y LOGIN -->
        <div class="left-side">
            <!-- SECCIÓN DEL LOGO -->
            <div class="logo-section">
                <a href="../index.php" class="logo-top">
                    <img src="../images/Logo/img_logo.png" alt="Logo">
                    <span>NexusPlay</span>
                </a>
            </div>
            
            <!-- SECCIÓN DEL LOGIN -->
            <div class="login-section">
                <div class="form-center">
                    <h2>Iniciar Sesión</h2>
                    <?php if ($error): ?>
                        <div class="error"><?php echo $error; ?></div>
                    <?php endif; ?>
                    <form method="POST">
                        <input type="text" name="username" placeholder="👤 Usuario o Email" required>
                        <input type="password" name="password" placeholder="🔒 Contraseña">
                        <button type="submit">Iniciar Sesión</button>
                    </form>
                    <div class="links">
                        <a href="register.php">¿No tienes cuenta? Regístrate</a>
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