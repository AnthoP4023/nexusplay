<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config_db/database.php';
require_once __DIR__ . '/../functions/fun_auth.php';

if (!isLoggedIn()) {
    header('Location: ../auth/login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

$password_message = '';
$password_message_type = '';

if (isset($_SESSION['password_message'])) {
    $password_message = $_SESSION['password_message'];
    $password_message_type = $_SESSION['password_message_type'];
    
    unset($_SESSION['password_message']);
    unset($_SESSION['password_message_type']);
}

try {
    $stmt = $conn->prepare("SELECT username, email, nombre, apellido, imagen_perfil, fecha_registro FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $user_data = $result->fetch_assoc();
        $_SESSION['username'] = $user_data['username'];

        $imagen_bd = $user_data['imagen_perfil'];
        
        if (!empty($imagen_bd) && $imagen_bd !== 'default-avatar.png') {
            $ruta_imagen = '/nexusplay/images/users/' . $imagen_bd;
            $ruta_fisica = $_SERVER['DOCUMENT_ROOT'] . $ruta_imagen;
            
            if (file_exists($ruta_fisica)) {
                $perfil_img = $ruta_imagen;
            } else {
                $perfil_img = '/nexusplay/images/users/default-avatar.png';
            }
        } else {
            $perfil_img = '/nexusplay/images/users/default-avatar.png';
        }

        $_SESSION['imagen_perfil'] = $perfil_img;

    } else {
        die("Usuario no encontrado.");
    }
} catch (mysqli_sql_exception $e) {
    die("Error en la consulta: " . $e->getMessage());
}
?>