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

if (isAdmin()) {
    header('Location: /nexusplay/profile/admin/admin.php');
    exit();
}

$user_id = $_SESSION['user_id'];

$password_message = '';
$password_message_type = '';
$profile_message = '';
$profile_message_type = '';

if (isset($_SESSION['password_message'])) {
    $password_message = $_SESSION['password_message'];
    $password_message_type = $_SESSION['password_message_type'];
    
    unset($_SESSION['password_message']);
    unset($_SESSION['password_message_type']);
}

if (isset($_SESSION['profile_message'])) {
    $profile_message = $_SESSION['profile_message'];
    $profile_message_type = $_SESSION['profile_message_type'];
    
    unset($_SESSION['profile_message']);
    unset($_SESSION['profile_message_type']);
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

    $stmt_stats = $conn->prepare("
        SELECT 
            COUNT(p.id) as total_pedidos,
            SUM(CASE WHEN p.estado = 'completado' THEN p.total ELSE 0 END) as total_gastado
        FROM pedidos p 
        WHERE p.usuario_id = ?
    ");
    $stmt_stats->bind_param("i", $user_id);
    $stmt_stats->execute();
    $stats_result = $stmt_stats->get_result();
    $stats = $stats_result->fetch_assoc();

    $stmt_cartera = $conn->prepare("SELECT saldo FROM carteras WHERE usuario_id = ?");
    $stmt_cartera->bind_param("i", $user_id);
    $stmt_cartera->execute();
    $cartera_result = $stmt_cartera->get_result();
    $cartera = $cartera_result->fetch_assoc();
    $saldo_cartera = $cartera ? $cartera['saldo'] : 0;

} catch (mysqli_sql_exception $e) {
    die("Error en la consulta: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if (isset($_POST['change_password'])) {
        $current_password = trim($_POST['current_password']);
        $new_password = trim($_POST['new_password']);
        $confirm_password = trim($_POST['confirm_password']);
        
        if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
            $password_message = 'Todos los campos de contraseña son obligatorios';
            $password_message_type = 'error';
        } elseif ($new_password !== $confirm_password) {
            $password_message = 'Las contraseñas nuevas no coinciden';
            $password_message_type = 'error';
        } elseif (strlen($new_password) < 6) {
            $password_message = 'La nueva contraseña debe tener al menos 6 caracteres';
            $password_message_type = 'error';
        } else {
            try {
                $stmt = $conn->prepare("SELECT password FROM usuarios WHERE id = ?");
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $result = $stmt->get_result();
                
                if ($result && $result->num_rows > 0) {
                    $user = $result->fetch_assoc();
                    $current_password_hash = md5($current_password);
                    
                    if ($current_password_hash === $user['password']) {
                        $new_password_hash = md5($new_password);
                        $update_stmt = $conn->prepare("UPDATE usuarios SET password = ? WHERE id = ?");
                        $update_stmt->bind_param("si", $new_password_hash, $user_id);
                        
                        if ($update_stmt->execute()) {
                            $password_message = 'Contraseña cambiada exitosamente';
                            $password_message_type = 'success';
                        } else {
                            $password_message = 'Error al actualizar la contraseña';
                            $password_message_type = 'error';
                        }
                    } else {
                        $password_message = 'La contraseña actual es incorrecta';
                        $password_message_type = 'error';
                    }
                } else {
                    $password_message = 'Usuario no encontrado';
                    $password_message_type = 'error';
                }
            } catch (mysqli_sql_exception $e) {
                $password_message = 'Error en la base de datos';
                $password_message_type = 'error';
            }
        }
    }
    
    if (isset($_POST['update_profile'])) {
        $new_username = trim($_POST['username']);
        $new_email = trim($_POST['email']);
        $new_nombre = trim($_POST['nombre']);
        $new_apellido = trim($_POST['apellido']);
        
        if (empty($new_username) || empty($new_email) || empty($new_nombre) || empty($new_apellido)) {
            $profile_message = 'Todos los campos del perfil son obligatorios';
            $profile_message_type = 'error';
        } elseif (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
            $profile_message = 'El email no es válido';
            $profile_message_type = 'error';
        } else {
            try {
                $check_stmt = $conn->prepare("SELECT id FROM usuarios WHERE (username = ? OR email = ?) AND id != ?");
                $check_stmt->bind_param("ssi", $new_username, $new_email, $user_id);
                $check_stmt->execute();
                $check_result = $check_stmt->get_result();
                
                if ($check_result && $check_result->num_rows > 0) {
                    $profile_message = 'El nombre de usuario o email ya están en uso';
                    $profile_message_type = 'error';
                } else {
                    $update_stmt = $conn->prepare("UPDATE usuarios SET username = ?, email = ?, nombre = ?, apellido = ? WHERE id = ?");
                    $update_stmt->bind_param("ssssi", $new_username, $new_email, $new_nombre, $new_apellido, $user_id);
                    
                    if ($update_stmt->execute()) {
                        $profile_message = 'Perfil actualizado exitosamente';
                        $profile_message_type = 'success';
                        
                        $_SESSION['username'] = $new_username;
                        $user_data['username'] = $new_username;
                        $user_data['email'] = $new_email;
                        $user_data['nombre'] = $new_nombre;
                        $user_data['apellido'] = $new_apellido;
                    } else {
                        $profile_message = 'Error al actualizar el perfil';
                        $profile_message_type = 'error';
                    }
                }
            } catch (mysqli_sql_exception $e) {
                $profile_message = 'Error en la base de datos';
                $profile_message_type = 'error';
            }
        }
    }
}
?>