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

try {
    $stmt = $conn->prepare("SELECT username, email, nombre, apellido, imagen_perfil, fecha_registro FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $admin_data = $result->fetch_assoc();

        $_SESSION['username'] = $admin_data['username'];

        // ✅ Ruta completa para la imagen
        $_SESSION['imagen_perfil'] = !empty($admin_data['imagen_perfil']) 
            ? '/prueba5/images/users/' . $admin_data['imagen_perfil'] 
            : '/prueba5/images/users/default-avatar.png';

    } else {
        die("Usuario no encontrado.");
    }
} catch (mysqli_sql_exception $e) {
    die("Error en la consulta: " . $e->getMessage());
}
?>
