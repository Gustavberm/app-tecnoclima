<?php
session_start();
if(!isset($_SESSION['usuario'])){
    header("Location: login.php");
    exit();
}

include 'config.php';

// Guardar nuevo cliente
if($_SERVER['REQUEST_METHOD']=='POST' && isset($_POST['nuevo_cliente'])){
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];
    $cuit = $_POST['cuit'];

    $stmt = $conn->prepare("INSERT INTO clientes(nombre,email,telefono,direccion,cuit) VALUES(?,?,?,?,?)");
    $stmt->bind_param("sssss", $nombre,$email,$telefono,$direccion,$cuit);
    $stmt->execute();
    header("Location: clientes.php");
    exit();
}

// Obtener clientes
$result = $conn->query("SELECT id, nombre, email, telefono, direccion, cuit FROM clientes ORDER BY nombre ASC");
$clientes = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Clientes</title>
<link rel="stylesheet" href="styles.css">
<style>
.container { max-width:900px; margin:30px auto; padding:20px; background:#fff; border-radius:12px; box-shadow:0 4px 15px rgba(0,0,0,0.2); }
h2 { margin-top:0; }
table { width:100%; border-collapse:collapse; margin-top:15px; }
th, td { padding:10px; border:1px solid #ddd; text-align:left; }
th { background:#2c3e50; color:#fff; }
tr:nth-child(even){ background:#f9f9f9; }

input, label { display:block; width:100%; margin-bottom:10px; padding:6px; border-radius:6px; border:1px solid #ccc; }
input[type="submit"] { width:auto; background:#2c3e50; color:#fff; border:none; padding:8px 12px; cursor:pointer; border-radius:6px; }
input[type="submit"]:hover { background:#34495e; }

.btn-back { background:#2c3e50; color:#fff; padding:8px 12px; border-radius:6px; text-decoration:none; font-weight:bold; transition:background 0.3s; }
.btn-back:hover { background:#34495e; }

.btn-delete { background:#c0392b; color:#fff; padding:6px 12px; border-radius:6px; text-decoration:none; font-weight:bold; transition: background 0.3s; }
.btn-delete:hover { background:#e74c3c; }

@media (max-width:600px){
    table, tr, td, th { display:block; width:100%; }
    tr { margin-bottom:15px; }
    td, th { text-align:right; padding-left:50%; position:relative; }
    td::before, th::before { position:absolute; left:10px; width:45%; white-space:nowrap; font-weight:bold; }
    td:nth-of-type(1)::before{content:"ID";}
    td:nth-of-type(2)::before{content:"Nombre";}
    td:nth-of-type(3)::before{content:"Email";}
    td:nth-of-type(4)::before{content:"Teléfono";}
    td:nth-of-type(5)::before{content:"Dirección";}
    td:nth-of-type(6)::before{content:"CUIT";}
    td:nth-of-type(7)::before{content:"Acciones";}
}
</style>
</head>
<body>
<div class="container">
    <div style="display:flex; justify-content:space-between; align-items:center;">
        <h2>Clientes Registrados</h2>
        <a href="index.php" class="btn-back">🏠 Volver al inicio</a>
    </div>

    <!-- Formulario Agregar Cliente -->
    <form method="POST" style="margin-top:20px; padding:15px; border:1px solid #ccc; border-radius:10px;">
        <h3>Agregar Nuevo Cliente</h3>
        <input type="hidden" name="nuevo_cliente" value="1">
        <label>Nombre:</label>
        <input type="text" name="nombre" required>

        <label>Email:</label>
        <input type="email" name="email">

        <label>Teléfono:</label>
        <input type="text" name="telefono">

        <label>Dirección:</label>
        <input type="text" name="direccion">

        <label>CUIT/CUIL:</label>
        <input type="text" name="cuit">

        <input type="submit" value="Agregar Cliente">
    </form>

    <!-- Tabla de Clientes -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Teléfono</th>
                <th>Dirección</th>
                <th>CUIT</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if(count($clientes)==0): ?>
                <tr><td colspan="7" style="text-align:center;">No hay clientes registrados.</td></tr>
            <?php else: ?>
                <?php foreach($clientes as $c): ?>
                    <tr>
                        <td><?= $c['id'] ?></td>
                        <td><?= htmlspecialchars($c['nombre']) ?></td>
                        <td><?= htmlspecialchars($c['email']) ?></td>
                        <td><?= htmlspecialchars($c['telefono']) ?></td>
                        <td><?= htmlspecialchars($c['direccion']) ?></td>
                        <td><?= isset($c['cuit']) ? htmlspecialchars($c['cuit']) : '' ?></td>
                        <td>
                            <a href="eliminar_cliente.php?id=<?= $c['id'] ?>" class="btn-delete" onclick="return confirm('¿Eliminar este cliente?')">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>

