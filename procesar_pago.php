<?php
require_once 'db.php'; // Asegúrate de que este es el camino correcto hacia tu archivo db.php

$data = json_decode(file_get_contents('php://input'), true);

if (!empty($data['pagos'])) {
    foreach ($data['pagos'] as $pago) {
        // Aquí implementarías la lógica para insertar en las tablas DETALLES_TRANSACCION y HISTORIAL_PAGOS
        // Por ejemplo:
        $stmt = $conn->prepare("INSERT INTO DETALLES_TRANSACCION (ANO, CODIGO_PRODUCTO, FOLIO_PAGO, VALOR, FECHA_PAGO, MEDIO_DE_PAGO, ESTADO, FECHA_VENCIMIENTO, TIPO_DOCUMENTO, NUMERO_DOCUMENTO, FECHA_EMISION, FECHA_COBRO) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sissssisssss", $ano, $codigoProducto, $folioPago, $valor, $fechaPago, $medioPago, $estado, $fechaVencimiento, $tipoDocumento, $numeroDocumento, $fechaEmision, $fechaCobro);
        
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
        $numeroDocumento = obtenerSiguienteNumeroDocumento($conn); // Esta función debería calcular el siguiente número de documento
        $fechaEmision = date('Y-m-d');
        $fechaCobro = date('Y-m-d');
        
        $stmt->execute();
        $stmt->close();
    }
    echo 'Pago registrado con éxito.';
} else {
    echo 'No hay pagos para procesar.';
}

// Función para obtener el siguiente número de documento
function obtenerSiguienteNumeroDocumento($conn) {
    // Implementar lógica para obtener el siguiente número de documento
    // Por ejemplo:
    $resultado = $conn->query("SELECT MAX(NUMERO_DOCUMENTO) AS ultimoNumero FROM DETALLES_TRANSACCION");
    $fila = $resultado->fetch_assoc();
    return $fila['ultimoNumero'] + 1;
}
?>
