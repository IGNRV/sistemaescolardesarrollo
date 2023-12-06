<?php
// Incluye la conexión a la base de datos
require_once 'db.php';

// Define una variable para el mensaje
$mensaje = '';

// Verifica si el usuario está logueado
if (!isset($_SESSION['EMAIL'])) {
    header('Location: login.php');
    exit;
}

$EMAIL = $_SESSION['EMAIL'];

// Función para buscar los datos del alumno y su contacto de emergencia
function buscarDatos($conn, $rutAlumno) {
    $stmt = $conn->prepare("SELECT ce.ID_CONTACTO, ce.RUT_APODERADO, a.RUT_ALUMNO, ce.ID_ALUMNO, ce.PARENTESCO, ce.NOMBRE, ce.AP_PATERNO, ce.AP_MATERNO, ce.MAIL_EMERGENCIA, ce.FONO_EMERGENCIA, ce.FECHA_INGRESO, ce.PERIODO_ESCOLAR, ce.STATUS, ce.DELETE_FLAG, ce.DATE_CREATED, ce.DATE_UPDATED FROM Alumno AS a LEFT JOIN Contacto_Emergencia AS ce ON ce.ID_ALUMNO = a.ID_ALUMNO WHERE a.RUT_ALUMNO = ?");
    $stmt->bind_param("s", $rutAlumno);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $stmt->close();
    return $resultado;
}

// Procesar el formulario de búsqueda
if (isset($_POST['buscarAlumno'])) {
    $rutAlumno = $_POST['rutAlumno'];
    $resultado = buscarDatos($conn, $rutAlumno);

    if ($resultado->num_rows > 0) {
        $contactoEmergencia = $resultado->fetch_assoc();
        $mensaje = "Alumno y contacto de emergencia encontrados.";
    } else {
        $mensaje = "Alumno no encontrado.";
    }
}

// Procesar el formulario de actualización
if (isset($_POST['actualizar_contacto'])) {
    $rut = $_POST['rut'];
    $nombre = $_POST['nombre'];
    $apPaterno = $_POST['ap_paterno'];
    $apMaterno = $_POST['ap_materno'];
    $fonoEmergencia = $_POST['fono_emergencia'];
    $mailEmergencia = $_POST['mail_emergencia'];
    $rutAlumno = $_POST['rutAlumno'];

    $stmt = $conn->prepare("UPDATE Contacto_Emergencia SET NOMBRE = ?, AP_PATERNO = ?, AP_MATERNO = ?, MAIL_EMERGENCIA = ?, FONO_EMERGENCIA = ? WHERE RUT_APODERADO = ?");
    $stmt->bind_param("ssssss", $nombre, $apPaterno, $apMaterno, $mailEmergencia, $fonoEmergencia, $rut);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $mensaje = "Contacto de emergencia actualizado con éxito.";
        $resultado = buscarDatos($conn, $rutAlumno);
        if ($resultado->num_rows > 0) {
            $contactoEmergencia = $resultado->fetch_assoc();
        }
    } else {
        $mensaje = "Error al actualizar el contacto de emergencia.";
    }
    $stmt->close();
}
$rutAlumno = isset($_POST['rutAlumno']) ? $_POST['rutAlumno'] : '';

?>
<?php if (!empty($mensaje)): ?>
        <div class="alert alert-success" role="alert">
            <?php echo $mensaje; ?>
        </div>
    <?php endif; ?>
<div class="emergency-contact">
    <h1>Contacto de emergencia</h1>
    <form method="post">
        <div class="form-group">
            <label for="rutAlumno">Rut del alumno:</label>
            <!-- Utiliza el valor de $rutAlumno para mantener el valor después de enviar el formulario -->
            <input type="text" class="form-control" id="rutAlumno" name="rutAlumno" placeholder="Ingrese RUT del alumno" value="<?php echo htmlspecialchars($rutAlumno); ?>">
            <button type="submit" class="btn btn-primary custom-button mt-3" name="buscarAlumno">Buscar</button>
        </div>
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="inputRUT">RUT (Sin puntos ni guion)</label>
                    <input type="text" name="rut" class="form-control" id="inputRUT" value="<?php echo isset($contactoEmergencia['RUT_APODERADO']) ? $contactoEmergencia['RUT_APODERADO'] : ''; ?>">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="inputNombres">Nombres</label>
                    <input type="text" name="nombre" class="form-control" id="inputNombres" value="<?php echo isset($contactoEmergencia['NOMBRE']) ? $contactoEmergencia['NOMBRE'] : ''; ?>">
                </div>
                <div class="form-group col-md-4">
                    <label for="inputApellidoPaterno">Ap. Paterno</label>
                    <input type="text" name="ap_paterno" class="form-control" id="inputApellidoPaterno" value="<?php echo isset($contactoEmergencia['AP_PATERNO']) ? $contactoEmergencia['AP_PATERNO'] : ''; ?>">
                </div>
                <div class="form-group col-md-4">
                    <label for="inputApellidoMaterno">Ap. Materno</label>
                    <input type="text" name="ap_materno" class="form-control" id="inputApellidoMaterno" value="<?php echo isset($contactoEmergencia['AP_MATERNO']) ? $contactoEmergencia['AP_MATERNO'] : ''; ?>">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="inputTelefono">Teléfono</label>
                    <input type="text" name="fono_emergencia" class="form-control" id="inputTelefono" value="<?php echo isset($contactoEmergencia['FONO_EMERGENCIA']) ? $contactoEmergencia['FONO_EMERGENCIA'] : ''; ?>">
                </div>
                <div class="form-group col-md-6">
                    <label for="inputEmail">Correo Electrónico</label>
                    <input type="email" name="mail_emergencia" class="form-control" id="inputEmail" value="<?php echo isset($contactoEmergencia['MAIL_EMERGENCIA']) ? $contactoEmergencia['MAIL_EMERGENCIA'] : ''; ?>">
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-block" name="actualizar_contacto">ACTUALIZAR CONTACTO DE EMERGENCIA</button>
        </form>
</div>

<div class="medical-record">
        <h2>Antecedentes Médicos (Enfermedades / Alergias)</h2>
        <!-- Formulario para agregar antecedentes médicos -->
        <table class="table">
            <thead>
                <tr>
                    <th>Categoría</th>
                    <th>Descripción</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>
        <tbody>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td>
                    <form action="" method="post">
                        <input type="hidden" name="id_antecedente" value="">
                        <button type="submit" name="eliminar_antecedente" class="btn btn-danger">Eliminar</button>
                    </form>
                </td>
            </tr>
        </tbody>
    </table>
        <form method="post">
            <div class="form-group">
                <label for="inputCategoria">Categoría</label>
                <input type="text" class="form-control" name="categoria" id="inputCategoria" required>
            </div>
            <div class="form-group">
                <label for="inputDescripcion">Descripción</label>
                <input type="text" class="form-control" name="descripcion" id="inputDescripcion" required>
            </div>
            <div class="form-group">
                <label for="inputFecha">Fecha</label>
                <input type="date" class="form-control" name="fecha" id="inputFecha" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block" name="agregar_antecedentes">AGREGAR ANTECEDENTES MÉDICOS</button>
        </form>
        <!-- Tabla de antecedentes médicos -->
    </div>
</div>
<script>

</script>
