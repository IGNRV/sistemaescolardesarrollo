
<?php
ini_set('display_errors', 1);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Cuadratura de Caja Diaria</title>
    <!-- Agrega los enlaces a los estilos de Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        /* Estilo personalizado para el tamaño de letra del título */
        .custom-title {
            font-size: 1.5em;
        }

        /* Estilo personalizado para hacer la tabla responsiva */
        .table-responsive {
            overflow-x: auto;
        }

        /* Estilo personalizado para ajustar el ancho del contenedor */
        .custom-container {
            max-width: 600px; /* Ajusta el ancho según tus preferencias */
            margin: auto;
            margin-top: 20px; /* Ajusta el margen superior según tus preferencias */
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.23/jspdf.plugin.autotable.min.js"></script>

</head>
<body>

<div class="container mt-5 custom-container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <!-- Título personalizado -->
                    <h2 class="text-center custom-title">CUADRATURA DE CAJA DIARIA</h2>
                </div>
                <div class="card-body">
                    <!-- Formulario de cuadratura de caja -->
                    <form>
                        <!-- Campos de selección de fecha y medio de pago -->
                        <div class="form-group">
                            <label for="fecha">Fecha</label>
                            <input type="date" class="form-control" id="fecha">
                        </div>
                        
                        <div class="form-group">
                            <label for="medioPago">Medio de Pago</label>
                            <select class="form-control" id="medioPago">
                                <option value="1">Efectivo</option>
                                <option value="3">Cheque</option>
                                <option value="2">Tarjeta POS</option>
                                <option value="4">Khipu</option>
                                <!-- Agrega más opciones según sea necesario -->
                            </select>
                        </div>

                        <!-- Botón para realizar la cuadratura -->
                        <button type="button" class="btn btn-primary btn-block" id="btnBuscar">Buscar</button>
                    </form>

                    <!-- Tabla de Pago con Efectivo -->
                    <!-- <button type="submit" class="btn btn-primary btn-block">Selecciona Valores</button> -->
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12 mt-4">
        <div class="card">
            <div class="card-header">
            <!-- Título personalizado con un ID para mostrar el total recaudado -->
            <h2 class="text-center custom-title" id="totalRecaudado">TOTAL RECAUDADO $</h2>
                        <h4 class="section-title">PAGO CON EFECTIVO</h4>
            </div>
                    <div class="table-responsive mt-4">
                        
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Fecha Pago</th>
                                    <th>Monto</th>
                                    <th>Medio de Pago</th>
                                    <th>Tipo Documento</th>
                                    <th>Estado</th>
                                    <th>RUT Alumno</th>
                                </tr>
                            </thead>
                            <tbody id="tablaEfectivo">
                                <!-- Agrega filas de datos según tus necesidades -->
                                <tr>
                                    <td>Fecha de Pago</td>
                                    <td>Monto</td>
                                    <td>Medio de Pago</td>
                                    <td>Tipo de Documento</td>
                                    <td>Estado</td>
                                    <td>RUT del Alumno</td>
                                </tr>
                                <!-- Puedes agregar más filas según sea necesario -->
                            </tbody>
                        </table>
                        </div>
            </div>
        </div>
    </div>

    <!-- Nuevo contenedor para PAGO CON CHEQUE -->
    <div class="col-md-12 mt-4">
        <div class="card">
            <div class="card-header">
                <!-- Título y subtítulo personalizados -->
                <h2 class="text-center custom-title" id="totalRecaudadoCheque">TOTAL RECAUDADO $</h2>
                <h5 class="text-center">PAGO CON CHEQUE</h5>
            </div>
            <div class="card-body">
                <!-- Tabla de Pago con Cheque -->
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Fecha Pago</th>
                                <th>Monto</th>
                                <th>Medio de Pago</th>
                                <th>Tipo Documento</th>
                                <th>Estado</th>
                                <th>RUT Alumno</th>
                            </tr>
                        </thead>
                        <tbody id="tablaCheque">
                            <!-- Agrega filas de datos según tus necesidades -->
                            <tr>
                                <td>Fecha de Pago</td>
                                <td>Monto</td>
                                <td>Medio de Pago</td>
                                <td>Tipo de Documento</td>
                                <td>Estado</td>
                                <td>RUT del Alumno</td>
                            </tr>
                            <!-- Puedes agregar más filas según sea necesario -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Nuevo contenedor para PAGO CON CHEQUE -->
    <div class="col-md-12 mt-4">
        <div class="card">
            <div class="card-header">
                <!-- Título y subtítulo personalizados -->
                <h3 class="text-center custom-title" id="totalRecaudadoTarjetaPOS">TOTAL RECAUDADO $</h3>
                <h5 class="text-center">PAGO CON TARJETA POS</h5>
            </div>
            <div class="card-body">
                <!-- Tabla de Pago con Cheque -->
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Fecha Pago</th>
                                <th>Monto</th>
                                <th>Medio de Pago</th>
                                <th>Tipo Documento</th>
                                <th>Estado</th>
                                <th>RUT Alumno</th>
                            </tr>
                        </thead>
                        <tbody id="tablaTarjetaPOS">
                            <!-- Agrega filas de datos según tus necesidades -->
                            <tr>
                                <td>Fecha de Pago</td>
                                <td>Monto</td>
                                <td>Medio de Pago</td>
                                <td>Tipo de Documento</td>
                                <td>Estado</td>
                                <td>RUT del Alumno</td>
                            </tr>
                            <!-- Puedes agregar más filas según sea necesario -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 mt-4">
        <div class="card">
            <div class="card-header">
                <!-- Título y subtítulo personalizados -->
                <h3 class="text-center custom-title" id="totalRecaudadoKhipu">TOTAL RECAUDADO $</h3>
                <h5 class="text-center">PAGO CON KHIPU</h5>
            </div>
            <div class="card-body">
                <!-- Tabla de Pago con Cheque -->
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Fecha Pago</th>
                                <th>Monto</th>
                                <th>Medio de Pago</th>
                                <th>Tipo Documento</th>
                                <th>Estado</th>
                                <th>RUT Alumno</th>
                            </tr>
                        </thead>
                        <tbody id="tablaKhipu">
                            <!-- Agrega filas de datos según tus necesidades -->
                            <tr>
                                <td>Fecha de Pago</td>
                                <td>Monto</td>
                                <td>Medio de Pago</td>
                                <td>Tipo de Documento</td>
                                <td>Estado</td>
                                <td>RUT del Alumno</td>
                            </tr>
                            <!-- Puedes agregar más filas según sea necesario -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <h5 class="text-center" id="totalRecaudadoGeneral">TOTAL RECAUDADO $</h5>
    <button type="button" class="btn btn-primary btn-block" id="btnGenerarReporte">Generar Reporte</button>

</div>

<!-- Agrega el script de Bootstrap -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script>
var datosEfectivo = [];
var totalEfectivoGlobal = 0;
var datosCheque = [];
var totalChequeGlobal = 0;
var datosTarjetaPOS = [];
var totalTarjetaPOSGlobal = 0;
var datosKhipu = [];
var totalKhipuGlobal = 0;

document.getElementById('btnBuscar').addEventListener('click', function() {
    var fecha = document.getElementById('fecha').value;
    var medioPagoSeleccionado = document.getElementById('medioPago').value;

    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'busca_pagos.php?fecha=' + fecha + '&medioPago=' + medioPagoSeleccionado, true);
    xhr.onload = function() {
        if (this.status == 200) {
            var respuesta = JSON.parse(this.responseText);
            switch (medioPagoSeleccionado) {
                case "1": // Efectivo
                    datosEfectivo = respuesta;
                    totalEfectivoGlobal = actualizarTabla(datosEfectivo, 'tablaEfectivo', 1);
                    document.getElementById('totalRecaudado').textContent = 'TOTAL RECAUDADO $' + totalEfectivoGlobal.toFixed(0);
                    break;
                case "3": // Cheque
                    datosCheque = respuesta;
                    totalChequeGlobal = actualizarTabla(datosCheque, 'tablaCheque', 3);
                    document.getElementById('totalRecaudadoCheque').textContent = 'TOTAL RECAUDADO $' + totalChequeGlobal.toFixed(0);
                    break;
                case "2": // Tarjeta POS
                    datosTarjetaPOS = respuesta;
                    totalTarjetaPOSGlobal = actualizarTabla(datosTarjetaPOS, 'tablaTarjetaPOS', 2);
                    document.getElementById('totalRecaudadoTarjetaPOS').textContent = 'TOTAL RECAUDADO $' + totalTarjetaPOSGlobal.toFixed(0);
                    break;
                case "4": // Khipu
                    datosKhipu = respuesta;
                    totalKhipuGlobal = actualizarTabla(datosKhipu, 'tablaKhipu', 4);
                    document.getElementById('totalRecaudadoKhipu').textContent = 'TOTAL RECAUDADO $' + totalKhipuGlobal.toFixed(0);
                    break;
            }
             // Actualizar el total general
             var totalRecaudadoGeneral = totalEfectivoGlobal + totalChequeGlobal + totalTarjetaPOSGlobal + totalKhipuGlobal;
                document.getElementById('totalRecaudadoGeneral').textContent = 'TOTAL RECAUDADO $' + totalRecaudadoGeneral.toFixed(0);
        } else {
            document.getElementById('tablaEfectivo').innerHTML = '<tr><td colspan="6">No se han encontrado datos</td></tr>';
            document.getElementById('tablaCheque').innerHTML = '<tr><td colspan="6">No se han encontrado datos</td></tr>';
            document.getElementById('tablaTarjetaPOS').innerHTML = '<tr><td colspan="6">No se han encontrado datos</td></tr>';
            document.getElementById('tablaKhipu').innerHTML = '<tr><td colspan="6">No se han encontrado datos</td></tr>';
        }
    };
    xhr.send();
});

function actualizarTabla(datos, idTabla, medioPago) {
    var tabla = document.getElementById(idTabla);
    var html = '';
    var total = 0;
    // Objeto de mapeo para convertir números a texto para los medios de pago
    var mediosDePago = {
        '1': 'Efectivo',
        '2': 'Tarjeta POS',
        '3': 'Cheque',
        '4': 'Khipu'
    };
    // Mapeo de estados
    var estados = {
        '1': 'Pagado'
        // Aquí puedes agregar más estados si los hay
    };

    datos.forEach(function(fila) {
        var medioPagoTexto = mediosDePago[fila.medio_de_pago] || fila.medio_de_pago;
        var estadoTexto = estados[fila.estado] || fila.estado; // Usa la representación de texto del estado
        if (fila.medio_de_pago == medioPago) {
            total += parseFloat(fila.valor);
            html += `<tr>
                        <td>${fila.fecha_pago}</td>
                        <td>${fila.valor}</td>
                        <td>${medioPagoTexto}</td>
                        <td>${fila.tipo_documento}</td>
                        <td>${estadoTexto}</td> <!-- Usa estadoTexto aquí -->
                        <td>${fila.rut_alumno}</td>
                    </tr>`;
        }
    });

    tabla.innerHTML = html;
    return total;
}


document.getElementById('btnGenerarReporte').addEventListener('click', function() {
    var doc = new jspdf.jsPDF();
    var finalY = 10; // Inicializamos el eje Y para que comience después del título del reporte

    doc.setFontSize(18);
    doc.text('Reporte de Cuadratura de Caja', 14, finalY);

    finalY += 10; // Espacio después del título

    // Agregar tablas y totales al PDF
    var mediosPago = ['Efectivo', 'Cheque', 'TarjetaPOS', 'Khipu'];
    mediosPago.forEach(function(medioPago, index) {
        // Convertir el id para que coincida con el HTML
        var seccion = medioPago.charAt(0).toUpperCase() + medioPago.slice(1).toLowerCase();
        
        doc.setFontSize(14);
        finalY += 7; // Espacio antes de cada sección
        doc.text('Pago con ' + seccion, 14, finalY);

        doc.setFontSize(11);
        finalY += 3; // Espacio para comenzar la tabla
        doc.autoTable({ 
            html: '#tabla' + seccion,
            startY: finalY,
            margin: { top: 30 },
            didDrawPage: function (data) {
                finalY = data.cursor.y; // Actualizar finalY al final de cada tabla
            }
        });

        // Mostrar total recaudado por sección
        var totalId = 'totalRecaudado' + (medioPago === 'Efectivo' ? '' : seccion);
        var totalElement = document.getElementById(totalId);
        if (totalElement) {
            finalY += 7; // Espacio antes del total
            doc.text(totalElement.textContent, 14, finalY);
            finalY += 5; // Espacio después del total antes de la siguiente sección
        }
    });

    // Agregar total general al final
    var totalGeneralElement = document.getElementById('totalRecaudadoGeneral');
    if (totalGeneralElement) {
        finalY += 7; // Espacio antes del total general
        doc.text(totalGeneralElement.textContent, 14, finalY);
    }

    // Guardar el PDF
    doc.save('reporte_cuadratura.pdf');
});


</script>


</body>
</html>
