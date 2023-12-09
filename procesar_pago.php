<?php
require_once 'db.php'; // Asegúrate de que este es el camino correcto hacia tu archivo db.php

$data = json_decode(file_get_contents('php://input'), true);

if (!empty($data['pagos'])) {
    foreach ($data['pagos'] as $pago) {
        // Insertar en DETALLES_TRANSACCION
        $stmt = $conn->prepare("INSERT INTO DETALLES_TRANSACCION (ANO, CODIGO_PRODUCTO, FOLIO_PAGO, VALOR, FECHA_PAGO, MEDIO_DE_PAGO, ESTADO, FECHA_VENCIMIENTO, TIPO_DOCUMENTO, NUMERO_DOCUMENTO, FECHA_EMISION, FECHA_COBRO, ID_PAGO) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sissssisssssi", $ano, $codigoProducto, $folioPago, $valor, $fechaPago, $medioPago, $estado, $fechaVencimiento, $tipoDocumento, $numeroDocumento, $fechaEmision, $fechaCobro, $idPago);

        // Asignar valores a las variables
        $ano = date('Y');
        $codigoProducto = $pago['codigoProducto'];
        $folioPago = $pago['folioPago'];
        $valor = $pago['valor'];
        $fechaPago = $data['fechaPago'];
        $medioPago = $data['tipoDocumento'];
        $estado = 1; // Suponiendo que el pago es exitoso
        $fechaVencimiento = $pago['fechaVencimiento'];
        $tipoDocumento = $data['tipoDocumento'];
        $numeroDocumento = obtenerSiguienteNumeroDocumento($conn);
        $fechaEmision = date('Y-m-d');
        $fechaCobro = date('Y-m-d');
        $idPago = $pago['idPago']; // Asignar el ID del pago a la columna "ID_PAGO"
        
        $stmt->execute();
        $stmt->close();

        // Actualizar el estado del pago, la fecha de pago y el medio de pago en HISTORIAL_PAGOS
        $fechaActual = date('Y-m-d'); // Obtener la fecha actual
        $stmtUpdate = $conn->prepare("UPDATE HISTORIAL_PAGOS SET ESTADO_PAGO = 2, FECHA_PAGO = ?, MEDIO_PAGO = ? WHERE ID_PAGO = ?");
        $stmtUpdate->bind_param("ssi", $fechaActual, $medioPago, $idPago);

        // Asignar el ID del pago
        $idPago = $pago['idPago'];

        $stmtUpdate->execute();
        $stmtUpdate->close();
    }
    echo 'Pago registrado con éxito.';
} else {
    echo 'No hay pagos para procesar.';
}

// Función para obtener el siguiente número de documento
function obtenerSiguienteNumeroDocumento($conn) {
    $resultado = $conn->query("SELECT MAX(NUMERO_DOCUMENTO) AS ultimoNumero FROM DETALLES_TRANSACCION");
    $fila = $resultado->fetch_assoc();
    return $fila['ultimoNumero'] + 1;
}
?>
