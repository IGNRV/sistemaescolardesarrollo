<?php
// Incluye la conexión a la base de datos
require_once 'db.php';
ini_set('display_errors', 1);

// Inicia sesión

// Define una variable para el mensaje
$mensaje = '';

// Verifica si el usuario está logueado y obtiene su id
if (!isset($_SESSION['EMAIL'])) {
    header('Location: login.php');
    exit;
} else {
    $EMAIL = $_SESSION['EMAIL'];
    // Busca el id del usuario en la tabla 'usuarios'
    $queryUsuario = "SELECT ID FROM USERS WHERE EMAIL = '$EMAIL'";
    $resultadoUsuario = $conn->query($queryUsuario);
    if ($resultadoUsuario->num_rows > 0) {
        $usuario = $resultadoUsuario->fetch_assoc();
        $id_usuario = $usuario['ID'];
    } else {
        // Manejar el error si el usuario no se encuentra
        $mensaje = "Usuario no encontrado.";
        exit;
    }
}

// Verifica si se ha enviado el formulario de búsqueda
if (isset($_POST['buscarAlumno'])) {
    $rutAlumno = $_POST['rutAlumno'];
    // Preparar la consulta SQL para buscar el alumno
    $stmt = $conn->prepare("SELECT * FROM ALUMNO WHERE RUT_ALUMNO = ?");
    $stmt->bind_param("s", $rutAlumno);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        // Alumno encontrado
        $mensaje = "Alumno encontrado.";
        $alumno = $resultado->fetch_assoc();
        // Aquí puedes asignar los valores a las variables para mostrarlos en el formulario
    } else {
        // Alumno no encontrado
        $mensaje = "Alumno no encontrado.";
    }
    $stmt->close();
}

// Verifica si se ha enviado el formulario de actualización
if (isset($_POST['actualizar'])) {
    // Recoge los datos del formulario
    $nombre = $_POST['name'];
    $apPaterno = $_POST['ap_paterno'];
    $apMaterno = $_POST['ap_materno'];
    $fechaNac = $_POST['fecha_nac'];
    $rutAlumno = $_POST['rut_alumno'];
    $rda = $_POST['rda'];
    $calle = $_POST['calle'];
    $nroCalle = $_POST['nro_calle'];
    $obsDireccion = $_POST['obs_direccion'];
    $villa = $_POST['villa'];
    $comuna = $_POST['comuna'];
    $idRegion = $_POST['id_region'];
    $mail = $_POST['mail'];
    $fono = $_POST['fono'];

    // Prepara la consulta SQL para actualizar el alumno
    $stmt = $conn->prepare("UPDATE Alumno SET NOMBRE = ?, AP_PATERNO = ?, AP_MATERNO = ?, FECHA_NAC = ?, RDA = ?, CALLE = ?, NRO_CALLE = ?, OBS_DIRECCION = ?, VILLA = ?, COMUNA = ?, ID_REGION = ?, MAIL = ?, FONO = ? WHERE RUT_ALUMNO = ?");
    $stmt->bind_param("ssssssssssssss", $nombre, $apPaterno, $apMaterno, $fechaNac, $rda, $calle, $nroCalle, $obsDireccion, $villa, $comuna, $idRegion, $mail, $fono, $rutAlumno);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $mensaje = "Datos del alumno actualizados con éxito.";
        
        // Vuelve a buscar los datos del alumno para mostrar los datos actualizados
        $stmt = $conn->prepare("SELECT * FROM Alumno WHERE RUT_ALUMNO = ?");
        $stmt->bind_param("s", $rutAlumno);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            $alumno = $resultado->fetch_assoc();
        }
    } else {
        $mensaje = "No se pudo actualizar los datos del alumno.";
    }
    $stmt->close();
}

?>
<?php if (!empty($mensaje)): ?>
    <div class="alert alert-success" role="alert">
        <?php echo $mensaje; ?>
    </div>
<?php endif; ?>
<form action="" method="post">
                        <div class="form-group">
                            <label for="rutAlumno">Rut del alumno:</label>
                            <!-- Agrega el atributo 'name' al input y cambia el botón para que envíe el formulario -->
                            <input type="text" class="form-control" id="rutAlumno" name="rutAlumno" placeholder="Ingrese RUT del alumno">
                            <button type="submit" class="btn btn-primary custom-button mt-3" name="buscarAlumno">Buscar</button>

                        </div>
</form>


<h1 class="text-center">Datos del alumno</h1>
            <!-- Formulario de datos del alumno -->
            <form action="" method="post">
                <input type="hidden" name="rut" value="<?php echo $rut; ?>">
                <div class="form-group">
        <label>Nombre:</label>
        <input type="text" class="form-control" name="name" value="<?php echo isset($alumno['NOMBRE']) ? $alumno['NOMBRE'] : ''; ?>">
    </div>
                <div class="form-group">
                    <label>Apellido Paterno:</label>
                    <input type="text" class="form-control" name="ap_paterno" value="<?php echo isset($alumno['AP_PATERNO']) ? $alumno['AP_PATERNO'] : ''; ?>">

                </div>
                <div class="form-group">
                    <label>Apellido Materno:</label>
                    <input type="text" class="form-control" name="ap_materno" value="<?php echo isset($alumno['AP_MATERNO']) ? $alumno['AP_MATERNO'] : ''; ?>">
                </div>
                <div class="form-group">
                    <label>Fecha de Nacimiento:</label>
                    <input type="text" class="form-control" name="fecha_nac" value="<?php echo isset($alumno['FECHA_NAC']) ? $alumno['FECHA_NAC'] : ''; ?>">
                </div>
                <div class="form-group">
                    <label>RUT:</label>
                    <input type="text" class="form-control" name="rut_alumno" value="<?php echo isset($alumno['RUT_ALUMNO']) ? $alumno['RUT_ALUMNO'] : ''; ?>">
                </div>
                <div class="form-group">
                    <label>RDA:</label>
                    <input type="text" class="form-control" name="rda" value="<?php echo isset($alumno['RDA']) ? $alumno['RDA'] : ''; ?>">
                </div>
            
                <div class="form-group">
                    <label>Calle:</label>
                    <input type="text" class="form-control" name="calle" value="<?php echo isset($alumno['CALLE']) ? $alumno['CALLE'] : ''; ?>">
                </div>
                <div class="form-group">
                    <label>Número:</label>
                    <input type="text" class="form-control" name="nro_calle" value="<?php echo isset($alumno['NRO_CALLE']) ? $alumno['NRO_CALLE'] : ''; ?>">
                </div>
                <div class="form-group">
                    <label>Resto Dirección:</label>
                    <input type="text" class="form-control" name="obs_direccion" value="<?php echo isset($alumno['OBS_DIRECCION']) ? $alumno['OBS_DIRECCION'] : ''; ?>">
                </div>
                <div class="form-group">
                    <label>Villa/Población:</label>
                    <input type="text" class="form-control" name="villa" value="<?php echo isset($alumno['VILLA']) ? $alumno['VILLA'] : ''; ?>">
                </div>
                <div class="form-group">
                    <label>Comuna:</label>
                    <input type="text" class="form-control" name="comuna" value="<?php echo isset($alumno['COMUNA']) ? $alumno['COMUNA'] : ''; ?>">
                </div>
                <div class="form-group">
                    <label>Ciudad:</label>
                    <input type="text" class="form-control" name="id_region" value="<?php echo isset($alumno['ID_REGION']) ? $alumno['ID_REGION'] : ''; ?>">
                </div>
                <div class="form-group">
                    <label>Email:</label>
                    <input type="email" class="form-control" name="mail" value="<?php echo isset($alumno['MAIL']) ? $alumno['MAIL'] : ''; ?>">
                </div>
                <div class="form-group">
                    <label>Número de teléfono:</label>
                    <input type="text" class="form-control" name="fono" value="<?php echo isset($alumno['FONO']) ? $alumno['FONO'] : ''; ?>">
                </div>
                <!-- Botón de actualizar con clase Bootstrap y personalizada -->
                <button type="submit" class="btn btn-primary btn-block custom-button" name="actualizar">Actualizar</button>
            </form>
            <h2>Observaciones</h2>
            <div class="table-responsive">
<table class="table">
    <thead>
        <tr>
            <th scope="col">Categoría</th>
            <th scope="col">Descripción</th>
            <th scope="col">Fecha</th>
            <th scope="col">Acciones</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td>
                <form action="" method="post" onsubmit="return confirmDelete();">
                    <input type="hidden" name="id_observacion" value="">
                    <button type="submit" name="eliminar_observacion" class="btn btn-danger">Eliminar</button>
                </form>
            </td>
        </tr>
    </tbody>
</table>
        </div>


<form action="" method="post">
    <div class="form-group">
        <label>Categoría:</label>
        <input type="text" class="form-control" name="categoria" required>
    </div>
    <div class="form-group">
        <label>Descripción:</label>
        <textarea class="form-control" name="descripcion" required></textarea>
    </div>
    <div class="form-group">
        <label>Fecha:</label>
        <input type="date" class="form-control" name="fecha" required>
    </div>
    <button type="submit" class="btn btn-primary btn-block custom-button" name="agregar_observacion">Agregar Observación</button>
</form>


<script>
function confirmDelete() {
    return confirm("¿Estás seguro de que quieres eliminar esta observación?");
}
</script>