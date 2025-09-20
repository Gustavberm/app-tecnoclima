<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}
include 'config.php';

// Obtener datos del formulario
$cliente_id = intval($_POST['cliente_id']);
$fecha = $_POST['fecha'];
$observacion = $_POST['observacion'] ?? '';
$items = $_POST['item'] ?? [];

if (empty($cliente_id) || empty($fecha) || count($items) == 0) {
    die("Datos incompletos.");
}

// Calcular total
$total = 0;
foreach($items as $i){
    $subtotal = floatval($i['cantidad']) * floatval($i['precio']);
    $total += $subtotal;
}

// Revisar si es edición o nueva proforma
if(isset($_GET['id'])){
    // Actualizar proforma existente
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("UPDATE proformas SET cliente_id=?, fecha=?, observacion=?, total=? WHERE id=?");
    $stmt->bind_param("issdi", $cliente_id, $fecha, $observacion, $total, $id);
    $stmt->execute();

    // Eliminar items antiguos
    $stmt2 = $conn->prepare("DELETE FROM items_proforma WHERE proforma_id=?");
    $stmt2->bind_param("i", $id);
    $stmt2->execute();

    // Insertar items nuevos
    $stmt3 = $conn->prepare("INSERT INTO items_proforma (proforma_id, descripcion, cantidad, precio, subtotal) VALUES (?, ?, ?, ?, ?)");
    foreach($items as $i){
        $descripcion = $i['descripcion'];
        $cantidad = floatval($i['cantidad']);
        $precio = floatval($i['precio']);
        $subtotal = $cantidad * $precio;
        $stmt3->bind_param("isddd", $id, $descripcion, $cantidad, $precio, $subtotal);
        $stmt3->execute();
    }

}else{
    // Crear nueva proforma
    $stmt = $conn->prepare("INSERT INTO proformas (cliente_id, fecha, observacion, total) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("issd", $cliente_id, $fecha, $observacion, $total);
    $stmt->execute();
    $proforma_id = $stmt->insert_id;

    // Insertar items
    $stmt2 = $conn->prepare("INSERT INTO items_proforma (proforma_id, descripcion, cantidad, precio, subtotal) VALUES (?, ?, ?, ?, ?)");
    foreach($items as $i){
        $descripcion = $i['descripcion'];
        $cantidad = floatval($i['cantidad']);
        $precio = floatval($i['precio']);
        $subtotal = $cantidad * $precio;
        $stmt2->bind_param("isddd", $proforma_id, $descripcion, $cantidad, $precio, $subtotal);
        $stmt2->execute();
    }
}

header("Location: proformas.php");
exit();

