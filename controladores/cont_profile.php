<?php
// cont_profile.php - Controlador del perfil de usuario

// Variables para mensajes
$success_message = '';
$error_message = '';

// Obtener datos del usuario actual
function getUserData($conn, $user_id) {
    $query = "SELECT * FROM usuarios WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Obtener pedidos del usuario
function getUserOrders($conn, $user_id) {
    $query = "SELECT * FROM pedidos WHERE usuario_id = ? ORDER BY fecha_pedido DESC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Obtener detalles de un pedido
function getOrderDetails($conn, $pedido_id) {
    $query = "SELECT dp.*, j.titulo, j.imagen 
              FROM detalles_pedido dp 
              JOIN juegos j ON dp.juego_id = j.id 
              WHERE dp.pedido_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $pedido_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Obtener tarjetas del usuario (con desencriptación)
function getUserCards($conn, $user_id) {
    $query = "SELECT id, numero_tarjeta, fecha_expiracion, alias, fecha_registro 
              FROM tarjetas WHERE usuario_id = ? ORDER BY fecha_registro DESC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Función para desencriptar número de tarjeta (simulada)
function decryptCardNumber($encrypted_number) {
    // En producción, usar AES_DECRYPT con la clave real
    // Por ahora simulamos con números genéricos
    $sample_cards = [
        '4532123456789012',
        '5555444433332222', 
        '4111111111111111',
        '5105105105105100',
        '4242424242424242'
    ];
    return $sample_cards[array_rand($sample_cards)];
}

// Obtener tipo de tarjeta basado en número
function getCardBrand($card_number) {
    $first_digit = substr($card_number, 0, 1);
    $first_two = substr($card_number, 0, 2);
    
    if ($first_digit === '4') return 'Visa';
    if (in_array($first_two, ['51', '52', '53', '54', '55'])) return 'Mastercard';
    if (in_array($first_two, ['34', '37'])) return 'Amex';
    if ($first_two === '60') return 'Discover';
    
    return 'Unknown';
}

// Obtener reseñas del usuario
function getUserReviews($conn, $user_id) {
    $query = "SELECT r.*, j.titulo, j.imagen 
              FROM resenas r 
              JOIN juegos j ON r.juego_id = j.id 
              WHERE r.usuario_id = ? 
              ORDER BY r.fecha_resena DESC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Funciones para administradores
function getAdminStats($conn) {
    $stats = [];
    
    // Total usuarios
    $result = $conn->query("SELECT COUNT(*) as total FROM usuarios");
    $stats['total_usuarios'] = $result->fetch_assoc()['total'];
    
    // Total juegos
    $result = $conn->query("SELECT COUNT(*) as total FROM juegos");
    $stats['total_juegos'] = $result->fetch_assoc()['total'];
    
    // Total pedidos
    $result = $conn->query("SELECT COUNT(*) as total FROM pedidos");
    $stats['total_pedidos'] = $result->fetch_assoc()['total'];
    
    // Ingresos totales
    $result = $conn->query("SELECT SUM(total) as ingresos FROM pedidos WHERE estado = 'completado'");
    $stats['ingresos_totales'] = $result->fetch_assoc()['ingresos'] ?? 0;
    
    // Pedidos pendientes
    $result = $conn->query("SELECT COUNT(*) as total FROM pedidos WHERE estado = 'pendiente'");
    $stats['pedidos_pendientes'] = $result->fetch_assoc()['total'];
    
    // Total reseñas
    $result = $conn->query("SELECT COUNT(*) as total FROM resenas");
    $stats['total_reseñas'] = $result->fetch_assoc()['total'];
    
    // Actividad reciente
    $stats['actividad_reciente'] = getRecentActivity($conn);
    
    return $stats;
}

function getRecentActivity($conn) {
    $activities = [];
    
    // Últimos usuarios registrados
    $query = "SELECT username, fecha_registro FROM usuarios 
              ORDER BY fecha_registro DESC LIMIT 3";
    $result = $conn->query($query);
    while ($user = $result->fetch_assoc()) {
        $activities[] = [
            'icon' => 'fas fa-user-plus',
            'descripcion' => "Nuevo usuario registrado: " . $user['username'],
            'fecha' => $user['fecha_registro']
        ];
    }
    
    // Últimos pedidos
    $query = "SELECT p.id, u.username, p.fecha_pedido 
              FROM pedidos p 
              JOIN usuarios u ON p.usuario_id = u.id 
              ORDER BY p.fecha_pedido DESC LIMIT 3";
    $result = $conn->query($query);
    while ($pedido = $result->fetch_assoc()) {
        $activities[] = [
            'icon' => 'fas fa-shopping-cart',
            'descripcion' => "Nuevo pedido #" . $pedido['id'] . " de " . $pedido['username'],
            'fecha' => $pedido['fecha_pedido']
        ];
    }
    
    // Últimas reseñas
    $query = "SELECT r.puntuacion, u.username, j.titulo, r.fecha_resena
              FROM resenas r
              JOIN usuarios u ON r.usuario_id = u.id
              JOIN juegos j ON r.juego_id = j.id
              ORDER BY r.fecha_resena DESC LIMIT 3";
    $result = $conn->query($query);
    while ($review = $result->fetch_assoc()) {
        $activities[] = [
            'icon' => 'fas fa-star',
            'descripcion' => $review['username'] . " reseñó " . $review['titulo'] . " (" . $review['puntuacion'] . " estrellas)",
            'fecha' => $review['fecha_resena']
        ];
    }
    
    // Ordenar por fecha
    usort($activities, function($a, $b) {
        return strtotime($b['fecha']) - strtotime($a['fecha']);
    });
    
    return array_slice($activities, 0, 10);
}

function getAllUsers($conn) {
    $query = "SELECT id, username, email, nombre, apellido, imagen_perfil, tipo_usuario, fecha_registro 
              FROM usuarios ORDER BY fecha_registro DESC";
    $result = $conn->query($query);
    return $result->fetch_all(MYSQLI_ASSOC);
}

function getAllGames($conn) {
    $query = "SELECT j.*, p.nombre as plataforma, c.nombre as categoria 
              FROM juegos j 
              LEFT JOIN plataformas p ON j.plataforma_id = p.id 
              LEFT JOIN categorias c ON j.categoria_id = c.id 
              ORDER BY j.fecha_agregado DESC";
    $result = $conn->query($query);
    return $result->fetch_all(MYSQLI_ASSOC);
}

function getCategories($conn) {
    $query = "SELECT * FROM categorias ORDER BY nombre";
    $result = $conn->query($query);
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Cargar datos según el tipo de usuario
$user_data = getUserData($conn, $_SESSION['user_id']);

if ($user_data['tipo_usuario'] === 'admin') {
    // Datos para administrador
    $admin_stats = getAdminStats($conn);
    $all_users = getAllUsers($conn);
    $all_games = getAllGames($conn);
    $categories = getCategories($conn);
} else {
    // Datos para usuario normal
    $user_orders = getUserOrders($conn, $_SESSION['user_id']);
    $user_cards = getUserCards($conn, $_SESSION['user_id']);
    $user_reviews = getUserReviews($conn, $_SESSION['user_id']);
}

// Procesar formularios enviados
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Actualizar información personal
        if (isset($_POST['update_info'])) {
            $username = trim($_POST['username']);
            $email = trim($_POST['email']);
            $nombre = trim($_POST['nombre']);
            $apellido = trim($_POST['apellido']);
            
            // Validaciones básicas
            if (empty($username) || empty($email) || empty($nombre) || empty($apellido)) {
                throw new Exception('Todos los campos son obligatorios');
            }
            
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception('El email no es válido');
            }
            
            // Verificar que el username no esté en uso por otro usuario
            $check_query = "SELECT id FROM usuarios WHERE username = ? AND id != ?";
            $check_stmt = $conn->prepare($check_query);
            $check_stmt->bind_param("si", $username, $_SESSION['user_id']);
            $check_stmt->execute();
            if ($check_stmt->get_result()->num_rows > 0) {
                throw new Exception('El nombre de usuario ya está en uso');
            }
            
            // Verificar que el email no esté en uso por otro usuario
            $check_query = "SELECT id FROM usuarios WHERE email = ? AND id != ?";
            $check_stmt = $conn->prepare($check_query);
            $check_stmt->bind_param("si", $email, $_SESSION['user_id']);
            $check_stmt->execute();
            if ($check_stmt->get_result()->num_rows > 0) {
                throw new Exception('El email ya está en uso');
            }
            
            $update_query = "UPDATE usuarios SET username = ?, email = ?, nombre = ?, apellido = ?";
            $params = [$username, $email, $nombre, $apellido];
            $types = "ssss";
            
            // Manejar subida de imagen
            if (isset($_FILES['imagen_perfil']) && $_FILES['imagen_perfil']['error'] === UPLOAD_ERR_OK) {
                $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
                $max_size = 2 * 1024 * 1024; // 2MB
                
                if (!in_array($_FILES['imagen_perfil']['type'], $allowed_types)) {
                    throw new Exception('Tipo de archivo no permitido. Solo JPG, PNG y GIF.');
                }
                
                if ($_FILES['imagen_perfil']['size'] > $max_size) {
                    throw new Exception('El archivo es demasiado grande. Máximo 2MB.');
                }
                
                $extension = pathinfo($_FILES['imagen_perfil']['name'], PATHINFO_EXTENSION);
                $filename = 'user_' . $_SESSION['user_id'] . '_' . time() . '.' . $extension;
                $upload_path = 'images/users/' . $filename;
                
                if (!move_uploaded_file($_FILES['imagen_perfil']['tmp_name'], $upload_path)) {
                    throw new Exception('Error al subir la imagen');
                }
                
                // Eliminar imagen anterior si no es la por defecto
                if ($user_data['imagen_perfil'] !== 'default-avatar.png' && file_exists('images/users/' . $user_data['imagen_perfil'])) {
                    unlink('images/users/' . $user_data['imagen_perfil']);
                }
                
                $update_query .= ", imagen_perfil = ?";
                $params[] = $filename;
                $types .= "s";
            }
            
            $update_query .= " WHERE id = ?";
            $params[] = $_SESSION['user_id'];
            $types .= "i";
            
            $stmt = $conn->prepare($update_query);
            $stmt->bind_param($types, ...$params);
            
            if ($stmt->execute()) {
                $_SESSION['username'] = $username; // Actualizar sesión
                $success_message = 'Información actualizada correctamente';
                $user_data = getUserData($conn, $_SESSION['user_id']); // Recargar datos
            } else {
                throw new Exception('Error al actualizar la información');
            }
        }
        
        // Cambiar contraseña
        if (isset($_POST['change_password'])) {
            $current_password = $_POST['current_password'];
            $new_password = $_POST['new_password'];
            $confirm_password = $_POST['confirm_password'];
            
            if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
                throw new Exception('Todos los campos de contraseña son obligatorios');
            }
            
            if ($new_password !== $confirm_password) {
                throw new Exception('Las nuevas contraseñas no coinciden');
            }
            
            if (strlen($new_password) < 6) {
                throw new Exception('La nueva contraseña debe tener al menos 6 caracteres');
            }
            
            // Verificar contraseña actual
            $current_hash = md5($current_password);
            if ($current_hash !== $user_data['password']) {
                throw new Exception('La contraseña actual es incorrecta');
            }
            
            // Actualizar contraseña
            $new_hash = md5($new_password);
            $query = "UPDATE usuarios SET password = ? WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("si", $new_hash, $_SESSION['user_id']);
            
            if ($stmt->execute()) {
                $success_message = 'Contraseña actualizada correctamente';
            } else {
                throw new Exception('Error al actualizar la contraseña');
            }
        }
        
        // Agregar nueva tarjeta
        if (isset($_POST['add_card'])) {
            $card_number = preg_replace('/\s+/', '', $_POST['card_number']); // Remover espacios
            $card_expiry = $_POST['card_expiry'];
            $card_alias = trim($_POST['card_alias']) ?: 'Mi tarjeta';
            
            if (!preg_match('/^\d{13,19}$/', $card_number)) {
                throw new Exception('Número de tarjeta inválido');
            }
            
            if (!preg_match('/^\d{2}\/\d{2}$/', $card_expiry)) {
                throw new Exception('Formato de fecha inválido (MM/YY)');
            }
            
            // Validar que la fecha no esté vencida
            list($month, $year) = explode('/', $card_expiry);
            $expiry_date = DateTime::createFromFormat('m/y', $month . '/' . $year);
            $current_date = new DateTime();
            
            if ($expiry_date < $current_date) {
                throw new Exception('La tarjeta está vencida');
            }
            
            // Verificar que no exista ya esa tarjeta
            $check_query = "SELECT id FROM tarjetas WHERE usuario_id = ? AND AES_DECRYPT(numero_tarjeta, 'clave_cifrado_segura') = ?";
            $check_stmt = $conn->prepare($check_query);
            $check_stmt->bind_param("is", $_SESSION['user_id'], $card_number);
            $check_stmt->execute();
            if ($check_stmt->get_result()->num_rows > 0) {
                throw new Exception('Esta tarjeta ya está registrada');
            }
            
            // Insertar nueva tarjeta
            $query = "INSERT INTO tarjetas (usuario_id, numero_tarjeta, fecha_expiracion, alias) 
                      VALUES (?, AES_ENCRYPT(?, 'clave_cifrado_segura'), ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("isss", $_SESSION['user_id'], $card_number, $card_expiry, $card_alias);
            
            if ($stmt->execute()) {
                $success_message = 'Tarjeta agregada correctamente';
                $user_cards = getUserCards($conn, $_SESSION['user_id']); // Recargar tarjetas
            } else {
                throw new Exception('Error al agregar la tarjeta');
            }
        }
        
        // Eliminar tarjeta
        if (isset($_POST['delete_card'])) {
            $card_id = intval($_POST['card_id']);
            
            $query = "DELETE FROM tarjetas WHERE id = ? AND usuario_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ii", $card_id, $_SESSION['user_id']);
            
            if ($stmt->execute()) {
                $success_message = 'Tarjeta eliminada correctamente';
                $user_cards = getUserCards($conn, $_SESSION['user_id']); // Recargar tarjetas
            } else {
                throw new Exception('Error al eliminar la tarjeta');
            }
        }
        
        // Eliminar cuenta (solo usuarios normales)
        if (isset($_POST['delete_account']) && $user_data['tipo_usuario'] !== 'admin') {
            $confirmation = $_POST['confirmation'];
            
            if ($confirmation !== 'ELIMINAR') {
                throw new Exception('Confirmación incorrecta');
            }
            
            // Iniciar transacción para eliminar todos los datos relacionados
            $conn->begin_transaction();
            
            try {
                // Eliminar reseñas
                $conn->prepare("DELETE FROM resenas WHERE usuario_id = ?")->execute([$_SESSION['user_id']]);
                
                // Eliminar detalles de pedidos
                $conn->prepare("DELETE dp FROM detalles_pedido dp JOIN pedidos p ON dp.pedido_id = p.id WHERE p.usuario_id = ?")->execute([$_SESSION['user_id']]);
                
                // Eliminar pedidos
                $conn->prepare("DELETE FROM pedidos WHERE usuario_id = ?")->execute([$_SESSION['user_id']]);
                
                // Eliminar carrito
                $conn->prepare("DELETE FROM carrito WHERE usuario_id = ?")->execute([$_SESSION['user_id']]);
                
                // Eliminar tarjetas
                $conn->prepare("DELETE FROM tarjetas WHERE usuario_id = ?")->execute([$_SESSION['user_id']]);
                
                // Eliminar usuario
                $conn->prepare("DELETE FROM usuarios WHERE id = ?")->execute([$_SESSION['user_id']]);
                
                $conn->commit();
                
                // Destruir sesión y redirigir
                session_destroy();
                header('Location: index.php?message=account_deleted');
                exit();
                
            } catch (Exception $e) {
                $conn->rollback();
                throw new Exception('Error al eliminar la cuenta: ' . $e->getMessage());
            }
        }
        
    } catch (Exception $e) {
        $error_message = $e->getMessage();
    }
}

// Función para formatear fechas en español
function formatearFecha($fecha) {
    $meses = [
        1 => 'enero', 2 => 'febrero', 3 => 'marzo', 4 => 'abril',
        5 => 'mayo', 6 => 'junio', 7 => 'julio', 8 => 'agosto',
        9 => 'septiembre', 10 => 'octubre', 11 => 'noviembre', 12 => 'diciembre'
    ];
    
    $timestamp = strtotime($fecha);
    $dia = date('j', $timestamp);
    $mes = $meses[date('n', $timestamp)];
    $año = date('Y', $timestamp);
    
    return "$dia de $mes de $año";
}
?>