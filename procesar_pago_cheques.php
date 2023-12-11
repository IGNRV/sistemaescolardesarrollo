<?php
require_once 'db.php'; 
ini_set('display_errors', 1);
// ruta_a_tu_script_de_insercion.php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $json = file_get_contents('php://input');
    $data = json_decode($json);

    foreach ($data->pagos as $pago) {
        // Prepara la sentencia SQL para insertar los datos en la base de datos
        $stmt = $conn->prepare("INSERT INTO DETALLES_TRANSACCION 
            (ANO, VALOR, FECHA_PAGO, MEDIO_DE_PAGO, ESTADO, TIPO_DOCUMENTO, NUMERO_DOCUMENTO, FECHA_EMISION, FECHA_COBRO, BANCO, N_CUOTAS, ID_PAGO) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("idssisssssis", 
            $pago->ano,
            $pago->monto,
            $pago->fechaPago,
            $pago->medioDePago,
            $pago->estado,
            $pago->tipoDocumento,
            $pago->nDocumento,
            $pago->fechaEmision,
            $pago->fechaCobro,
            $pago->banco,
            $pago->nCuotas,
            $pago->idPago
        );
        $stmt->execute();
        // Comprueba los errores y confirma la inserción aquí
    }

    // Envía una respuesta al cliente
    echo json_encode(['success' => true]);
}


?>