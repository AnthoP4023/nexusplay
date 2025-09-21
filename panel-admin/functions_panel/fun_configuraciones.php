<?php
require_once __DIR__ . '/../../config_db/database.php';

function obtenerDatosAdmin($admin_id) {
    global $conn;
    
    try {
        $stmt = $conn->prepare("SELECT username, email FROM usuarios WHERE id = ? AND tipo_user_id = 2");
        $stmt->bind_param("i", $admin_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result && $result->num_rows > 0) {
            $data = $result->fetch_assoc();
            return $data;
        }
        
        error_log("No se encontraron datos para admin_id: " . $admin_id);
        return [
            'username' => '',
            'email' => ''
        ];
        
    } catch (Exception $e) {
        error_log("Error en obtenerDatosAdmin: " . $e->getMessage());
        return [
            'username' => '',
            'email' => ''
        ];
    }
}

function obtenerFotoAdmin($admin_id) {
    global $conn;
    
    try {
        $stmt = $conn->prepare("SELECT imagen_perfil FROM usuarios WHERE id = ? AND tipo_user_id = 2");
        $stmt->bind_param("i", $admin_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result && $result->num_rows > 0) {
            $data = $result->fetch_assoc();
            $imagen = $data['imagen_perfil'];
            
            if (!empty($imagen) && $imagen !== 'default-avatar.png') {
                return '/nexusplay/images/users/' . $imagen;
            }
        }
        
        return '/nexusplay/images/users/default-avatar.png';
        
    } catch (Exception $e) {
        error_log("Error en obtenerFotoAdmin: " . $e->getMessage());
        return '/nexusplay/images/users/default-avatar.png';
    }
}

function actualizarInfoAdmin($admin_id, $username, $email, $current_password, $new_password) {
    global $conn;
    
    try {
        if (!empty($current_password) && !empty($new_password)) {
            $stmt = $conn->prepare("SELECT password FROM usuarios WHERE id = ?");
            $stmt->bind_param("i", $admin_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $user_data = $result->fetch_assoc();
            
            if (md5($current_password) !== $user_data['password']) {
                return ['success' => false, 'message' => 'Contraseña actual incorrecta'];
            }
            
            $hashed_password = md5($new_password);
            $stmt = $conn->prepare("UPDATE usuarios SET username = ?, email = ?, password = ? WHERE id = ?");
            $stmt->bind_param("sssi", $username, $email, $hashed_password, $admin_id);
        } else {
            $stmt = $conn->prepare("UPDATE usuarios SET username = ?, email = ? WHERE id = ?");
            $stmt->bind_param("ssi", $username, $email, $admin_id);
        }
        
        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Datos actualizados correctamente'];
        }
        
        return ['success' => false, 'message' => 'Error al actualizar los datos'];
        
    } catch (Exception $e) {
        error_log("Error en actualizarInfoAdmin: " . $e->getMessage());
        return ['success' => false, 'message' => 'Error interno del servidor'];
    }
}

function subirFotoAdmin($admin_id, $archivo) {
    $upload_dir = __DIR__ . '/../../images/users/';
    $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
    $max_size = 5 * 1024 * 1024;
    
    if (!in_array($archivo['type'], $allowed_types)) {
        return ['success' => false, 'message' => 'Tipo de archivo no permitido'];
    }
    
    if ($archivo['size'] > $max_size) {
        return ['success' => false, 'message' => 'El archivo es muy grande'];
    }
    
    $extension = pathinfo($archivo['name'], PATHINFO_EXTENSION);
    $filename = 'admin_' . $admin_id . '_' . time() . '.' . $extension;
    $filepath = $upload_dir . $filename;
    
    if (move_uploaded_file($archivo['tmp_name'], $filepath)) {
        global $conn;
        
        try {
            $stmt = $conn->prepare("UPDATE usuarios SET imagen_perfil = ? WHERE id = ?");
            $stmt->bind_param("si", $filename, $admin_id);
            
            if ($stmt->execute()) {
                return ['success' => true, 'message' => 'Foto actualizada correctamente'];
            }
            
            return ['success' => false, 'message' => 'Error al guardar en base de datos'];
            
        } catch (Exception $e) {
            error_log("Error en subirFotoAdmin: " . $e->getMessage());
            return ['success' => false, 'message' => 'Error interno del servidor'];
        }
    }
    
    return ['success' => false, 'message' => 'Error al subir la foto'];
}
?>