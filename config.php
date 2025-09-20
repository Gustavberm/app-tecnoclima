<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mi_app";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8mb4"); // importante para acentos

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
