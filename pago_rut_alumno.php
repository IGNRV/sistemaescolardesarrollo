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

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h2 class="text-center">Portal de Pago</h2>
                </div>
                <div class="card-body">
                    <!-- Formulario de pago -->
                    <form>
                        <!-- Campo RUT del alumno -->
                        <div class="form-group">
                            <label for="rutAlumno">Rut del alumno:</label>
                            <input type="text" class="form-control" id="rutAlumno" placeholder="Ingrese RUT del alumno">
                            <button type="button" class="btn btn-primary custom-button mt-3" id="btnBuscarAlumno">Buscar</button>
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
                              <!--           <th>Medio de Pago</th>
                                        <th>Fecha de Pago</th> -->
                                        <th>Estado</th>
                                        <th>Seleccione Valor a Pagar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Aquí irán las filas con la información de los pagos -->
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
                                    <!--     <th>Medio de Pago</th>
                                        <th>Fecha de Pago</th> -->
                                        <th>Estado</th>
                                        <th>Seleccione Valor a Pagar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Aquí irán las filas con la información de las cuotas del periodo actual -->
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
    document.getElementById('btnRegistrarPago').addEventListener('click', function() {
    var montoEfectivo = parseFloat(document.getElementById('montoEfectivo').value) || 0;
    var montoCheque = parseFloat(document.getElementById('montoCheque').value) || 0;
    var montoPos = parseFloat(document.getElementById('montoPos').value) || 0;

    var totalMontoIngresado = montoEfectivo + montoCheque + montoPos; // + otros montos si existen
    var totalAPagarTexto = document.getElementById('totalAPagar').textContent;
    var totalAPagar = parseFloat(totalAPagarTexto.split('$')[1]) || 0;

    if (totalMontoIngresado !== totalAPagar) {
        alert('La suma de los montos ingresados no es igual al total a pagar.');
        return;
    }

    // Recolectar los datos de los métodos de pago
    var idsCuotasSeleccionadas = Array.from(document.querySelectorAll('.cuota-checkbox:checked')).map(function(checkbox) {
        return checkbox.getAttribute('data-id-cuota');
    });

    var datosPago = {
        tipoDocumento: document.getElementById('tipoDocumento').value,
        montoEfectivo: montoEfectivo,
        fechaPagoEfectivo: document.getElementById('fechaPagoEfectivo').value,
        montoCheque: montoCheque, // Incluir monto y detalles del cheque
        tipoDocumentoCheque: document.getElementById('tipoDocumentoCheque').value,
        numeroDocumentoCheque: document.getElementById('numeroDocumentoCheque').value,
        fechaEmisionCheque: document.getElementById('fechaEmisionCheque').value,
        bancoCheque: document.getElementById('bancoCheque').value,
        fechaDepositoCheque: document.getElementById('fechaDepositoCheque').value,
        rutAlumno: document.getElementById('rutAlumno').value,
        idsCuotasSeleccionadas: idsCuotasSeleccionadas
    };

    // Código para enviar datosPago al servidor...
});

document.getElementById('btnBuscarAlumno').addEventListener('click', function() {
    var rutAlumno = document.getElementById('rutAlumno').value;
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'busca_alumno.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if (this.status == 200) {
            var response = JSON.parse(this.responseText);
            if(response.encontrado){
                actualizarTabla(response.datosAnterior, 'tablaSaldoPeriodoAnterior');
                actualizarTabla(response.datosActual, 'tablaCuotasPeriodoActual');
                actualizarEstadosDeCuotasVencidas(rutAlumno); // Nueva función para actualizar el estado
            } else {
                alert('Rut no encontrado');
            }
        }
    };
    xhr.send('rut=' + rutAlumno);
});

// Nueva función para actualizar los estados de las cuotas vencidas
function actualizarEstadosDeCuotasVencidas(rutAlumno) {
    var xhrActualizar = new XMLHttpRequest();
    xhrActualizar.open('POST', 'actualizar_cuotas_vencidas.php', true);
    xhrActualizar.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhrActualizar.onload = function() {
        if (this.status == 200) {
            console.log("Cuotas actualizadas: " + this.responseText);
        }
    };
    xhrActualizar.send('rut=' + rutAlumno);
}

function actualizarTabla(datos, idTabla) {
    var tbody = document.getElementById(idTabla).getElementsByTagName('tbody')[0];
    tbody.innerHTML = ''; // Limpiar la tabla actual

    // Ordenar los datos por fecha de vencimiento
    datos.sort((a, b) => new Date(a.fecha_cuota_deuda) - new Date(b.fecha_cuota_deuda));

    var checkboxAnteriorHabilitado = true; // Indica si el checkbox anterior está habilitado

    datos.forEach(function(cuota, index) {
        var row = tbody.insertRow();
        row.insertCell(0).innerHTML = index + 1; // N° Cuota
        row.insertCell(1).innerHTML = cuota.fecha_cuota_deuda; // Fecha Vencimiento
        row.insertCell(2).innerHTML = cuota.monto; // Monto
        row.insertCell(3).innerHTML = ''; // Medio de Pago
        row.insertCell(4).innerHTML = ''; // Fecha de Pago
        var estado = cuota.estado_cuota === '0' ? 'VIGENTE' : (cuota.estado_cuota === '1' ? 'VENCIDA' : 'PAGADA');
        row.insertCell(5).innerHTML = estado; // Estado

        var cellCheck = row.insertCell(6); // Celda para el checkbox
        var checkbox = document.createElement('input');
        checkbox.type = 'checkbox';
        checkbox.classList.add('cuota-checkbox');
        checkbox.setAttribute('data-id-cuota', cuota.id); // Agregar el ID de la cuota
        checkbox.value = cuota.monto;

        // Habilitar solo el primer checkbox no pagado y los siguientes en función del anterior
        checkbox.disabled = !checkboxAnteriorHabilitado || cuota.estado_cuota === '2';
        if (cuota.estado_cuota !== '2') {
            checkboxAnteriorHabilitado = false;
        }
        
        cellCheck.appendChild(checkbox);
    });

    // Añadir evento para habilitar el siguiente checkbox
    var checkboxes = document.querySelectorAll('.cuota-checkbox');
    checkboxes.forEach(function(checkbox, index) {
        checkbox.addEventListener('change', function() {
            if (index < checkboxes.length - 1) {
                checkboxes[index + 1].disabled = !checkbox.checked;
            }
        });
    });
}

document.getElementById('btnSeleccionarValores').addEventListener('click', function() {
    var checkboxes = document.querySelectorAll('.cuota-checkbox:checked');
    var total = 0;
    checkboxes.forEach(function(checkbox) {
        total += parseFloat(checkbox.value);
    });
    document.getElementById('totalAPagar').textContent = 'Total a Pagar $ ' + total.toFixed(2);
});

function togglePaymentSections() {
    var efectivoChecked = document.getElementById('efectivo').checked;
    var pagoPosChecked = document.getElementById('pagoPos').checked;
    var chequeChecked = document.getElementById('cheque').checked;

    document.getElementById('seccionEfectivo').style.display = efectivoChecked ? 'block' : 'none';
    document.getElementById('seccionPagoPos').style.display = pagoPosChecked ? 'block' : 'none';
    document.getElementById('seccionCheque').style.display = chequeChecked ? 'block' : 'none';
}

var metodosPago = document.querySelectorAll('input[name="metodoPago"]');
metodosPago.forEach(function(metodo) {
    metodo.addEventListener('change', togglePaymentSections);
});

var metodosPago = document.querySelectorAll('input[name="metodoPago"]');
metodosPago.forEach(function(metodo) {
    metodo.addEventListener('change', togglePaymentSections);
});

let rutAlumnoGlobal = '';

// Manejador del evento click para el botón REGISTRAR PAGO
document.querySelector('.btn-primary.btn-block.mt-4').addEventListener('click', function() {
    var montoEfectivo = parseFloat(document.getElementById('montoEfectivo').value) || 0;
    var montoCheque = parseFloat(document.getElementById('montoCheque').value) || 0;
    var montoPos = parseFloat(document.getElementById('montoPos').value) || 0;
    var rutPadre = document.getElementById('rutPadre').value; // Añadir esta línea para obtener el RUT del apoderado
    var rutAlumno = rutAlumnoGlobal || document.getElementById('rutAlumno').value;



    var totalMontoIngresado = montoEfectivo + montoCheque + montoPos;
    var totalAPagarTexto = document.getElementById('totalAPagar').textContent;
    var totalAPagar = parseFloat(totalAPagarTexto.split('$')[1]) || 0;

    if (totalMontoIngresado !== totalAPagar) {
        alert('La suma de los montos ingresados no es igual al total a pagar.');
        return;
    }

    // Recolectar los datos de los métodos de pago
    var idsCuotasSeleccionadas = Array.from(document.querySelectorAll('.cuota-checkbox:checked')).map(function(checkbox) {
        return checkbox.getAttribute('data-id-cuota');
    });

    var datosPago = {
        tipoDocumento: document.getElementById('tipoDocumento').value,
        montoEfectivo: montoEfectivo,
        fechaPagoEfectivo: document.getElementById('fechaPagoEfectivo').value,
        rutAlumno: document.getElementById('rutAlumno').value,
        idsCuotasSeleccionadas: idsCuotasSeleccionadas,
        rutPadre: document.getElementById('rutPadre').value,
        rutAlumno: rutAlumno,
    };

    // Añadir lógica para el pago con cheque
    if (document.getElementById('cheque').checked) {
        datosPago.tipoDocumentoCheque = document.getElementById('tipoDocumentoCheque').value;
        datosPago.numeroDocumentoCheque = document.getElementById('numeroDocumentoCheque').value;
        datosPago.fechaEmisionCheque = document.getElementById('fechaEmisionCheque').value;
        datosPago.bancoCheque = document.getElementById('bancoCheque').value; // Se recoge el valor del banco
        datosPago.montoCheque = montoCheque;
        datosPago.fechaDepositoCheque = document.getElementById('fechaDepositoCheque').value;
    }

    if (document.getElementById('pagoPos').checked) {
        datosPago.tipoDocumentoPos = document.getElementById('tipoDocumentoPos').value;
        datosPago.montoPos = parseFloat(document.getElementById('montoPos').value) || 0;
        datosPago.fechaPagoPos = document.getElementById('fechaPagoPos').value;
        datosPago.comprobantePos = document.getElementById('comprobantePos').value;
        datosPago.tipoTarjetaPos = document.getElementById('tipoTarjetaPos').value;
        datosPago.cuotasPos = parseInt(document.getElementById('cuotasPos').value) || 0; // Aquí se añade el número de cuotas

    }

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'registrar_pago.php', true);
    xhr.setRequestHeader('Content-Type', 'application/json;charset=UTF-8');
    xhr.onload = function() {
        if (this.status == 200) {
            alert("Respuesta del servidor: " + this.responseText);
            window.location.reload();
        } else {
            alert("Error al procesar el pago.");
        }
    };
    xhr.send(JSON.stringify(datosPago));
});

// Asumiendo que el resto del código se mantiene sin cambios...

document.getElementById('btnBuscarApoderado').addEventListener('click', function() {
    var rutPadre = document.getElementById('rutPadre').value.trim();
    if(rutPadre === '') {
        alert('Por favor, ingrese un RUT.');
        return;
    }

    // Ocultar las tablas de periodos anteriores y actuales antes de la búsqueda
    var tablasPeriodos = document.querySelectorAll('.table-responsive');
    tablasPeriodos.forEach(function(tabla) {
        tabla.style.display = 'none';
    });


    // Limpiamos el contenedor antes de cargar nuevos datos
    var contenedorDatosAlumnos = document.getElementById('datosAlumnos');
    contenedorDatosAlumnos.innerHTML = '';

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'busca_padres_apoderados.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.onload = function () {
        if (this.status == 200) {
            var respuesta = JSON.parse(this.responseText);
            if (respuesta.encontrado) {
                var primerIdAlumno = Object.keys(respuesta.datos)[0];
                var primerAño = Object.keys(respuesta.datos[primerIdAlumno])[0];
                rutAlumnoGlobal = respuesta.datos[primerIdAlumno][primerAño][0].rut_alumno;
                Object.keys(respuesta.datos).forEach(function(idAlumno) {
                    // Para cada ID de alumno, creamos las tablas de saldos y cuotas solo una vez.
                    Object.keys(respuesta.datos[idAlumno]).sort().forEach(function(año, index, array) {
                        var datos = respuesta.datos[idAlumno][año];
                        var tablaId = 'tabla_' + idAlumno + '_' + año;
                        var esAnioActual = año == new Date().getFullYear();

                        // Verificamos si ya se creó un contenedor para este alumno
                        var contenedorAlumno = document.getElementById('contenedor_' + idAlumno);
                        if (!contenedorAlumno) {
                            contenedorAlumno = document.createElement('div');
                            contenedorAlumno.id = 'contenedor_' + idAlumno;
                            contenedorDatosAlumnos.appendChild(contenedorAlumno);
                        }

                        // Creamos y agregamos las tablas al contenedor correspondiente al alumno
                        contenedorAlumno.innerHTML += `
                        <h4>${esAnioActual ? 'Cuotas Periodo Actual' : 'Saldo Periodo Anterior'} para el alumno ID: ${idAlumno}</h4>
                        <div class="table-responsive">
                            <table class="table" id="${tablaId}">
                                <thead>
                                    <tr>
                                        <th>N° Cuota</th>
                                        <th>Fecha Vencimiento</th>
                                        <th>Monto</th>
                                        <th>Estado</th>
                                        <th>Seleccione Valor a Pagar</th>
                                    </tr>
                                </thead>
                                <tbody> ... </tbody>
                            </table>
                        </div>
                    `;

                        // Llamamos a actualizarTabla para llenar la tabla con los datos correspondientes
                        actualizarTabla(datos, tablaId);
                    });
                });
            } else {
                alert('No se encontraron datos para el RUT ingresado.');
            }
        } else {
            alert('Hubo un error al procesar la búsqueda.');
        }
    };
    xhr.send('rutPadre=' + rutPadre);
});


function actualizarTabla(datos, idTabla) {
    var tbody = document.getElementById(idTabla).querySelector('tbody');
    tbody.innerHTML = ''; // Limpiar la tabla actual

    datos.forEach(function(cuota, index) {
        var row = tbody.insertRow();
        row.insertCell(0).textContent = index + 1; // N° Cuota
        row.insertCell(1).textContent = cuota.fecha_cuota_deuda; // Fecha Vencimiento
        row.insertCell(2).textContent = cuota.monto; // Monto
        var estado = cuota.estado_cuota === '0' ? 'VIGENTE' : (cuota.estado_cuota === '1' ? 'VENCIDA' : 'PAGADA');
        row.insertCell(3).textContent = estado; // Estado
        
        var cellCheck = row.insertCell(4); // Celda para el checkbox
        var checkbox = document.createElement('input');
        checkbox.type = 'checkbox';
        checkbox.classList.add('cuota-checkbox');
        checkbox.setAttribute('data-id-cuota', cuota.id);
        checkbox.value = cuota.monto;
        checkbox.disabled = cuota.estado_cuota === '2'; // Deshabilitar si la cuota está pagada
        cellCheck.appendChild(checkbox);
    });
}


</script>
</body>
</html>
