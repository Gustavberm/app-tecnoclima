<?php
session_start();
require 'config.php';
if(!isset($_SESSION['usuario_id'])){ header("Location: login.php"); exit; }

if($_SERVER['REQUEST_METHOD']=='POST'){
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];
    $cuit_cuil = $_POST['cuit_cuil'];

    $stmt = $conn->prepare("INSERT INTO clientes(nombre,email,telefono,direccion,cuit_cuil) VALUES(?,?,?,?,?)");
    $stmt->bind_param("sssss",$nombre,$email,$telefono,$direccion,$cuit_cuil);
    $stmt->execute();
    $stmt->close();
    header("Location: proformas.php"); exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Agregar Cliente</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="main-header">
<h1>Agregar Cliente</h1>
<nav>
<a href="index.php">Inicio</a>
<a href="proformas.php">Proformas</a>
<a href="logout.php">Salir</a>
</nav>
</div>
<div class="container">
<div class="card">
<form method="post">
<input type="text" name="nombre" placeholder="Nombre" required>
<input type="email" name="email" placeholder="Email">
<input type="text" name="telefono" placeholder="Teléfono">
<input type="text" name="direccion" placeholder="Dirección">
<input type="text" name="cuit_cuil" placeholder="CUIT/CUIL">
<button type="submit">Agregar Cliente</button>
</form>
</div>
</div>
</body>
</html>
