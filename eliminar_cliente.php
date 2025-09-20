<?php
session_start();
if(!isset($_SESSION['usuario'])){
    header("Location: login.php");
    exit();
}

include 'config.php';

// Verificar que exista el ID
if(isset($_GET['id'])){
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("DELETE FROM clientes WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

// Redirigir de nuevo a la página de clientes
header("Location: clientes.php");
exit();
