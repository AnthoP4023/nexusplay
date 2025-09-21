<?php
require_once __DIR__ . '/../functions_panel/fun_configuraciones.php';
require_once __DIR__ . '/../functions_panel/fun_auth_panel.php';

if (!isPanelAdminLoggedIn()) {
    header('Location: panel_login.php');
    exit();
}

renewPanelSession();

$admin_id = $_SESSION['panel_admin_id'];

error_log("Admin ID: " . $admin_id);
error_log("Session username: " . ($_SESSION['panel_admin_username'] ?? 'NO SET'));
error_log("Session email: " . ($_SESSION['panel_admin_email'] ?? 'NO SET'));

$admin_data = obtenerDatosAdmin($admin_id);
error_log("Admin data obtenido: " . print_r($admin_data, true));

if (!$admin_data || empty($admin_data['username'])) {
    $admin_data = [
        'username' => $_SESSION['panel_admin_username'] ?? '',
        'email' => $_SESSION['panel_admin_email'] ?? ''
    ];
    error_log("Usando datos de sesión como fallback");
}

$admin_avatar = obtenerFotoAdmin($admin_id);
if (!$admin_avatar) {
    $admin_avatar = '/nexusplay/images/users/default-avatar.png';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if (isset($_POST['update_info'])) {
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $current_password = trim($_POST['current_password']);
        $new_password = trim($_POST['new_password']);
        
        if (empty($username) || empty($email)) {
            $_SESSION['config_message'] = 'Todos los campos son obligatorios';
            $_SESSION['config_type'] = 'error';
        } else {
            $resultado = actualizarInfoAdmin($admin_id, $username, $email, $current_password, $new_password);
            
            if ($resultado['success']) {
                $_SESSION['panel_admin_username'] = $username;
                $_SESSION['config_message'] = 'Información actualizada correctamente';
                $_SESSION['config_type'] = 'success';
                $admin_data = obtenerDatosAdmin($admin_id);
            } else {
                $_SESSION['config_message'] = $resultado['message'];
                $_SESSION['config_type'] = 'error';
            }
        }
        
        header('Location: configuraciones.php');
        exit();
    }
    
    if (isset($_POST['update_photo'])) {
        if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] === 0) {
            $resultado = subirFotoAdmin($admin_id, $_FILES['profile_photo']);
            
            if ($resultado['success']) {
                $_SESSION['config_message'] = 'Foto actualizada correctamente';
                $_SESSION['config_type'] = 'success';
                $admin_avatar = obtenerFotoAdmin($admin_id);
            } else {
                $_SESSION['config_message'] = $resultado['message'];
                $_SESSION['config_type'] = 'error';
            }
        } else {
            $_SESSION['config_message'] = 'Por favor selecciona una foto';
            $_SESSION['config_type'] = 'error';
        }
        
        header('Location: configuraciones.php');
        exit();
    }
}
?>