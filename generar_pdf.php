<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}
include 'config.php';

// Revisar que se pase un ID
if (!isset($_GET['id'])) {
    die("No se especificó proforma.");
}
$proforma_id = intval($_GET['id']);

// Obtener datos de la proforma y cliente
$stmt = $conn->prepare("
    SELECT p.*, c.nombre AS cliente, c.direccion AS cliente_direccion, 
           c.telefono AS cliente_telefono, c.cuit AS cliente_cuit
    FROM proformas p
    JOIN clientes c ON p.cliente_id = c.id
    WHERE p.id=?
");
$stmt->bind_param("i", $proforma_id);
$stmt->execute();
$proforma = $stmt->get_result()->fetch_assoc();
if(!$proforma) die("Proforma no encontrada.");

// Obtener items
$stmt2 = $conn->prepare("SELECT descripcion, cantidad, precio, subtotal FROM items_proforma WHERE proforma_id=?");
$stmt2->bind_param("i", $proforma_id);
$stmt2->execute();
$items = $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);

// Obtener datos de la empresa
$empresa = $conn->query("SELECT * FROM empresa LIMIT 1")->fetch_assoc();

// Incluir FPDF
require('fpdf/fpdf.php');

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',12);

// Logo empresa
if(!empty($empresa['logo']) && file_exists('assets/img/'.$empresa['logo'])){
    $pdf->Image('assets/img/'.$empresa['logo'],10,10,50);
}

// Datos de la empresa
$pdf->SetXY(120,10);
$pdf->Cell(0,6, utf8_decode($empresa['nombre']),0,1,'R');
$pdf->SetFont('Arial','',10);
$pdf->SetX(120);
$pdf->Cell(0,5, utf8_decode('Dirección: '.$empresa['direccion']),0,1,'R');
$pdf->SetX(120);
$pdf->Cell(0,5, 'CUIT: '.$empresa['cuit'],0,1,'R');
$pdf->SetX(120);
$pdf->Cell(0,5,utf8_decode( 'Teléfono: '.$empresa['telefono']),0,1,'R');
$pdf->SetX(120);
$pdf->Cell(0,5, utf8_decode('Ingresos Brutos: '.$empresa['ing_br']),0,1,'R');
$pdf->SetX(120);
$pdf->Cell(0,5, utf8_decode('Inicio de Actividad: '.$empresa['inicio_act']),0,1,'R');

$pdf->Ln(15);

// Título proforma
$pdf->SetFont('Arial','B',14);
$pdf->Cell(0,7, "PROFORMA #".$proforma['id'],0,1,'C');
$pdf->SetFont('Arial','',10);
$pdf->Cell(0,5, "Fecha: ".$proforma['fecha'],0,1,'C');
$pdf->Cell(0,5, utf8_decode("Proforma no válida como factura"),0,1,'C');
$pdf->Ln(5);

// Datos del cliente
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,6, utf8_decode("Cliente: ".$proforma['cliente']),0,1);
$pdf->SetFont('Arial','',10);
$pdf->Cell(0,5, utf8_decode("Dirección: ".$proforma['cliente_direccion']),0,1);
$pdf->Cell(0,5, utf8_decode( "Teléfono: ".$proforma['cliente_telefono']),0,1);
$pdf->Cell(0,5, "CUIT/CUIL: ".$proforma['cliente_cuit'],0,1);
$pdf->Ln(5);

// Tabla de items
$pdf->SetFont('Arial','B',10);
$pdf->Cell(80,7, utf8_decode("Descripción"),1);
$pdf->Cell(25,7, "Cantidad",1,0,'C');
$pdf->Cell(25,7, "Precio",1,0,'C');
$pdf->Cell(30,7, "Subtotal",1,1,'C');
$pdf->SetFont('Arial','',10);
foreach($items as $i){
    $pdf->Cell(80,6, utf8_decode($i['descripcion']),1);
    $pdf->Cell(25,6, number_format($i['cantidad'],2),1,0,'C');
    $pdf->Cell(25,6, '$'.number_format($i['precio'],2),1,0,'C');
    $pdf->Cell(30,6, '$'.number_format($i['subtotal'],2),1,1,'C');
}

// Total
$pdf->SetFont('Arial','B',12);
$pdf->Cell(130,7,'Total',1);
$pdf->Cell(30,7,'$'.number_format($proforma['total'],2),1,1,'C');
$pdf->Ln(5);

// Observación
if(!empty($proforma['observacion'])){
    $pdf->SetFont('Arial','',10);
    $pdf->MultiCell(0,5, utf8_decode("Observación: ".$proforma['observacion']));
}

// Generar PDF
$pdf->Output('I', 'proforma_'.$proforma['id'].'.pdf');


