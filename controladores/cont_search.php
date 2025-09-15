<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once 'config_db/database.php';
require_once 'functions/fun_auth.php';
require_once 'functions/fun_profile.php';

if (isset($_SESSION['user_id'])) {
    $perfil_img = loadUserProfileImage($conn, $_SESSION['user_id']);
    $_SESSION['imagen_perfil'] = $perfil_img;
}

$search_query = isset($_GET['q']) ? trim($_GET['q']) : '';
$plataforma_id = isset($_GET['plataforma']) ? $_GET['plataforma'] : '';
$categoria_id = isset($_GET['categoria']) ? $_GET['categoria'] : '';
$precio = isset($_GET['precio']) ? $_GET['precio'] : '';

$juegos_result = null;
$total_resultados = 0;

$sql = "SELECT id, titulo, descripcion, imagen, precio FROM juegos WHERE 1=1";

if (!empty($search_query)) {
    $sql .= " AND CONCAT(titulo, ' ', descripcion) LIKE '%$search_query%'";  //Vulnerabilidad SQLi
}

if (!empty($plataforma_id)) {
    $sql .= " AND plataforma_id = $plataforma_id";
}

if (!empty($categoria_id)) {
    $sql .= " AND categoria_id = $categoria_id";
}

if (!empty($precio)) {
    switch ($precio) {
        case '1':
            $sql .= " AND precio < 20";
            break;
        case '2':
            $sql .= " AND precio BETWEEN 20 AND 50";
            break;
        case '3':
            $sql .= " AND precio > 50";
            break;
    }
}

try {
    $juegos_result = $conn->query($sql);
    if ($juegos_result) {
        $total_resultados = $juegos_result->num_rows;
    } else {
        $total_resultados = 0;
    }
} catch (mysqli_sql_exception $e) {
    echo "Error SQL: " . $e->getMessage();
    exit;
}

$plataformas = $conn->query("SELECT * FROM plataformas");
$categorias = $conn->query("SELECT * FROM categorias");

if (!function_exists('highlightSearchTerm')) {
    function highlightSearchTerm($text, $term) {
        if (empty($term)) {
            return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
        }
        $escapedText = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
        $escapedTerm = htmlspecialchars($term, ENT_QUOTES, 'UTF-8');
        return preg_replace(
            "/(" . preg_quote($escapedTerm, "/") . ")/i",
            '<span class="highlight">$1</span>',
            $escapedText
        );
    }
}
?>