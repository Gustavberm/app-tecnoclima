<?php
require 'config.php';
if(!isset($_SESSION['usuario_id'])){ header("Location: login.php"); exit; }

$empresa=$conn->query("SELECT * FROM empresa LIMIT 1")->fetch_assoc();

if($_SERVER['REQUEST_METHOD']==='POST'){
    $nombre=$_POST['nombre'];
    $direccion=$_POST['direccion'];
    $telefono=$_POST['telefono'];
    $cuit=$_POST['cuit'];
    $email=$_POST['email'];

    $logo_path=$empresa['logo'];
    if(isset($_FILES['logo']) && $_FILES['logo']['error']==0){
        $target="assets/img/".basename($_FILES['logo']['name']);
        move_uploaded_file($_FILES['logo']['tmp_name'],$target);
        $logo_path=$target;
    }

    $stmt=$conn->prepare("UPDATE empresa SET nombre=?, direccion=?, telefono=?, cuit=?, email=?, logo=? WHERE id=?");
    $stmt->bind_param("ssssssi",$nombre,$direccion,$telefono,$cuit,$email,$logo_path,$empresa['id']);
    $stmt->execute();
    $stmt->close();
    header("Location: plantilla.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Plantilla Empresa</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<header class="main-header">
<h1>Configuración de Empresa</h1>
<a href="index.php">Volver</a>
</header>
<main class="container">
<form method="post" enctype="multipart/form-data" class="needs-validation">
<label>Nombre</label>
<input type="text" name="nombre" value="<?=htmlspecialchars($empresa['nombre'])?>" required>
<label>Dirección</label>
<input type="text" name="direccion" value="<?=htmlspecialchars($empresa['direccion'])?>">
<label>Teléfono</label>
<input type="text" name="telefono" value="<?=htmlspecialchars($empresa['telefono'])?>">
<label>CUIT</label>
<input type="text" name="cuit" value="<?=htmlspecialchars($empresa['cuit'])?>">
<label>Email</label>
<input type="email" name="email" value="<?=htmlspecialchars($empresa['email'])?>">
<label>Logo Actual</label>
<img src="<?=$empresa['logo']?>" style="max-height:80px;">
<label>Cambiar Logo</label>
<input type="file" name="logo">
<button type="submit">Guardar</button>
</form>
</main>
</body>
</html>