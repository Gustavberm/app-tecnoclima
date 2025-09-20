<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}
include 'config.php';

// Obtener lista de clientes
$result = $conn->query("SELECT id, nombre FROM clientes ORDER BY nombre ASC");
$clientes = $result->fetch_all(MYSQLI_ASSOC);

// Inicializar variables
$edit = false;
$proforma_id = 0;
$cliente_id = '';
$fecha = date('Y-m-d');
$observacion = '';
$items = [];

if (isset($_GET['edit'])) {
    $proforma_id = intval($_GET['edit']);
    $edit = true;

    // Datos proforma
    $stmt = $conn->prepare("SELECT cliente_id, fecha, observacion FROM proformas WHERE id=?");
    $stmt->bind_param("i", $proforma_id);
    $stmt->execute();
    $proforma = $stmt->get_result()->fetch_assoc();

    $cliente_id = $proforma['cliente_id'];
    $fecha = $proforma['fecha'];
    $observacion = $proforma['observacion'];

    // Datos items
    $stmt2 = $conn->prepare("SELECT id, descripcion, cantidad, precio FROM items_proforma WHERE proforma_id=?");
    $stmt2->bind_param("i", $proforma_id);
    $stmt2->execute();
    $items = $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $edit ? "Editar Proforma" : "Registrar Proforma" ?></title>
<style>
body { font-family: Arial; background:#f0f2f5; margin:0; }
.container { max-width: 900px; margin:30px auto; padding:20px; background:#fff; border-radius:12px; box-shadow:0 4px 15px rgba(0,0,0,0.2);}
.header { display:flex; justify-content: space-between; align-items: center; margin-bottom: 15px;}
h2 { margin:0; }
.btn-back { background:#2c3e50; color:#fff; padding:8px 12px; border-radius:6px; text-decoration:none; font-weight:bold; }
.btn-back:hover { background:#34495e; }
label { display:block; margin-top:10px; font-weight:bold; }
input, select, textarea, button { width:100%; padding:10px; margin-top:5px; border-radius:6px; border:1px solid #ccc; box-sizing:border-box;}
textarea { resize: vertical;}
button.submit-btn { background:#2c3e50;color:#fff;border:none;margin-top:20px;cursor:pointer;font-size:16px;}
button.submit-btn:hover { background:#34495e;}
.item { margin-bottom:10px; padding:10px; border:1px solid #ddd; border-radius:6px;}
.delete-item { background:#c0392b; color:#fff; border:none; border-radius:4px; padding:5px 10px; cursor:pointer; margin-top:5px;}
.delete-item:hover { background:#e74c3c; }
@media (max-width:600px){ .container{margin:10px; padding:15px;} }
</style>
<script>
function addItem(desc='', qty='', price='') {
    const div = document.getElementById('items');
    const idx = div.children.length;
    const html = `
    <div class="item">
        <input type="text" name="item[${idx}][descripcion]" placeholder="Descripción" required value="${desc}">
        <input type="number" step="0.01" name="item[${idx}][cantidad]" placeholder="Cantidad" oninput="updateItemTotal(this)" required value="${qty}">
        <input type="number" step="0.01" name="item[${idx}][precio]" placeholder="Precio" oninput="updateItemTotal(this)" required value="${price}">
        <input type="text" name="item[${idx}][total]" placeholder="Total" readonly>
        <div style="text-align:center;">
            <button type="button" class="delete-item" onclick="removeItem(this)">Eliminar</button>
        </div>
    </div>`;
    div.insertAdjacentHTML('beforeend', html);
    updateItemTotal(div.lastElementChild.querySelector('[name*="[cantidad]"]'));
}

function updateItemTotal(el) {
    const card = el.closest('.item');
    const cantidad = parseFloat(card.querySelector('[name*="[cantidad]"]').value) || 0;
    const precio = parseFloat(card.querySelector('[name*="[precio]"]').value) || 0;
    card.querySelector('[name*="[total]"]').value = (cantidad * precio).toFixed(2);
}

function removeItem(button) { button.closest('.item').remove(); }
</script>
</head>
<body>
<div class="container">
    <div class="header">
        <h2><?= $edit ? "Editar Proforma" : "Registrar Proforma" ?></h2>
        <a href="index.php" class="btn-back">🏠 Volver al inicio</a>
    </div>

    <form action="guardar_proforma.php<?= $edit ? '?id='.$proforma_id : '' ?>" method="POST">
        <label>Cliente:</label>
        <select name="cliente_id" required>
            <option value="">Seleccione un cliente</option>
            <?php foreach($clientes as $c): ?>
                <option value="<?= $c['id'] ?>" <?= $c['id']==$cliente_id?'selected':'' ?>><?= htmlspecialchars($c['nombre']) ?></option>
            <?php endforeach; ?>
        </select>

        <label>Fecha:</label>
        <input type="date" name="fecha" value="<?= $fecha ?>" required>

        <h3>Items</h3>
        <div id="items"></div>
        <button type="button" onclick="addItem()">Agregar Item</button>

        <label>Observación:</label>
        <textarea name="observacion"><?= htmlspecialchars($observacion) ?></textarea>

        <button type="submit" class="submit-btn"><?= $edit ? "Actualizar Proforma" : "Guardar Proforma" ?></button>
    </form>
</div>

<script>
// Cargar items existentes si estamos editando
<?php if($edit && count($items)>0): ?>
<?php foreach($items as $i): ?>
addItem('<?= htmlspecialchars($i['descripcion'],ENT_QUOTES) ?>','<?= $i['cantidad'] ?>','<?= $i['precio'] ?>');
<?php endforeach; ?>
<?php endif; ?>
</script>
</body>
</html>


