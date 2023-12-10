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
$stmtComunas = $conn->prepare("SELECT ID_COMUNA, NOM_COMUNA FROM COMUNA");
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
    $comunaNuevo = $_POST['comunaNuevo'];
    $idRegionNuevo = $_POST['idRegionNuevo'];
    $mailNuevo = $_POST['mailNuevo'];
    $fonoNuevo = $_POST['fonoNuevo'];
    $cursoSeleccionado = $_POST['curso'];
    $fotoalumno = $_POST['fotoalumno'];
    $fechaingreso = $_POST['fechaingreso'];
    $periodoescolar = 2;
    $status = 1;
    $deleteflag = 1;

    // Obtiene el ID del curso seleccionado
    $stmtCurso = $conn->prepare("SELECT ID_CURSO FROM CURSOS WHERE NOMBRE_CURSO = ?");
    $stmtCurso->bind_param("s", $cursoSeleccionado);
    $stmtCurso->execute();
    $resultadoCurso = $stmtCurso->get_result();
    $idCurso = $resultadoCurso->fetch_assoc()['ID_CURSO'];
    $stmtCurso->close();

    // Obtiene el ID de la comuna seleccionada
    $stmtComuna = $conn->prepare("SELECT ID_COMUNA FROM COMUNA WHERE NOM_COMUNA = ?");
    $stmtComuna->bind_param("s", $comunaNuevo);
    $stmtComuna->execute();
    $resultadoComuna = $stmtComuna->get_result();
    $idComuna = $resultadoComuna->fetch_assoc()['ID_COMUNA'];
    $stmtComuna->close();

    // Prepara la consulta SQL para insertar el nuevo alumno
    $stmtNuevo = $conn->prepare("INSERT INTO ALUMNO (NOMBRE, AP_PATERNO, AP_MATERNO, FECHA_NAC, RUT_ALUMNO, RDA, CALLE, NRO_CALLE, OBS_DIRECCION, VILLA, COMUNA, ID_REGION, MAIL, FONO, CURSO, ID_CURSO, ID_COMUNA, FOTO_ALUMNO, FECHA_INGRESO, PERIODO_ESCOLAR, STATUS, DELETE_FLAG) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmtNuevo->bind_param("sssssssssssssssisssiii", $nombreNuevo, $apPaternoNuevo, $apMaternoNuevo, $fechaNacNuevo, $rutAlumnoNuevo, $rdaNuevo, $calleNuevo, $nroCalleNuevo, $obsDireccionNuevo, $villaNuevo, $comunaNuevo, $idRegionNuevo, $mailNuevo, $fonoNuevo, $cursoSeleccionado, $idCurso, $idComuna, $fotoalumno, $fechaingreso, $periodoescolar, $status, $deleteflag);
    $stmtNuevo->execute();

    if ($stmtNuevo->affected_rows > 0) {
        $mensaje = "Nuevo alumno agregado con éxito.";
    } else {
        $mensaje = "Error al agregar el nuevo alumno.";
    }
    $stmtNuevo->close();
}



?>


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
            <option value="<?php echo htmlspecialchars($comuna['ID_COMUNA']); ?>">
                <?php echo htmlspecialchars($comuna['NOM_COMUNA']); ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>
    <div class="form-group">
        <label>Región:</label>
        <input type="text" class="form-control" name="idRegionNuevo">
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
            <option value="<?php echo htmlspecialchars($curso['ID_CURSO']); ?>">
                <?php echo htmlspecialchars($curso['NOMBRE_CURSO']); ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>
    <div class="form-group">
        <label>ID Curso:</label>
        <input type="text" class="form-control" name="idcurso">
    </div>
    <div class="form-group">
        <label>ID Comuna:</label>
        <input type="text" class="form-control" name="idcomuna">
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
