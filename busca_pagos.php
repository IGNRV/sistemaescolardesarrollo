<?php
require_once 'db.php';

$fecha = $_GET['fecha'] ?? '';
$medioPago = $_GET['medioPago'] ?? '';

$query = "SELECT * FROM historial_de_pagos WHERE fecha_pago = '$fecha' AND medio_de_pago = '$medioPago' AND estado = 1";
$resultado = $conn->query($query);

$datos = [];
if ($resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $datos[] = $fila;
    }
}

echo json_encode($datos);
?>