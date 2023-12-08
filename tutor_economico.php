<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['EMAIL'])) {
    header('Location: login.php');
    exit;
}

// Incluye la conexión a la base de datos
require_once 'db.php';

// Obtener el ID del usuario que ha iniciado sesión
$EMAIL = $_SESSION['EMAIL'];
$queryUsuario = "SELECT ID FROM USERS WHERE EMAIL = '$EMAIL'";
$resultadoUsuario = $conn->query($queryUsuario);

$apoderados = []; // Array para almacenar los datos de los apoderados
$rutTutor = '';
$nombreTutor = '';
$apPaternoTutor = '';
$apMaternoTutor = '';
$telefonoParticular = '';
$telefonoTrabajo = '';
$calle = '';
$nCalle = '';
$restoDireccion = '';
$villaPoblacion = '';
$comuna = '';
$ciudad = '';
$correoPersonal = '';
$correoTrabajo = '';

if (isset($_POST['buscarAlumno'])) {
    $rutAlumno = $_POST['rutAlumno'];

    // Consulta a la base de datos
    $stmt = $conn->prepare("SELECT 
                                a.ID_ALUMNO,
                                a.RUT_ALUMNO,
                                ap.ID_APODERADO,
                                ap.RUT_APODERADO,
                                ap.NOMBRE,
                                ap.AP_PATERNO,
                                ap.AP_MATERNO,
                                ap.FECHA_NAC,
                                ap.PARENTESCO,
                                ap.MAIL_LAB,
                                ap.MAIL_PART,
                                ap.FONO_PART,
                                ap.CALLE,
                                ap.NRO_CALLE,
                                ap.OBS_DIRECCION,
                                ap.VILLA,
                                ap.COMUNA,
                                ap.ID_COMUNA,
                                ap.ID_REGION,
                                ap.FECHA_INGRESO,
                                ap.PERIODO_ESCOLAR,
                                ap.FONO_LAB,
                                ap.TUTOR_ACADEMICO,
                                a.PERIODO_ESCOLAR
                            FROM
                                ALUMNO AS a
                                    LEFT JOIN
                                REL_ALUM_APOD AS raa ON raa.ID_ALUMNO = a.ID_ALUMNO
                                    LEFT JOIN
                                APODERADO AS ap ON ap.ID_APODERADO = raa.ID_APODERADO
                            WHERE
                                a.RUT_ALUMNO = ? AND ap.TUTOR_ACADEMICO = 1");
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

if (!empty($apoderados)) {
    // Asigna los valores a las variables
    $rutTutor = $apoderados[0]['RUT_APODERADO'];
    $nombreTutor = $apoderados[0]['NOMBRE'];
    $apPaternoTutor = $apoderados[0]['AP_PATERNO'];
    $apMaternoTutor = $apoderados[0]['AP_MATERNO'];
    $telefonoParticular = $apoderados[0]['FONO_PART'];
    $telefonoTrabajo = $apoderados[0]['FONO_LAB'];
    $calle = $apoderados[0]['CALLE'];
    $nCalle = $apoderados[0]['NRO_CALLE'];
    $restoDireccion = $apoderados[0]['OBS_DIRECCION'];
    $villaPoblacion = $apoderados[0]['VILLA'];
    $comuna = $apoderados[0]['COMUNA'];
    $ciudad = $apoderados[0]['ID_REGION'];
    $correoPersonal = $apoderados[0]['MAIL_PART'];
    $correoTrabajo = $apoderados[0]['MAIL_LAB'];
    $periodoescolar = $apoderados[0]['PERIODO_ESCOLAR'];

}


if (isset($_POST['INGRESAR_DATOS'])) {
    $medioPago = $_POST['medioPago'];
    $bancoEmisor = $_POST['bancoEmisor'];
    $tipoMedioPago = $_POST['tipoMedioPago'];
    $rutPagador = $_POST['rut']; // Utiliza el valor del input 'rut' del formulario



    // Obtener el último número de medio de pago y aumentarlo en 1
    $stmt = $conn->prepare("SELECT MAX(NRO_MEDIOPAGO) AS ultimo_numero FROM MEDIOS_DE_PAGO");
    $stmt->execute();
    $resultado = $stmt->get_result();
    $fila = $resultado->fetch_assoc();
    $nroMedioPago = (int)$fila['ultimo_numero'] + 1;

    // Fecha actual
    $fechaActual = date('Y-m-d');

    // Insertar en la base de datos
    $stmt = $conn->prepare("INSERT INTO MEDIOS_DE_PAGO (MEDIO_PAGO, BANCO_EMISOR, TIPO_MEDIOPAGO, RUT_PAGADOR, NRO_MEDIOPAGO, FECHA_SUSCRIPCION, ESTADO_MP, FECHA_VENCIMIENTO_MP, FECHA_INGRESO, FECHA_ACTIVACION, PERIODO_ESCOLAR) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $estadoMP = 1; // Estado activo
    $fechaVencimientoMP = '2100-01-01';
    $periodoEscolar = 2; // Número fijo según tu indicación

    $stmt->bind_param("ssssssssssi", $medioPago, $bancoEmisor, $tipoMedioPago, $rutPagador, $nroMedioPago, $fechaActual, $estadoMP, $fechaVencimientoMP, $fechaActual, $fechaActual, $periodoEscolar);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $mensaje = "Datos del medio de pago ingresados con éxito.";
    } else {
        $mensaje = "Error al ingresar los datos del medio de pago.";
    }
    $stmt->close();
}



?>
<div class="tutor-economico">
    <form method="post">
        <div class="form-group">
            <label for="rutAlumno">Rut del alumno:</label>
            <!-- Utiliza el valor de $rutAlumno para mantener el valor después de enviar el formulario -->
            <input type="text" class="form-control" id="rutAlumno" name="rutAlumno" placeholder="Ingrese RUT del alumno" value="<?php echo htmlspecialchars($rutAlumno); ?>">
            <button type="submit" class="btn btn-primary custom-button mt-3" name="buscarAlumno">Buscar</button>
        </div>
    </form>
    <h2>Datos tutor económico</h2>
    <form method="post">
        <div class="form-group">
            <label for="rutTutor">RUT</label>
            <input type="text" class="form-control" name="rut" id="rutTutor" value="<?php echo htmlspecialchars($rutTutor); ?>" maxlength="9">
        </div>
        <div class="form-group">
            <label for="nombresTutor">Nombre</label>
            <input type="text" class="form-control" name="nombre" id="nombresTutor" value="<?php echo htmlspecialchars($nombreTutor); ?>">
        </div>
        <div class="form-group">
            <label for="apPaternoTutor">Apellido Paterno</label>
            <input type="text" class="form-control" name="apellido_paterno" id="apPaternoTutor" value="<?php echo htmlspecialchars($apPaternoTutor); ?>">
        </div>
        <div class="form-group">
            <label for="apMaternoTutor">Apellido Materno</label>
            <input type="text" class="form-control" name="apellido_materno" id="apMaternoTutor" value="<?php echo htmlspecialchars($apMaternoTutor); ?>">
        </div>
        <div class="form-group">
            <label for="telefono_particular">Teléfono particular</label>
            <input type="text" class="form-control" name="telefono_particular" id="telefono_particular" value="<?php echo htmlspecialchars($telefonoParticular); ?>">
        </div>
        <div class="form-group">
            <label for="telefono_trabajo">Teléfono trabajo</label>
            <input type="text" class="form-control" name="telefono_trabajo" id="telefono_trabajo" value="<?php echo htmlspecialchars($telefonoTrabajo); ?>">
        </div>
        <div class="form-group">
            <label for="calleTutor">Calle</label>
            <input type="text" class="form-control" name="calle" id="calleTutor" value="<?php echo htmlspecialchars($calle); ?>">
        </div>
        <div class="form-group">
            <label for="nCalleTutor">N° Calle</label>
            <input type="text" class="form-control" name="n_calle" id="nCalleTutor" value="<?php echo htmlspecialchars($nCalle); ?>">
        </div>
        <div class="form-group">
            <label for="restoDireccionTutor">Resto Dirección</label>
            <input type="text" class="form-control" name="resto_direccion" id="restoDireccionTutor" value="<?php echo htmlspecialchars($restoDireccion); ?>">
        </div>
        <div class="form-group">
            <label for="villaPoblacionTutor">Villa/Población</label>
            <input type="text" class="form-control" name="villa_poblacion" id="villaPoblacionTutor" value="<?php echo htmlspecialchars($villaPoblacion); ?>">
        </div>
        <div class="form-group">
            <label for="comunaTutor">Comuna</label>
            <input type="text" class="form-control" name="comuna" id="comunaTutor" value="<?php echo htmlspecialchars($comuna); ?>">
        </div>
        <div class="form-group">
            <label for="ciudadTutor">Ciudad</label>
            <input type="text" class="form-control" name="ciudad" id="ciudadTutor" value="<?php echo htmlspecialchars($ciudad); ?>">
        </div>
        <div class="form-group">
            <label for="correoPersonalTutor">Correo Electrónico Personal</label>
            <input type="email" class="form-control" name="correo_electronico_particular" id="correoPersonalTutor" value="<?php echo htmlspecialchars($correoPersonal); ?>">
        </div>
        <div class="form-group">
            <label for="correoTrabajoTutor">Correo Electrónico Trabajo</label>
            <input type="email" class="form-control" name="correo_electronico_trabajo" id="correoTrabajoTutor" value="<?php echo htmlspecialchars($correoTrabajo); ?>">
        </div>
        
        <button type="submit" class="btn btn-primary btn-block custom-button">ACTUALIZAR</button>
    </form>

    <h3>Medios de pago suscritos</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Tipo Medio de Pago</th>
                <th>Banco Emisor</th>
                <th>Fecha suscripción</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
        </tbody>
    </table>
    <button type="button" class="btn btn-primary btn-block custom-button">VER DETALLE</button>
    <form method="post">
        <div class="form-group">
            <label for="medioPago">Medio de Pago</label>
            <input type="text" class="form-control" name="medioPago" id="medioPago" value="" maxlength="9">
        </div>
        <div class="form-group">
            <label for="bancoEmisor">Banco Emisor </label>
            <input type="text" class="form-control" name="bancoEmisor" id="bancoEmisor" value="">
        </div>
        <div class="form-group">
            <label for="tipoMedioPago">Tipo de Medio de Pago</label>
            <input type="text" class="form-control" name="tipoMedioPago" id="tipoMedioPago" value="">
        </div>     
        <input type="hidden" name="rut" value="<?php echo htmlspecialchars($rutTutor); ?>">

        <button type="submit" class="btn btn-primary btn-block custom-button" name="INGRESAR_DATOS">INGRESAR DATOS</button>
    </form>
</div>