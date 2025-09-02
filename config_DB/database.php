<?php
$host = "localhost";
$username = "root";
$password = ""; // Cambiar por la contraseña de MySQL en Ubuntu
$database = "basenueva";
$port = 3306;

$conn = new mysqli($host, $username, $password, $database, $port);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>