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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']); 

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