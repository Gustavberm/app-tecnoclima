<?php
session_start();
if(!isset($_SESSION['usuario'])){
    header("Location: login.php");
    exit();
}

include 'config.php';

// Obtener datos de la empresa
$empresa = $conn->query("SELECT * FROM empresa LIMIT 1")->fetch_assoc();

// Guardar cambios si se envía POST
if($_SERVER['REQUEST_METHOD']=='POST'){
    $nombre = $_POST['nombre'];
    $direccion = $_POST['direccion'];
    $cuit = $_POST['cuit'];
    $telefono = $_POST['telefono'];
    $ingresos_brutos = $_POST['ingresos_brutos'];
    $inicio_actividad = $_POST['inicio_actividad'];

    // Subir logo si hay archivo
    $logo = $empresa['logo'];
    if(isset($_FILES['logo']) && $_FILES['logo']['error']==0){
        $ext = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
        $logo = 'logo_empresa.'.$ext;
        move_uploaded_file($_FILES['logo']['tmp_name'], 'img/'.$logo);
    }

    $stmt = $conn->prepare("UPDATE empresa SET nombre=?, direccion=?, cuit=?, telefono=?, ing_br=?, inicio_act=?, logo=? WHERE id=?");
    $stmt->bind_param("sssssssi", $nombre, $direccion, $cuit, $telefono, $ingresos_brutos, $inicio_actividad, $logo, $empresa['id']);
    $stmt->execute();
    header("Location: editar_empresa.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Empresa</title>
<link rel="stylesheet" href="styles.css">
<style>
.container { max-width:600px; margin:30px auto; padding:20px; background:#fff; border-radius:12px; box-shadow:0 4px 15px rgba(0,0,0,0.2); }
h2 { margin-top:0; }
input, label { display:block; width:100%; margin-bottom:10px; }
input[type="submit"] { width:auto; padding:8px 12px; background:#2c3e50; color:#fff; border:none; border-radius:6px; cursor:pointer; }
input[type="submit"]:hover { background:#34495e; }
.btn-back { background:#2c3e50; color:#fff; padding:8px 12px; border-radius:6px; text-decoration:none; font-weight:bold; transition:background 0.3s; }
.btn-back:hover { background:#34495e; }
</style>
</head>
<body>
<div class="container">
    <div style="display:flex; justify-content:space-between; align-items:center;">
        <h2>Datos de la Empresa</h2>
        <a href="index.php" class="btn-back">🏠 Volver al inicio</a>
    </div>
    <form method="POST" enctype="multipart/form-data">
        <label>Nombre:</label>
        <input type="text" name="nombre" value="<?= htmlspecialchars($empresa['nombre']) ?>" required>

        <label>Dirección:</label>
        <input type="text" name="direccion" value="<?= htmlspecialchars($empresa['direccion']) ?>" required>

        <label>CUIT:</label>
        <input type="text" name="cuit" value="<?= htmlspecialchars($empresa['cuit']) ?>">

        <label>Teléfono:</label>
        <input type="text" name="telefono" value="<?= htmlspecialchars($empresa['telefono']) ?>">

        <label>Ingresos Brutos:</label>
        <input type="text" name="ingresos_brutos" value="<?= htmlspecialchars($empresa['ing_br']) ?>">

        <label>Inicio de Actividad:</label>
        <input type="text" name="inicio_actividad" value="<?= htmlspecialchars($empresa['inicio_act']) ?>">

        <label>Logo:</label>
        <input type="file" name="logo">
        <?php if(!empty($empresa['logo'])): ?>
            <img src="assets/img/<?= $empresa['logo'] ?>" alt="Logo" style="max-width:150px;margin-top:5px;">
        <?php endif; ?>

        <input type="submit" value="Guardar Cambios">
    </form>
</div>
</body>
</html>


