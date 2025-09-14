<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../config_db/database.php';
require_once '../functions/fun_auth.php';

// Solo admin puede acceder
if (!isLoggedIn() || !isAdmin()) {
    header('Location: ../index.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Verificar mensajes de cambio de contraseña
$password_message = '';
$password_message_type = '';

if (isset($_SESSION['password_message'])) {
    $password_message = $_SESSION['password_message'];
    $password_message_type = $_SESSION['password_message_type'];
    
    // Limpiar mensajes de la sesión
    unset($_SESSION['password_message']);
    unset($_SESSION['password_message_type']);
}

try {
    $stmt = $conn->prepare("SELECT username, email, nombre, apellido, imagen_perfil, fecha_registro FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $admin_data = $result->fetch_assoc();

        $_SESSION['username'] = $admin_data['username'];

        // Manejo robusto de la imagen de perfil
        $imagen_bd = $admin_data['imagen_perfil'];
        
        if (!empty($imagen_bd) && $imagen_bd !== 'default-avatar.png') {
            // Si tiene una imagen específica, verificar si existe
            $ruta_imagen = '/prueba5/images/users/' . $imagen_bd;
            $ruta_fisica = $_SERVER['DOCUMENT_ROOT'] . $ruta_imagen;
            
            if (file_exists($ruta_fisica)) {
                $perfil_img = $ruta_imagen;
            } else {
                // Si el archivo no existe, usar imagen por defecto
                $perfil_img = '/prueba5/images/users/default-avatar.png';
            }
        } else {
            // Si no tiene imagen o es la por defecto
            $perfil_img = '/prueba5/images/users/default-avatar.png';
        }

        // Actualizar la sesión con la imagen correcta
        $_SESSION['imagen_perfil'] = $perfil_img;

    } else {
        die("Usuario no encontrado.");
    }
} catch (mysqli_sql_exception $e) {
    die("Error en la consulta: " . $e->getMessage());
}
?>