<?php
require_once 'db.php'; // Asegúrate de que este es el camino correcto hacia tu archivo db.php

$data = json_decode(file_get_contents('php://input'), true);

if (!empty($data['pagos'])) {
    $adicionales = $data['adicionales'];

    foreach ($data['pagos'] as $pago) {
        // Procesar cada pago individualmente
        procesarPago($pago, $adicionales, $conn);
    }
    echo 'Pago registrado con éxito.';
} else {
    echo 'No hay pagos para procesar.';
}

function procesarPago($pago, $adicionales, $conn) {
    // Insertar en DETALLES_TRANSACCION para efectivo
    if ($adicionales['montoEfectivo'] > 0) {
        insertarDetalleTransaccion($pago, $adicionales['tipoDocumentoEfectivo'], $adicionales['montoEfectivo'], $conn);
    }

    // Insertar en DETALLES_TRANSACCION para POS
    if ($adicionales['montoPos'] > 0) {
        insertarDetalleTransaccion($pago, $adicionales['tipoDocumentoPos'], $adicionales['montoPos'], $conn);
    }

    // Actualizar el estado del pago en HISTORIAL_PAGOS
    actualizarHistorialPagos($pago, $conn);
}

function insertarDetalleTransaccion($pago, $tipoDocumento, $monto, $conn) {
    $stmt = $conn->prepare("INSERT INTO DETALLES_TRANSACCION (ANO, CODIGO_PRODUCTO, FOLIO_PAGO, VALOR, FECHA_PAGO, MEDIO_DE_PAGO, ESTADO, FECHA_VENCIMIENTO, TIPO_DOCUMENTO, NUMERO_DOCUMENTO, FECHA_EMISION, FECHA_COBRO, ID_PAGO) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sissssisssssi", $ano, $codigoProducto, $folioPago, $valor, $fechaPago, $medioPago, $estado, $fechaVencimiento, $tipoDocumento, $numeroDocumento, $fechaEmision, $fechaCobro, $idPago);

    // Asignar valores a las variables
    $ano = date('Y');
    $codigoProducto = $pago['codigoProducto'];
    $folioPago = $pago['folioPago'];
    $valor = $monto; // Monto del pago
    $fechaPago = $adicionales['fechaPago'];
    $medioPago = $tipoDocumento;
    $estado = 1; // Suponiendo que el pago es exitoso
    $fechaVencimiento = $pago['fechaVencimiento'];
    $tipoDocumento = $tipoDocumento;
    $numeroDocumento = obtenerSiguienteNumeroDocumento($conn);
    $fechaEmision = date('Y-m-d');
    $fechaCobro = date('Y-m-d');
    $idPago = $pago['idPago'];

    $stmt->execute();
    $stmt->close();
}

function actualizarHistorialPagos($pago, $conn) {
    $fechaActual = date('Y-m-d');
    $stmtUpdate = $conn->prepare("UPDATE HISTORIAL_PAGOS SET ESTADO_PAGO = 2, FECHA_PAGO = ? WHERE ID_PAGO = ?");
    $stmtUpdate->bind_param("si", $fechaActual, $idPago);

    $idPago = $pago['idPago'];

    $stmtUpdate->execute();
    $stmtUpdate->close();
}

function obtenerSiguienteNumeroDocumento($conn) {
    $resultado = $conn->query("SELECT MAX(NUMERO_DOCUMENTO) AS ultimoNumero FROM DETALLES_TRANSACCION");
    $fila = $resultado->fetch_assoc();
    return $fila['ultimoNumero'] + 1;
}
?>
