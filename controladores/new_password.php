<?php
if (session_status() == PHP_SESSION_NONE) session_start();

require_once '../config_db/database.php';
require_once '../functions/fun_auth.php';

if (!isLoggedIn() || !isAdmin()) {
    header('Location: ../index.php');
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current = $_POST['current_password'] ?? '';
    $new = $_POST['new_password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if ($new !== $confirm) {
        die("Las nuevas contraseñas no coinciden.");
    }

    try {
        // Obtener la contraseña actual
        $stmt = $conn->prepare("SELECT password FROM usuarios WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if (!$user) die("Usuario no encontrado.");

        // Verificar contraseña actual
        if (!password_verify($current, $user['password'])) {
            die("Contraseña actual incorrecta.");
        }

        // Actualizar contraseña
        $new_hashed = password_hash($new, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE usuarios SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $new_hashed, $user_id);
        $stmt->execute();

        echo "Contraseña actualizada con éxito.";

    } catch (mysqli_sql_exception $e) {
        die("Error: " . $e->getMessage());
    }
}
?>
