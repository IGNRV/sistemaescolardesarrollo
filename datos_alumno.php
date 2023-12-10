<?php
// Incluye la conexión a la base de datos
require_once 'db.php';
ini_set('display_errors', 1);

// Inicia sesión

// Define una variable para el mensaje
$mensaje = '';
$observaciones = []; // Array para almacenar las observaciones
$rutAlumno = ''; // Variable para almacenar el RUT del alumno buscado

$cursos = [];
$stmtCursos = $conn->prepare("SELECT ID_CURSO, NOMBRE_CURSO FROM CURSOS");
$stmtCursos->execute();
$resultadoCursos = $stmtCursos->get_result();

if ($resultadoCursos->num_rows > 0) {
    while ($filaCurso = $resultadoCursos->fetch_assoc()) {
        $cursos[] = $filaCurso['NOMBRE_CURSO'];
    }
}
$stmtCursos->close();

// Consulta para obtener las comunas
$comunas = [];
$stmtComunas = $conn->prepare("SELECT ID_COMUNA, NOM_COMUNA, ID_REGION FROM COMUNA");
$stmtComunas->execute();
$resultadoComunas = $stmtComunas->get_result();

if ($resultadoComunas->num_rows > 0) {
    while ($filaComuna = $resultadoComunas->fetch_assoc()) {
        $comunas[] = $filaComuna['NOM_COMUNA'];
    }
}
$stmtComunas->close();

// Verifica si el usuario está logueado y obtiene su id
if (!isset($_SESSION['EMAIL'])) {
    header('Location: login.php');
    exit;
} else {
    $EMAIL = $_SESSION['EMAIL'];
    $queryUsuario = "SELECT ID FROM USERS WHERE EMAIL = '$EMAIL'";
    $resultadoUsuario = $conn->query($queryUsuario);
    if ($resultadoUsuario->num_rows > 0) {
        $usuario = $resultadoUsuario->fetch_assoc();
        $id_usuario = $usuario['ID'];
    } else {
        $mensaje = "Usuario no encontrado.";
        exit;
    }
}

// Verifica si se ha enviado el formulario de búsqueda
if (isset($_POST['buscarAlumno'])) {
    $rutAlumno = $_POST['rutAlumno'];
    $stmt = $conn->prepare("SELECT * FROM ALUMNO WHERE RUT_ALUMNO = ?");
    $stmt->bind_param("s", $rutAlumno);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $mensaje = "Alumno encontrado.";
        $alumno = $resultado->fetch_assoc();

        // Consulta para obtener las observaciones del alumno
        $stmtObs = $conn->prepare("SELECT * FROM OBSERVACIONES WHERE RUT_ALUMNO = ?");
        $stmtObs->bind_param("s", $rutAlumno);
        $stmtObs->execute();
        $resultadoObs = $stmtObs->get_result();

        if ($resultadoObs->num_rows > 0) {
            while ($filaObs = $resultadoObs->fetch_assoc()) {
                $observaciones[] = $filaObs;
            }
        }
        $stmtObs->close();
    } else {
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
    $stmt = $conn->prepare("UPDATE ALUMNO SET NOMBRE = ?, AP_PATERNO = ?, AP_MATERNO = ?, FECHA_NAC = ?, RDA = ?, CALLE = ?, NRO_CALLE = ?, OBS_DIRECCION = ?, VILLA = ?, COMUNA = ?, ID_REGION = ?, MAIL = ?, FONO = ? WHERE RUT_ALUMNO = ?");
    $stmt->bind_param("ssssssssssssss", $nombre, $apPaterno, $apMaterno, $fechaNac, $rda, $calle, $nroCalle, $obsDireccion, $villa, $comuna, $idRegion, $mail, $fono, $rutAlumno);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $mensaje = "Datos del alumno actualizados con éxito.";
        
        // Vuelve a buscar los datos del alumno para mostrar los datos actualizados
        $stmt = $conn->prepare("SELECT * FROM ALUMNO WHERE RUT_ALUMNO = ?");
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

if (isset($_POST['agregar_observacion'])) {
    $categoria = $_POST['categoria'];
    $descripcion = $_POST['descripcion'];
    $fecha = $_POST['fecha'];
    // Asegúrate de usar la misma variable que usas para mostrar el RUT en el formulario
    $rutAlumno = $_POST['rutAlumno']; 

    // Asegúrate de que $rutAlumno esté definido y no sea nulo
    if (isset($rutAlumno) && !empty($rutAlumno)) {
        // Preparar la consulta SQL para insertar la observación
        $stmt = $conn->prepare("INSERT INTO OBSERVACIONES (CATEGORIA, DESCRIPCION, FECHA, RUT_ALUMNO) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $categoria, $descripcion, $fecha, $rutAlumno); // Cambia "i" por "s" si RUT_ALUMNO es VARCHAR
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            $mensaje = "Observación agregada con éxito.";
        } else {
            $mensaje = "Error al agregar la observación.";
        }
        $stmt->close();
    } else {
        $mensaje = "RUT del alumno no definido.";
    }
}

// Verifica si se ha enviado el formulario de agregar alumno
if (isset($_POST['agregarAlumno'])) {
    // Recoge los datos del formulario
    $nombreNuevo = $_POST['nombreNuevo'];
    $apPaternoNuevo = $_POST['apPaternoNuevo'];
    $apMaternoNuevo = $_POST['apMaternoNuevo'];
    $fechaNacNuevo = $_POST['fechaNacNuevo'];
    $rutAlumnoNuevo = $_POST['rutAlumnoNuevo'];
    $rdaNuevo = $_POST['rdaNuevo'];
    $calleNuevo = $_POST['calleNuevo'];
    $nroCalleNuevo = $_POST['nroCalleNuevo'];
    $obsDireccionNuevo = $_POST['obsDireccionNuevo'];
    $villaNuevo = $_POST['villaNuevo'];
    $comunaSeleccionada = $_POST['comunaNuevo'];
    $mailNuevo = $_POST['mailNuevo'];
    $fonoNuevo = $_POST['fonoNuevo'];
    $cursoSeleccionado = $_POST['curso'];
    $fotoalumno = $_POST['fotoalumno'];
    $fechaingreso = $_POST['fechaingreso'];
    $periodoescolar = 2;
    $status = 1;
    $deleteflag = 1;

    // Obtener ID_CURSO basado en la selección
    $stmtCurso = $conn->prepare("SELECT ID_CURSO FROM CURSOS WHERE NOMBRE_CURSO = ?");
    $stmtCurso->bind_param("s", $cursoSeleccionado);
    $stmtCurso->execute();
    $resultadoCurso = $stmtCurso->get_result();
    $idcurso = ($resultadoCurso->num_rows > 0) ? $resultadoCurso->fetch_assoc()['ID_CURSO'] : null;

    // Obtener ID_COMUNA e ID_REGION basado en la selección de comuna
    $stmtComuna = $conn->prepare("SELECT ID_COMUNA, ID_REGION FROM COMUNA WHERE NOM_COMUNA = ?");
    $stmtComuna->bind_param("s", $comunaSeleccionada);
    $stmtComuna->execute();
    $resultadoComuna = $stmtComuna->get_result();
    $comunaData = ($resultadoComuna->num_rows > 0) ? $resultadoComuna->fetch_assoc() : null;
    $idcomuna = $comunaData ? $comunaData['ID_COMUNA'] : null;
    $idRegion = $comunaData ? $comunaData['ID_REGION'] : null;

    // Prepara la consulta SQL para insertar el nuevo alumno
    $stmtNuevo = $conn->prepare("INSERT INTO ALUMNO (NOMBRE, AP_PATERNO, AP_MATERNO, FECHA_NAC, RUT_ALUMNO, RDA, CALLE, NRO_CALLE, OBS_DIRECCION, VILLA, COMUNA, ID_REGION, MAIL, FONO, CURSO, ID_CURSO, ID_COMUNA, FOTO_ALUMNO, FECHA_INGRESO, PERIODO_ESCOLAR, STATUS, DELETE_FLAG) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmtNuevo->bind_param("ssssssssssssssssssssss", $nombreNuevo, $apPaternoNuevo, $apMaternoNuevo, $fechaNacNuevo, $rutAlumnoNuevo, $rdaNuevo, $calleNuevo, $nroCalleNuevo, $obsDireccionNuevo, $villaNuevo, $comunaSeleccionada, $idRegion, $mailNuevo, $fonoNuevo, $cursoSeleccionado, $idcurso, $idcomuna, $fotoalumno, $fechaingreso, $periodoescolar, $status, $deleteflag);
    $stmtNuevo->execute();

    if ($stmtNuevo->affected_rows > 0) {
        $mensaje = "Nuevo alumno agregado con éxito.";
    } else {
        $mensaje = "Error al agregar el nuevo alumno.";
    }
    $stmtNuevo->close();
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
        <input type="text" class="form-control" id="rutAlumno" name="rutAlumno" placeholder="Ingrese RUT del alumno" value="<?php echo htmlspecialchars($rutAlumno); ?>">
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

            <h2>Agregar Nuevo Alumno</h2>
<form action="" method="post">
    <div class="form-group">
        <label>Nombre:</label>
        <input type="text" class="form-control" name="nombreNuevo" required>
    </div>
    <div class="form-group">
        <label>Apellido Paterno:</label>
        <input type="text" class="form-control" name="apPaternoNuevo" required>
    </div>
    <div class="form-group">
        <label>Apellido Materno:</label>
        <input type="text" class="form-control" name="apMaternoNuevo" required>
    </div>
    <div class="form-group">
        <label>Fecha de Nacimiento:</label>
        <input type="date" class="form-control" name="fechaNacNuevo" required>
    </div>
    <div class="form-group">
        <label>RUT:</label>
        <input type="text" class="form-control" name="rutAlumnoNuevo" required>
    </div>
    <div class="form-group">
        <label>RDA:</label>
        <input type="text" class="form-control" name="rdaNuevo">
    </div>
    <div class="form-group">
        <label>Calle:</label>
        <input type="text" class="form-control" name="calleNuevo">
    </div>
    <div class="form-group">
        <label>Número de Calle:</label>
        <input type="text" class="form-control" name="nroCalleNuevo">
    </div>
    <div class="form-group">
        <label>Observaciones Dirección:</label>
        <input type="text" class="form-control" name="obsDireccionNuevo">
    </div>
    <div class="form-group">
        <label>Villa/Población:</label>
        <input type="text" class="form-control" name="villaNuevo">
    </div>
    <div class="form-group">
        <label>Comuna:</label>
        <select class="form-control" name="comunaNuevo">
            <?php foreach ($comunas as $comuna): ?>
                <option value="<?php echo htmlspecialchars($comuna); ?>">
                    <?php echo htmlspecialchars($comuna); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    
    <div class="form-group">
        <label>Email:</label>
        <input type="email" class="form-control" name="mailNuevo">
    </div>
    <div class="form-group">
        <label>Teléfono:</label>
        <input type="text" class="form-control" name="fonoNuevo">
    </div>
    <div class="form-group">
        <label>Curso:</label>
        <select class="form-control" name="curso">
            <?php foreach ($cursos as $curso): ?>
                <option value="<?php echo htmlspecialchars($curso); ?>">
                    <?php echo htmlspecialchars($curso); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group">
        <label>Foto alumno:</label>
        <input type="text" class="form-control" name="fotoalumno">
    </div>
    <div class="form-group">
        <label>Fecha de Ingreso:</label>
        <input type="date" class="form-control" name="fechaingreso" required>
    </div>
    <button type="submit" class="btn btn-success" name="agregarAlumno">Agregar Alumno</button>
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
            <?php foreach ($observaciones as $obs): ?>
                <tr>
                    <td><?php echo htmlspecialchars($obs['CATEGORIA']); ?></td>
                    <td><?php echo htmlspecialchars($obs['DESCRIPCION']); ?></td>
                    <td><?php echo htmlspecialchars($obs['FECHA']); ?></td>
                    <td>
                        <form action="" method="post" onsubmit="return confirmDelete();">
                            <input type="hidden" name="id_observacion" value="<?php echo $obs['ID']; ?>">
                            <button type="submit" name="eliminar_observacion" class="btn btn-danger">Eliminar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>


<form action="" method="post">
    <input type="hidden" name="rutAlumno" value="<?php echo htmlspecialchars($rutAlumno); ?>">
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