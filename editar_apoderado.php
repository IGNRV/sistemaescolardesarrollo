<?php
// Iniciar sesión y conectar a la base de datos
session_start();
require_once 'db.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


if (!isset($_SESSION['EMAIL'])) {
    header('Location: login.php');
    exit;
}

$mensaje = '';
$apoderado = null;

if (isset($_GET['rut'])) {
    $rutApoderado = $_GET['rut'];

    // Consulta para obtener los datos del apoderado
    $stmt = $conn->prepare("SELECT * FROM APODERADO WHERE RUT_APODERADO = ?");
    $stmt->bind_param("s", $rutApoderado);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $apoderado = $resultado->fetch_assoc();
    } else {
        $mensaje = "No se encontró el apoderado.";
    }
    $stmt->close();
}

if (isset($_POST['actualizar_apoderado'])) {
    $rutOriginal = $_POST['rut_original'];
    $parentesco = $_POST['parentesco'];
    $nombre = $_POST['nombre'];
    $apellidoPaterno = $_POST['apellidoPaterno'];
    $apellidoMaterno = $_POST['apellidoMaterno'];
    $fechaNac = $_POST['fecha_nac'];
    $calle = $_POST['calle'];
    $nCalle = $_POST['n_calle'];
    $obsDireccion = $_POST['obsDireccion'];
    $villaPoblacion = $_POST['villaPoblacion'];
    $comuna = $_POST['comuna'];
    $idRegion = $_POST['idRegion'];
    $fonoPart = $_POST['telefonoParticular'];
    $mailPart = $_POST['correoElectronicoPersonal'];
    $mailLab = $_POST['correoElectronicoTrabajo'];
    $tutorAcademico = isset($_POST['tutorAcademico']) ? 1 : 0;


    // Actualiza los datos en la base de datos
    $stmtActualizar = $conn->prepare("UPDATE APODERADO SET PARENTESCO = ?, NOMBRE = ?, AP_PATERNO = ?, AP_MATERNO = ?, FECHA_NAC = ?, CALLE = ?, NRO_CALLE = ?, OBS_DIRECCION = ?, VILLA = ?, ID_COMUNA = ?, ID_REGION = ?, FONO_PART = ?, MAIL_PART = ?, MAIL_LAB = ?, TUTOR_ACADEMICO = ? WHERE RUT_APODERADO = ?");
    $stmtActualizar->bind_param("ssssssssssssssss", $parentesco, $nombre, $apellidoPaterno, $apellidoMaterno, $fechaNac, $calle, $nCalle, $obsDireccion, $villaPoblacion, $comuna, $idRegion, $fonoPart, $mailPart, $mailLab, $tutorAcademico, $rutOriginal);
    $stmtActualizar->execute();

    if ($stmtActualizar->affected_rows > 0) {
        // Establece el mensaje de éxito en una variable de sesión
        $_SESSION['mensaje_exito'] = "Datos del apoderado actualizados correctamente.";
    
        // Redirige al usuario a la página de padres/apoderados
        header("Location: /bienvenido.php?page=padres_apoderados");
        exit; // Asegúrate de llamar a exit después de header para terminar la ejecución del script actual
    } else {
        $mensaje = "Error al actualizar los datos.";
    }
    $stmtActualizar->close();
}
?>
<head>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

</head>
<?php if (!empty($mensaje)): ?>
    <div class="alert alert-warning"><?php echo $mensaje; ?></div>
<?php endif; ?>

<?php if ($apoderado != null): ?>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3>Editar Apoderado</h3>
                    </div>
                    <div class="card-body">
                        <form method="post">
                            <input type="hidden" name="rut_original" value="<?php echo $apoderado['RUT_APODERADO']; ?>">

                            <div class="form-group">
                                <label for="parentesco">Parentesco</label>
                                <input type="text" class="form-control" name="parentesco" value="<?php echo $apoderado['PARENTESCO']; ?>">
                            </div>

                            <div class="form-group">
                                <label for="nombre">Nombre</label>
                                <input type="text" class="form-control" name="nombre" value="<?php echo $apoderado['NOMBRE']; ?>">
                            </div>

                            <div class="form-group">
                                <label for="apellidoPaterno">Apellido Paterno</label>
                                <input type="text" class="form-control" name="apellidoPaterno" value="<?php echo $apoderado['AP_PATERNO']; ?>">
                            </div>

                            <div class="form-group">
                                <label for="apellidoMaterno">Apellido Materno</label>
                                <input type="text" class="form-control" name="apellidoMaterno" value="<?php echo $apoderado['AP_MATERNO']; ?>">
                            </div>

                            <div class="form-group">
                                <label for="fechaNac">Fecha de Nacimiento</label>
                                <input type="date" class="form-control" name="fecha_nac" value="<?php echo $apoderado['FECHA_NAC']; ?>">
                            </div>

                            <div class="form-group">
                                <label for="calle">Calle</label>
                                <input type="text" class="form-control" name="calle" value="<?php echo $apoderado['CALLE']; ?>">
                            </div>

                            <div class="form-group">
                                <label for="nCalle">Número de Calle</label>
                                <input type="text" class="form-control" name="n_calle" value="<?php echo $apoderado['NRO_CALLE']; ?>">
                            </div>

                            <div class="form-group">
                                <label for="obsDireccion">Observaciones de Dirección</label>
                                <input type="text" class="form-control" name="obsDireccion" value="<?php echo $apoderado['OBS_DIRECCION']; ?>">
                            </div>

                            <div class="form-group">
                                <label for="villaPoblacion">Villa/Población</label>
                                <input type="text" class="form-control" name="villaPoblacion" value="<?php echo $apoderado['VILLA']; ?>">
                            </div>

                            <div class="form-group">
                                <label for="comuna">Comuna</label>
                                <input type="text" class="form-control" name="comuna" value="<?php echo $apoderado['ID_COMUNA']; ?>">
                            </div>

                            <div class="form-group">
                                <label for="idRegion">Región</label>
                                <input type="text" class="form-control" name="idRegion" value="<?php echo $apoderado['ID_REGION']; ?>">
                            </div>

                            <div class="form-group">
                                <label for="telefonoParticular">Teléfono Particular</label>
                                <input type="tel" class="form-control" name="telefonoParticular" value="<?php echo $apoderado['FONO_PART']; ?>">
                            </div>

                            <div class="form-group">
                                <label for="correoElectronicoPersonal">Correo Electrónico Personal</label>
                                <input type="email" class="form-control" name="correoElectronicoPersonal" value="<?php echo $apoderado['MAIL_PART']; ?>">
                            </div>

                            <div class="form-group">
                                <label for="correoElectronicoTrabajo">Correo Electrónico de Trabajo</label>
                                <input type="email" class="form-control" name="correoElectronicoTrabajo" value="<?php echo $apoderado['MAIL_LAB']; ?>">
                            </div>

                            <div class="form-group">
                                <label for="tutorAcademico">Tutor Académico</label>
                                <input type="checkbox" id="tutorAcademico" name="tutorAcademico" value="1" <?php echo $apoderado['TUTOR_ACADEMICO'] ? 'checked' : ''; ?>>
                            </div>

                            <button type="submit" name="actualizar_apoderado" class="btn btn-primary">Actualizar</button>
                        </form>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

