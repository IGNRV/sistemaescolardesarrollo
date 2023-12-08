<?php
require_once 'db.php'; // Asegúrate de que este es el camino correcto hacia tu archivo db.php

$saldoPeriodoAnterior = [];
$cuotasPeriodoActual = [];
$mensaje = '';

if (isset($_POST['btnBuscarAlumno'])) {
    $rutAlumno = $_POST['rutAlumno'];
    $yearActual = date('Y');

    // Consulta a la base de datos
    $stmt = $conn->prepare("SELECT 
                                hp.ID_PAGO,
                                hp.ID_ALUMNO,
                                a.RUT_ALUMNO,
                                hp.CODIGO_PRODUCTO,
                                hp.FOLIO_PAGO,
                                hp.VALOR_ARANCEL,
                                hp.DESCUENTO_BECA,
                                hp.OTROS_DESCUENTOS,
                                hp.VALOR_A_PAGAR,
                                hp.FECHA_PAGO,
                                hp.MEDIO_PAGO,
                                hp.NRO_MEDIOPAGO,
                                hp.FECHA_SUSCRIPCION,
                                hp.BANCO_EMISOR,
                                hp.TIPO_MEDIOPAGO,
                                hp.ESTADO_PAGO,
                                hp.TIPO_DOCUMENTO,
                                hp.NUMERO_DOCUMENTO,
                                hp.FECHA_VENCIMIENTO,
                                hp.FECHA_INGRESO,
                                hp.FECHA_EMISION,
                                hp.FECHA_COBRO,
                                hp.ID_PERIODO_ESCOLAR
                            FROM
                                c1occsyspay.HISTORIAL_PAGOS AS hp
                                    LEFT JOIN
                                ALUMNO AS a ON a.ID_ALUMNO = hp.ID_ALUMNO
                            WHERE
                                a.RUT_ALUMNO = ?");
    $stmt->bind_param("s", $rutAlumno);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        while ($fila = $resultado->fetch_assoc()) {
            $fechaVencimiento = new DateTime($fila['FECHA_VENCIMIENTO']);
            $yearVencimiento = $fechaVencimiento->format('Y');
            
            if ($yearVencimiento < $yearActual) {
                // Agregar a saldo del período anterior
                $saldoPeriodoAnterior[] = $fila;
            } else {
                // Agregar a cuotas del período actual
                $cuotasPeriodoActual[] = $fila;
            }
        }
        $mensaje = "Datos encontrados.";
    } else {
        $mensaje = "No se encontraron datos para el RUT ingresado.";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Portal de Pago</title>
    <!-- Agrega los enlaces a los estilos de Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    
</head>
<body>
<?php if (!empty($mensaje)): ?>
    <div class="alert alert-info"><?php echo $mensaje; ?></div>
<?php endif; ?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h2 class="text-center">Portal de Pago</h2>
                </div>
                <div class="card-body">
                    <!-- Formulario de pago -->
                    <form method="post"> <!-- Agrega el método POST y la acción al formulario -->
                        <!-- Campo RUT del alumno -->
                        <div class="form-group">
                            <label for="rutAlumno">Rut del alumno:</label>
                            <input type="text" class="form-control" id="rutAlumno" name="rutAlumno" placeholder="Ingrese RUT del alumno" required>
                            <button type="submit" class="btn btn-primary custom-button mt-3" id="btnBuscarAlumno" name="btnBuscarAlumno">Buscar</button>
                        </div>
                        
                        <!-- Campo RUT del padre/poderado -->
                        <div class="form-group">
                            <label for="rutPadre">RUT del Padre/Apoderado</label>
                            <input type="text" class="form-control" id="rutPadre" placeholder="Ingrese el RUT del Padre/Apoderado">
                            <button type="button" class="btn btn-primary custom-button mt-3" id="btnBuscarApoderado">Buscar</button>
                        </div>


<!-- Tabla de pagos -->

<div id="datosAlumnos">
    <!-- Las tablas generadas se insertarán aquí -->
</div>
<div class="mt-4 table-responsive">
                            <h4>Saldo Periodo Anterior</h4>
                            <table class="table" id="tablaSaldoPeriodoAnterior">
                                <thead>
                                    <tr>
                                        <th>N° Cuota</th>
                                        <th>Fecha Vencimiento</th>
                                        <th>Monto</th>
                                        <th>Medio de Pago</th>
                                        <th>Fecha de Pago</th>
                                        <th>Estado</th>
                                        <th>Seleccione Valor a Pagar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($saldoPeriodoAnterior as $index => $pago): ?>
                                        <tr>
                                            <td><?php echo $index + 1; ?></td>
                                            <td><?php echo htmlspecialchars($pago['FECHA_VENCIMIENTO']); ?></td>
                                            <td><?php echo htmlspecialchars($pago['VALOR_ARANCEL']); ?></td>
                                            <td><?php echo htmlspecialchars($pago['MEDIO_PAGO']); ?></td>
                                            <td><?php echo htmlspecialchars($pago['FECHA_PAGO']); ?></td>
                                            <td><?php echo htmlspecialchars($pago['ESTADO_PAGO']); ?></td>
                                            <td><input type="checkbox" class="seleccionarPago" value="<?php echo htmlspecialchars($pago['VALOR_ARANCEL']); ?>"></td>

                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

<!-- Tabla de cuotas del periodo actual -->
<div class="mt-4 table-responsive">
                            <h4>Cuotas Periodo Actual</h4>
                            <table class="table" id="tablaCuotasPeriodoActual">
                                <thead>
                                    <tr>
                                        <th>N° Cuota</th>
                                        <th>Fecha Vencimiento</th>
                                        <th>Monto</th>
                                        <th>Medio de Pago</th>
                                        <th>Fecha de Pago</th>
                                        <th>Estado</th>
                                        <th>Seleccione Valor a Pagar</th>
                                    </tr>
                                </thead>
                                <tbody>
            <?php foreach ($cuotasPeriodoActual as $index => $pago): ?>
                <tr>
                    <td><?php echo $index + 1; ?></td>
                    <td><?php echo htmlspecialchars($pago['FECHA_VENCIMIENTO']); ?></td>
                    <td><?php echo htmlspecialchars($pago['VALOR_ARANCEL']); ?></td>
                    <td><?php echo htmlspecialchars($pago['MEDIO_PAGO']); ?></td>
                    <td><?php echo htmlspecialchars($pago['FECHA_PAGO']); ?></td>
                    <td><?php echo htmlspecialchars($pago['ESTADO_PAGO']); ?></td>
                    <td><input type="checkbox" class="seleccionarPago" value="<?php echo htmlspecialchars($pago['VALOR_ARANCEL']); ?>"></td>

                </tr>
            <?php endforeach; ?>
        </tbody>
                            </table>
                        </div>

                        <div>
                            <button type="button" class="btn btn-primary" id="btnSeleccionarValores">Seleccionar valores</button>
                        </div>

                        <!-- Sección "Total a Pagar $" -->
                        <div class="mt-4">
                            <h4 id="totalAPagar">Total a Pagar $</h4>
                            <h6>Seleccione Medio de Pago</h6>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="metodoPago" value="efectivo" id="efectivo">
                                <label class="form-check-label" for="efectivo">Efectivo</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="metodoPago" value="pagoPos" id="pagoPos">
                                <label class="form-check-label" for="pagoPos">Pago Tarjeta POS</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="metodoPago" value="cheque" id="cheque">
                                <label class="form-check-label" for="cheque">Cheque</label>
                            </div>
                        </div>

                        <!-- Sección "PAGO CON EFECTIVO" -->
                        <div id="seccionEfectivo" class="mt-4" style="display:none;">
                            <h4>PAGO CON EFECTIVO</h4>
                            <div class="form-group">
                                <label for="tipoDocumento">Tipo Documento</label>
                                <input type="text" class="form-control" id="tipoDocumento" placeholder="Ingrese el tipo de documento">
                            </div>
                            <div class="form-group">
                                <label for="montoEfectivo">Monto</label>
                                <input type="text" class="form-control" id="montoEfectivo" placeholder="Ingrese el monto">
                            </div>
                            <div class="form-group">
                                <label for="fechaPagoEfectivo">Fecha Pago</label>
                                <input type="date" class="form-control" id="fechaPagoEfectivo">
                            </div>
                        </div>
                        <!-- Sección "PAGO CON CHEQUE" -->
                        <div id="seccionCheque" class="mt-4" style="display:none;">
                            <h4>PAGO CON CHEQUE</h4>
                            <div class="form-group">
                                <label for="tipoDocumentoCheque">Tipo Documento</label>
                                <input type="text" class="form-control" id="tipoDocumentoCheque" placeholder="Ingrese el tipo de documento">
                            </div>
                            <div class="form-group">
                                <label for="numeroDocumentoCheque">N°Documento</label>
                                <input type="text" class="form-control" id="numeroDocumentoCheque" placeholder="Ingrese el número de documento">
                            </div>
                            <div class="form-group">
                                <label for="fechaEmisionCheque">Fecha Emisión</label>
                                <input type="date" class="form-control" id="fechaEmisionCheque">
                            </div>
                            <div class="form-group">
                                <label for="bancoCheque">Banco</label>
                                <input type="text" class="form-control" id="bancoCheque" placeholder="Ingrese el banco">
                            </div>
                            <div class="form-group">
                                <label for="montoCheque">Monto</label>
                                <input type="text" class="form-control" id="montoCheque" placeholder="Ingrese el monto">
                            </div>
                            <div class="form-group">
                                <label for="fechaDepositoCheque">Fecha Depósito</label>
                                <input type="date" class="form-control" id="fechaDepositoCheque">
                            </div>
                        </div>
                        <!-- Sección "PAGO CON TARJETA POS" -->
                        <div id="seccionPagoPos" class="mt-4" style="display:none;">
                            <h4>PAGO CON TARJETA POS</h4>
                            <div class="form-group">
                                <label for="tipoDocumentoPos">Tipo Documento</label>
                                <input type="text" class="form-control" id="tipoDocumentoPos" placeholder="Ingrese el tipo de documento">
                            </div>
                            <div class="form-group">
                                <label for="montoPos">Monto</label>
                                <input type="text" class="form-control" id="montoPos" placeholder="Ingrese el monto">
                            </div>
                            <div class="form-group">
                                <label for="fechaPagoPos">Fecha Pago</label>
                                <input type="date" class="form-control" id="fechaPagoPos">
                            </div>
                            <div class="form-group">
                                <label for="comprobantePos">N°Comprobante o Voucher</label>
                                <input type="text" class="form-control" id="comprobantePos" placeholder="Ingrese el número de comprobante o voucher">
                            </div>
                            <div class="form-group">
                                <label for="tipoTarjetaPos">Tipo Tarjeta</label>
                                <select class="form-control" id="tipoTarjetaPos">
                                    <option value="-">Selecciona un tipo de tarjeta</option>
                                    <option value="credito">Tarjeta Crédito</option>
                                    <option value="debito">Tarjeta Débito</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="cuotasPos">Cantidad de Cuotas</label>
                                <input type="text" class="form-control" id="cuotasPos" placeholder="Ingrese la cantidad de cuotas">
                            </div>
                        </div>
                        <!-- Botón "REGISTRAR PAGO" en azul -->
<button type="button" class="btn btn-primary btn-block mt-4" id="btnRegistrarPago">REGISTRAR PAGO</button>


                    </form>
                </div>   
            </div>
        </div>
    </div>
</div>

<!-- Agrega el script de Bootstrap -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<!-- ... Resto del HTML anterior ... -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
    var checkboxes = Array.from(document.querySelectorAll('input[type="checkbox"][name="seleccionarPago[]"]'));
    var btnSeleccionarValores = document.getElementById('btnSeleccionarValores');
    var totalPagarElement = document.querySelector('.total-pagar strong');
    var resumenValoresTableBody = document.querySelector('#resumenValores tbody');
    var rutAlumnoInput = document.getElementById('rutAlumno');
    var payWithTransferButton = document.getElementById('payWithTransfer');
    var transferPaymentForm = document.getElementById('transferPaymentForm');

    // Ordenar los checkboxes por fecha de vencimiento de forma ascendente
    checkboxes.sort(function(a, b) {
        var dateA = new Date(a.dataset.fechaVencimiento), dateB = new Date(b.dataset.fechaVencimiento);
        return dateA - dateB;
    });

    checkboxes.forEach(function(checkbox, index) {
        // Deshabilitar todos los checkboxes excepto el primero
        if(index > 0) checkbox.disabled = true;

        checkbox.addEventListener('change', function(event) {
            handleCheckboxChange(event.target, index, checkboxes);
        });
    });

    function handleCheckboxChange(changedCheckbox, changedIndex, allCheckboxes) {
        // Si se desmarca una casilla, también desmarca todas las casillas posteriores
        if (!changedCheckbox.checked) {
            for (var i = changedIndex + 1; i < allCheckboxes.length; i++) {
                allCheckboxes[i].checked = false;
                allCheckboxes[i].disabled = true;
            }
        } else {
            // Si se marca una casilla, habilita la siguiente casilla
            if (changedIndex + 1 < allCheckboxes.length) {
                allCheckboxes[changedIndex + 1].disabled = false;
            }
        }
    }

    document.getElementById('btnSeleccionarValores').addEventListener('click', function() {
        var checkboxes = document.querySelectorAll('.seleccionarPago:checked');
        var totalAPagar = 0;
        checkboxes.forEach(function(checkbox) {
            totalAPagar += parseFloat(checkbox.value);
        });
        document.getElementById('totalAPagar').textContent = 'Total a Pagar $' + totalAPagar.toFixed(2);
    });

    payWithTransferButton.addEventListener('click', function() {
        // Tomar el monto total a pagar del elemento de texto
        var totalAmount = totalPagarElement.textContent.replace('Total a pagar $', '').trim();

        // Asignar el monto total al input del formulario de Khipu
        document.getElementById('transferAmountToPay').value = totalAmount;

        // Enviar el formulario de Khipu
        transferPaymentForm.submit();
    });
});
document.addEventListener('DOMContentLoaded', function() {
        var btnSeleccionarValores = document.getElementById('btnSeleccionarValores');
        var totalAPagarElement = document.getElementById('totalAPagar');

        btnSeleccionarValores.addEventListener('click', function() {
            var checkboxes = document.querySelectorAll('.seleccionarPago:checked');
            var totalAPagar = 0;
            checkboxes.forEach(function(checkbox) {
                totalAPagar += parseFloat(checkbox.value);
            });
            totalAPagarElement.textContent = 'Total a Pagar $' + totalAPagar.toFixed(2);
        });
    });
</script>

</body>
</html>
