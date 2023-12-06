<?php
// Incluye la conexión a la base de datos
require_once 'db.php';

// Inicia sesión
session_start();

// Define una variable para el mensaje
$mensaje = '';

// Verifica si el usuario está logueado
if (!isset($_SESSION['correo_electronico'])) {
    header('Location: login.php');
    exit;
}

$correo_electronico = $_SESSION['correo_electronico'];
// Busca el id del usuario en la tabla 'usuarios'
$queryUsuario = "SELECT id FROM usuarios WHERE correo_electronico = '$correo_electronico'";
$resultadoUsuario = $conn->query($queryUsuario);

if ($resultadoUsuario->num_rows > 0) {
    $usuario = $resultadoUsuario->fetch_assoc();
    $id_usuario = $usuario['id'];

    // Buscar información de contacto de emergencia
    $queryContactoEmergencia = "SELECT * FROM contacto_emergencia WHERE id_usuario = $id_usuario";
    $resultadoContactoEmergencia = $conn->query($queryContactoEmergencia);

    if ($resultadoContactoEmergencia->num_rows > 0) {
        $contactoEmergencia = $resultadoContactoEmergencia->fetch_assoc();
    } else {
        $mensaje = "No se encontraron datos de contacto de emergencia.";
    }

    // Recuperar antecedentes médicos del usuario
    $queryAntecedentesMedicos = "SELECT * FROM antecedentes_medicos WHERE id_usuario = $id_usuario";
    $resultadoAntecedentesMedicos = $conn->query($queryAntecedentesMedicos);
} else {
    $mensaje = "Usuario no encontrado.";
    exit;
}

// Manejar la inserción de antecedentes médicos
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['agregar_antecedentes'])) {
    $categoria = $conn->real_escape_string($_POST['categoria']);
    $descripcion = $conn->real_escape_string($_POST['descripcion']);
    $fecha = $conn->real_escape_string($_POST['fecha']);

    // Inserta los antecedentes médicos en la base de datos
    $insertQuery = "INSERT INTO antecedentes_medicos (categoria, descripcion, fecha, id_usuario) VALUES ('$categoria', '$descripcion', '$fecha', $id_usuario)";
    if ($conn->query($insertQuery) === TRUE) {
        $mensaje = "Antecedentes médicos agregados correctamente.";
    } else {
        $mensaje = "Error al agregar los antecedentes médicos: " . $conn->error;
    }
}

// Manejar la eliminación de un antecedente médico
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['eliminar_antecedente'])) {
    $idAntecedente = $conn->real_escape_string($_POST['id_antecedente']);
    $deleteQuery = "DELETE FROM antecedentes_medicos WHERE id = $idAntecedente AND id_usuario = $id_usuario";
    if ($conn->query($deleteQuery) === TRUE) {
        $mensaje = "Antecedente médico eliminado correctamente.";
    } else {
        $mensaje = "Error al eliminar el antecedente médico: " . $conn->error;
    }
}

// Manejar la actualización de contacto de emergencia
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['actualizar_contacto'])) {
    // Recoger los datos del formulario
    $rut = $conn->real_escape_string($_POST['rut']);
    $nombres = $conn->real_escape_string($_POST['nombres']);
    $apellido_paterno = $conn->real_escape_string($_POST['apellido_paterno']);
    $apellido_materno = $conn->real_escape_string($_POST['apellido_materno']);
    $telefono = $conn->real_escape_string($_POST['telefono']);
    $correo_electronico = $conn->real_escape_string($_POST['correo_electronico']);

    // Actualizar los datos en la base de datos
    $updateQuery = "UPDATE contacto_emergencia SET rut='$rut', nombres='$nombres', apellido_paterno='$apellido_paterno', apellido_materno='$apellido_materno', telefono='$telefono', correo_electronico='$correo_electronico' WHERE id_usuario = $id_usuario";
    if ($conn->query($updateQuery) === TRUE) {
        $mensaje = "Contacto de emergencia actualizado correctamente.";
    
        // Realiza la consulta nuevamente para obtener datos actualizados
        $resultadoContactoEmergencia = $conn->query($queryContactoEmergencia);
        if ($resultadoContactoEmergencia->num_rows > 0) {
            $contactoEmergencia = $resultadoContactoEmergencia->fetch_assoc();
        }
    } else {
        $mensaje = "Error al actualizar el contacto de emergencia: " . $conn->error;
    }
}


?>
<?php if (!empty($mensaje)): ?>
        <div class="alert alert-success" role="alert">
            <?php echo $mensaje; ?>
        </div>
    <?php endif; ?>
<div class="emergency-contact">
    <h1>Contacto de emergencia</h1>
    <form method="post">
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="inputRUT">RUT (Sin puntos ni guion)</label>
                    <input type="text" name="rut" class="form-control" id="inputRUT" value="<?php echo $contactoEmergencia['rut'] ?? ''; ?>">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="inputNombres">Nombres</label>
                    <input type="text" name="nombres" class="form-control" id="inputNombres" value="<?php echo $contactoEmergencia['nombres'] ?? ''; ?>">
                </div>
                <div class="form-group col-md-4">
                    <label for="inputApellidoPaterno">Ap. Paterno</label>
                    <input type="text" name="apellido_paterno" class="form-control" id="inputApellidoPaterno" value="<?php echo $contactoEmergencia['apellido_paterno'] ?? ''; ?>">
                </div>
                <div class="form-group col-md-4">
                    <label for="inputApellidoMaterno">Ap. Materno</label>
                    <input type="text" name="apellido_materno" class="form-control" id="inputApellidoMaterno" value="<?php echo $contactoEmergencia['apellido_materno'] ?? ''; ?>">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="inputTelefono">Teléfono</label>
                    <input type="text" name="telefono" class="form-control" id="inputTelefono" value="<?php echo $contactoEmergencia['telefono'] ?? ''; ?>">
                </div>
                <div class="form-group col-md-6">
                    <label for="inputEmail">Correo Electrónico</label>
                    <input type="email" name="correo_electronico" class="form-control" id="inputEmail" value="<?php echo $contactoEmergencia['correo_electronico'] ?? ''; ?>">
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
            <?php
            $queryAntecedentesMedicos = "SELECT * FROM antecedentes_medicos WHERE id_usuario = $id_usuario";
            $resultadoAntecedentesMedicos = $conn->query($queryAntecedentesMedicos);
            while($fila = $resultadoAntecedentesMedicos->fetch_assoc()):
            ?>
            <tr>
                <td><?php echo htmlspecialchars($fila['categoria']); ?></td>
                <td><?php echo htmlspecialchars($fila['descripcion']); ?></td>
                <td><?php echo htmlspecialchars(date('d-m-Y', strtotime($fila['fecha']))); ?></td>
                <td>
                    <form action="" method="post">
                        <input type="hidden" name="id_antecedente" value="<?php echo $fila['id']; ?>">
                        <button type="submit" name="eliminar_antecedente" class="btn btn-danger">Eliminar</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
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
    document.addEventListener('DOMContentLoaded', function() {
        var inputRut = document.getElementById('inputRUT');
        
        inputRut.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    });
</script>
