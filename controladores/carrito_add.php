<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config_db/database.php';

// Inicializar carrito en sesión si no existe
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = array();
}

// Procesar solicitud de agregar al carrito
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['juego_id'])) {
    $juego_id = intval($_POST['juego_id']);
    $cantidad = isset($_POST['cantidad']) ? intval($_POST['cantidad']) : 1;
    
    if ($cantidad <= 0) $cantidad = 1;
    
    try {
        // Verificar que el juego existe y obtener sus datos
        $stmt = $conn->prepare("SELECT id, titulo, precio, imagen FROM juegos WHERE id = ?");
        $stmt->bind_param("i", $juego_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result && $result->num_rows > 0) {
            $juego = $result->fetch_assoc();
            
            // Si el juego ya está en el carrito, aumentar cantidad
            if (isset($_SESSION['carrito'][$juego_id])) {
                $_SESSION['carrito'][$juego_id]['cantidad'] += $cantidad;
            } else {
                // Agregar nuevo juego al carrito
                $_SESSION['carrito'][$juego_id] = array(
                    'id' => $juego['id'],
                    'titulo' => $juego['titulo'],
                    'precio' => $juego['precio'],
                    'imagen' => $juego['imagen'],
                    'cantidad' => $cantidad
                );
            }
            
            // Mensaje de éxito
            $_SESSION['cart_message'] = 'Juego agregado al carrito exitosamente';
            $_SESSION['cart_message_type'] = 'success';
        } else {
            $_SESSION['cart_message'] = 'El juego no existe';
            $_SESSION['cart_message_type'] = 'error';
        }
    } catch (mysqli_sql_exception $e) {
        $_SESSION['cart_message'] = 'Error al agregar el juego al carrito';
        $_SESSION['cart_message_type'] = 'error';
    }
}

// Determinar la URL de redirección
$redirect_url = '/nexusplay/index.php'; // URL por defecto

// Si se envió desde el carrito, redirigir al carrito
if (isset($_POST['from_cart']) && $_POST['from_cart'] == '1') {
    $redirect_url = '/nexusplay/cart.php';
} elseif (isset($_SERVER['HTTP_REFERER'])) {
    // Redirigir a la página anterior solo si es del mismo sitio
    $referer = $_SERVER['HTTP_REFERER'];
    $site_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
    
    // Verificar que el referer es del mismo sitio
    if (strpos($referer, $site_url) === 0) {
        $redirect_url = $referer;
    }
}

header("Location: $redirect_url");
exit();
?>