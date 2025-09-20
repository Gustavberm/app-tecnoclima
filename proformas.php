<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}
include 'config.php';

// Eliminar proforma si se envía ?delete=id
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    // Eliminar ítems primero
    $stmt = $conn->prepare("DELETE FROM items_proforma WHERE proforma_id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    // Eliminar proforma
    $stmt = $conn->prepare("DELETE FROM proformas WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: proformas.php");
    exit();
}

// Obtener todas las proformas
$query = "
    SELECT p.id, p.fecha, p.total, c.nombre AS cliente
    FROM proformas p
    JOIN clientes c ON p.cliente_id = c.id
    ORDER BY p.fecha DESC
";
$result = $conn->query($query);
$proformas = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Proformas</title>
<style>
body {
    font-family: Arial, sans-serif;
    margin:0; background:#f0f2f5;
}
.container {
    max-width: 1000px;
    margin: 30px auto;
    padding: 20px;
    background:#fff;
    border-radius:12px;
    box-shadow:0 4px 15px rgba(0,0,0,0.2);
}
.header-top {
    display:flex; 
    justify-content: space-between; 
    align-items: center; 
    margin-bottom: 15px;
}
h2 { margin-top:0; }
table { width:100%; border-collapse:collapse; margin-top:15px; }
th, td { padding:10px; border:1px solid #ddd; text-align:left; }
th { background:#2c3e50; color:#fff; }
tr:nth-child(even){ background:#f9f9f9; }

.btn-pdf { background:#27ae60; color:#fff; padding:6px 12px; border-radius:6px; text-decoration:none; font-weight:bold; transition: background 0.3s; }
.btn-pdf:hover { background:#2ecc71; }
.btn-edit { background:#2980b9; color:#fff; padding:6px 12px; border-radius:6px; text-decoration:none; font-weight:bold; transition: background 0.3s; }
.btn-edit:hover { background:#3498db; }
.btn-delete { background:#c0392b; color:#fff; padding:6px 12px; border-radius:6px; text-decoration:none; font-weight:bold; transition: background 0.3s; }
.btn-delete:hover { background:#e74c3c; }

.btn-back {
    background: #2c3e50;
    color: #fff;
    padding: 8px 12px;
    border-radius: 6px;
    text-decoration: none;
    font-weight: bold;
    transition: background 0.3s;
}
.btn-back:hover { background:#34495e; }

@media (max-width:600px){
    table, tr, td, th { display:block; width:100%; }
    tr { margin-bottom:15px; }
    td, th { text-align:right; padding-left:50%; position:relative; }
    td::before, th::before {
        position:absolute; left:10px; width:45%; white-space:nowrap; font-weight:bold;
    }
    td:nth-of-type(1)::before{content:"ID";}
    td:nth-of-type(2)::before{content:"Cliente";}
    td:nth-of-type(3)::before{content:"Fecha";}
    td:nth-of-type(4)::before{content:"Total";}
    td:nth-of-type(5)::before{content:"Acciones";}
}
</style>
</head>
<body>
<div class="container">
    <div class="header-top">
        <h2>Proformas Registradas</h2>
        <a href="index.php" class="btn-back">🏠 Volver al inicio</a>
    </div>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Fecha</th>
                <th>Total</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if(count($proformas)==0): ?>
                <tr><td colspan="5" style="text-align:center;">No hay proformas registradas.</td></tr>
            <?php else: ?>
                <?php foreach($proformas as $p): ?>
                    <tr>
                        <td><?= $p['id'] ?></td>
                        <td><?= htmlspecialchars($p['cliente']) ?></td>
                        <td><?= $p['fecha'] ?></td>
                        <td>$<?= number_format($p['total'],2) ?></td>
                        <td>
                            <a href="registrar_proforma.php?edit=<?= $p['id'] ?>" class="btn-edit">Editar</a>
                            <a href="proformas.php?delete=<?= $p['id'] ?>" class="btn-delete" onclick="return confirm('¿Eliminar esta proforma?')">Eliminar</a>
                            <a href="generar_pdf.php?id=<?= $p['id'] ?>" class="btn-pdf" target="_blank">Ver PDF</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>



