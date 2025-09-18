<?php

function initializeCart() {
    if (!isset($_SESSION['carrito'])) {
        $_SESSION['carrito'] = array();
    }
}

function addToCart($conn, $juego_id, $cantidad = 1) {
    initializeCart();
    
    try {
        $stmt = $conn->prepare("SELECT id, titulo, precio, imagen FROM juegos WHERE id = ?");
        $stmt->bind_param("i", $juego_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            return ['success' => false, 'message' => 'El juego no existe'];
        }
        
        $juego = $result->fetch_assoc();
        
        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
            
            $stmt_check = $conn->prepare("SELECT cantidad FROM carrito WHERE user_id = ? AND juego_id = ?");
            $stmt_check->bind_param("ii", $user_id, $juego_id);
            $stmt_check->execute();
            $check_result = $stmt_check->get_result();
            
            if ($check_result->num_rows > 0) {
                $stmt_update = $conn->prepare("UPDATE carrito SET cantidad = cantidad + ? WHERE user_id = ? AND juego_id = ?");
                $stmt_update->bind_param("iii", $cantidad, $user_id, $juego_id);
                $stmt_update->execute();
            } else {
                $stmt_insert = $conn->prepare("INSERT INTO carrito (user_id, juego_id, cantidad) VALUES (?, ?, ?)");
                $stmt_insert->bind_param("iii", $user_id, $juego_id, $cantidad);
                $stmt_insert->execute();
            }
        }
        
        if (isset($_SESSION['carrito'][$juego_id])) {
            $_SESSION['carrito'][$juego_id]['cantidad'] += $cantidad;
        } else {
            $_SESSION['carrito'][$juego_id] = [
                'id' => $juego['id'],
                'titulo' => $juego['titulo'],
                'precio' => $juego['precio'],
                'imagen' => $juego['imagen'],
                'cantidad' => $cantidad
            ];
        }
        
        return ['success' => true, 'message' => 'Juego agregado al carrito exitosamente'];
        
    } catch (Exception $e) {
        return ['success' => false, 'message' => 'Error al agregar el juego al carrito'];
    }
}

function updateCartQuantity($conn, $juego_id, $nueva_cantidad) {
    initializeCart();
    
    try {
        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
            
            if ($nueva_cantidad <= 0) {
                $stmt = $conn->prepare("DELETE FROM carrito WHERE user_id = ? AND juego_id = ?");
                $stmt->bind_param("ii", $user_id, $juego_id);
                $stmt->execute();
            } else {
                $stmt = $conn->prepare("UPDATE carrito SET cantidad = ? WHERE user_id = ? AND juego_id = ?");
                $stmt->bind_param("iii", $nueva_cantidad, $user_id, $juego_id);
                $stmt->execute();
            }
        }
        
        if ($nueva_cantidad <= 0) {
            unset($_SESSION['carrito'][$juego_id]);
            return ['success' => true, 'message' => 'Juego eliminado del carrito'];
        } else {
            if (isset($_SESSION['carrito'][$juego_id])) {
                $_SESSION['carrito'][$juego_id]['cantidad'] = $nueva_cantidad;
            }
            return ['success' => true, 'message' => 'Cantidad actualizada'];
        }
        
    } catch (Exception $e) {
        return ['success' => false, 'message' => 'Error al actualizar la cantidad'];
    }
}

function removeFromCart($conn, $juego_id) {
    initializeCart();
    
    try {
        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
            $stmt = $conn->prepare("DELETE FROM carrito WHERE user_id = ? AND juego_id = ?");
            $stmt->bind_param("ii", $user_id, $juego_id);
            $stmt->execute();
        }
        
        unset($_SESSION['carrito'][$juego_id]);
        
        return ['success' => true, 'message' => 'Juego eliminado del carrito'];
        
    } catch (Exception $e) {
        return ['success' => false, 'message' => 'Error al eliminar el juego'];
    }
}

function clearCart($conn) {
    initializeCart();
    
    try {
        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
            $stmt = $conn->prepare("DELETE FROM carrito WHERE user_id = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
        }
        
        $_SESSION['carrito'] = array();
        
        return ['success' => true, 'message' => 'Carrito vaciado'];
        
    } catch (Exception $e) {
        return ['success' => false, 'message' => 'Error al vaciar el carrito'];
    }
}

function syncCartWithDatabase($conn, $user_id) {
    initializeCart();
    
    try {
        $stmt = $conn->prepare("
            SELECT c.juego_id, c.cantidad, j.titulo, j.precio, j.imagen
            FROM carrito c
            JOIN juegos j ON c.juego_id = j.id
            WHERE c.user_id = ?
        ");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $_SESSION['carrito'] = array();
        while ($row = $result->fetch_assoc()) {
            $_SESSION['carrito'][$row['juego_id']] = [
                'id' => $row['juego_id'],
                'titulo' => $row['titulo'],
                'precio' => $row['precio'],
                'imagen' => $row['imagen'],
                'cantidad' => $row['cantidad']
            ];
        }
        
    } catch (Exception $e) {
    }
}

function getCartTotal() {
    initializeCart();
    
    $total = 0;
    foreach ($_SESSION['carrito'] as $item) {
        $total += $item['precio'] * $item['cantidad'];
    }
    
    return $total;
}

function getCartItemCount() {
    initializeCart();
    
    $count = 0;
    foreach ($_SESSION['carrito'] as $item) {
        $count += $item['cantidad'];
    }
    
    return $count;
}

function isItemInCart($juego_id) {
    initializeCart();
    return isset($_SESSION['carrito'][$juego_id]);
}

function getCartItems() {
    initializeCart();
    return $_SESSION['carrito'];
}

function mergeSessionCartToDatabase($conn, $user_id) {
    initializeCart();
    
    if (empty($_SESSION['carrito'])) {
        return;
    }
    
    try {
        foreach ($_SESSION['carrito'] as $juego_id => $item) {
            $stmt_check = $conn->prepare("SELECT cantidad FROM carrito WHERE user_id = ? AND juego_id = ?");
            $stmt_check->bind_param("ii", $user_id, $juego_id);
            $stmt_check->execute();
            $check_result = $stmt_check->get_result();
            
            if ($check_result->num_rows > 0) {
                $existing = $check_result->fetch_assoc();
                $nueva_cantidad = $existing['cantidad'] + $item['cantidad'];
                
                $stmt_update = $conn->prepare("UPDATE carrito SET cantidad = ? WHERE user_id = ? AND juego_id = ?");
                $stmt_update->bind_param("iii", $nueva_cantidad, $user_id, $juego_id);
                $stmt_update->execute();
            } else {
                $stmt_insert = $conn->prepare("INSERT INTO carrito (user_id, juego_id, cantidad) VALUES (?, ?, ?)");
                $stmt_insert->bind_param("iii", $user_id, $juego_id, $item['cantidad']);
                $stmt_insert->execute();
            }
        }
        
        syncCartWithDatabase($conn, $user_id);
        
    } catch (Exception $e) {
    }
}

function formatPrice($price) {
    return '$' . number_format($price, 2);
}

function validateQuantity($quantity) {
    $qty = intval($quantity);
    return ($qty > 0 && $qty <= 10) ? $qty : 1;
}
?>