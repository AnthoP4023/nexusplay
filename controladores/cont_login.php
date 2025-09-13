<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../config_db/database.php';
require_once '../functions/fun_auth.php';

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
        $password_hash = md5($password);
        $query = "SELECT * FROM usuarios WHERE username = '$username' AND password = '$password_hash'";

        try {
            $result = $conn->query($query);

            if ($result && $result->num_rows > 0) {
                $user = $result->fetch_assoc();
                $_SESSION['user_id']   = $user['id'];
                $_SESSION['username']  = $user['username'];
                $_SESSION['user_type'] = $user['tipo_usuario'];

                header('Location: ../index.php');
                exit();
            } else {
                $error = 'Usuario o contraseña incorrectos';
            }
        } catch (mysqli_sql_exception $e) {
            die("Error en la consulta: " . $e->getMessage());
        }
    }
}

?>
