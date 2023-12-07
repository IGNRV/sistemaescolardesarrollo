<?php
// Asegúrate de que un usuario haya iniciado sesión
if (!isset($_SESSION['EMAIL'])) {
    header('Location: login.php');
    exit;
}

// Conexión a la base de datos
require_once 'db.php';

// Obtener el id_usuario del usuario que ha iniciado sesión
$EMAIL = $_SESSION['EMAIL'];
$queryUsuario = "SELECT ID FROM USERS WHERE EMAIL = '$EMAIL'";
$resultadoUsuario = $conn->query($queryUsuario);

$apoderados = []; // Array para almacenar los datos de los apoderados

if (isset($_POST['buscarAlumno'])) {
    $rutAlumno = $_POST['rutAlumno'];

    // Consulta a la base de datos
    $stmt = $conn->prepare("SELECT 
                                a.RUT_ALUMNO,
                                ap.NOMBRE,
                                ap.AP_PATERNO,
                                ap.AP_MATERNO,
                                ap.PARENTESCO,
                                ap.MAIL_PART,
                                ap.FONO_PART
                            FROM
                                ALUMNO AS a
                                    LEFT JOIN
                                REL_ALUM_APOD AS raa ON raa.ID_ALUMNO = a.ID_ALUMNO
                                    LEFT JOIN
                                APODERADO AS ap ON ap.ID_APODERADO = raa.ID_APODERADO
                            WHERE
                                a.RUT_ALUMNO = ?");
    $stmt->bind_param("s", $rutAlumno);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $mensaje = "Datos del alumno encontrados.";
        $apoderados = $resultado->fetch_all(MYSQLI_ASSOC);
    } else {
        $mensaje = "No se encontró ningún alumno con ese RUT.";
    }
    $stmt->close();
}

?>

<?php if (!empty($mensaje)): ?>
    <div class="alert alert-success" role="alert">
        <?php echo $mensaje; ?>
    </div>
<?php endif; ?>
<div class="parents-apoderados">
<h2>Datos padres/apoderados</h2>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>RUT</th>
                    <th>Nombre completo</th>
                    <th>Parentesco</th>
                    <th>Mail</th>
                    <th>Teléfono</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($apoderados as $apoderado): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($apoderado['RUT_ALUMNO']); ?></td>
                        <td><?php echo htmlspecialchars($apoderado['NOMBRE']) . " " . htmlspecialchars($apoderado['AP_PATERNO']) . " " . htmlspecialchars($apoderado['AP_MATERNO']); ?></td>
                        <td><?php echo htmlspecialchars($apoderado['PARENTESCO']); ?></td>
                        <td><?php echo htmlspecialchars($apoderado['MAIL_PART']); ?></td>
                        <td><?php echo htmlspecialchars($apoderado['FONO_PART']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>


    <form method="post">
        <div class="form-group">
            <label for="rutAlumno">Rut del alumno:</label>
            <!-- Utiliza el valor de $rutAlumno para mantener el valor después de enviar el formulario -->
            <input type="text" class="form-control" id="rutAlumno" name="rutAlumno" placeholder="Ingrese RUT del alumno" value="<?php echo htmlspecialchars($rutAlumno); ?>">
            <button type="submit" class="btn btn-primary custom-button mt-3" name="buscarAlumno">Buscar</button>
        </div>
    </form>
    
    <h3>Información de padres/apoderados</h3>
    <form method="POST" action="padres_apoderados.php">
        <div class="form-group">
            <label for="rut">RUT</label>
            <input type="text" class="form-control" name="rut" id="rut" maxlength="9">
        </div>
        <div class="form-group">
            <label for="parentesco">Parentesco</label>
            <input type="text" class="form-control" name="parentesco">
        </div>
        <div class="form-group">
            <label for="nombres">Nombre</label>
            <input type="text" class="form-control" name="nombre">
        </div>
        <div class="form-group">
            <label for="apellidoPaterno">Apellido Paterno</label>
            <input type="text" class="form-control" name="apellidoPaterno">
        </div>
        <div class="form-group">
            <label for="apellidoMaterno">Apellido Materno</label>
            <input type="text" class="form-control" name="apellidoMaterno">
        </div>
        <div class="form-group">
            <label for="calle">Calle</label>
            <input type="text" class="form-control" name="calle">
        </div>
        <div class="form-group">
            <label for="n_calle">N°</label>
            <input type="text" class="form-control" name="n_calle">
        </div>
        <div class="form-group">
            <label for="restoDireccion">Resto Dirección</label>
            <input type="text" class="form-control" name="restoDireccion">
        </div>
        <div class="form-group">
            <label for="villaPoblacion">Villa/Población</label>
            <input type="text" class="form-control" name="villaPoblacion">
        </div>
        <div class="form-group">
            <label for="comuna">Comuna</label>
            <input type="text" class="form-control" name="comuna">
        </div>
        <div class="form-group">
            <label for="ciudad">Ciudad</label>
            <input type="text" class="form-control" name="ciudad">
        </div>
        <div class="form-group">
            <label for="telefonoParticular">Teléfono Particular</label>
            <input type="tel" class="form-control" name="telefonoParticular">
        </div>
        <div class="form-group">
            <label for="telefonoTrabajo">Teléfono Trabajo</label>
            <input type="tel" class="form-control" name="telefonoTrabajo">
        </div>
        <div class="form-group">
            <label for="correoElectronicoPersonal">Correo Electrónico Personal</label>
            <input type="email" class="form-control" name="correoElectronicoPersonal">
        </div>
        <div class="form-group">
            <label for="correoElectronicoTrabajo">Correo Electrónico Trabajo</label>
            <input type="email" class="form-control" name="correoElectronicoTrabajo">
        </div>
        <div class="form-group">
            <label for="tutorAcademico">Tutor académico</label>
            <input type="checkbox" id="tutorAcademico" name="tutorAcademico" value="1">
        </div>
        <button type="submit" class="btn btn-primary btn-block custom-button" name="actualizar_datos">ACTUALIZAR DATOS</button>
</form>
</div>
