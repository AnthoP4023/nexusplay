<?php
    session_start();
    require_once '../config_DB/database.php';
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