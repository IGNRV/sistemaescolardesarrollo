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
/* 
if ($resultadoUsuario->num_rows > 0) {
    $usuario = $resultadoUsuario->fetch_assoc();
    $id_usuario = $usuario['id'];
} else {
    echo "Usuario no encontrado.";
    exit;
}

if (isset($_POST['actualizar_datos'])) {
    // Recuperar los valores del formulario
    $rut = $conn->real_escape_string($_POST['rut']);
    $parentesco = $conn->real_escape_string($_POST['parentesco']);
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $apellidoPaterno = $conn->real_escape_string($_POST['apellidoPaterno']);
    $apellidoMaterno = $conn->real_escape_string($_POST['apellidoMaterno']);
    $telefonoParticular = $conn->real_escape_string($_POST['telefonoParticular']);
    $telefonoTrabajo = $conn->real_escape_string($_POST['telefonoTrabajo']);
    $correoElectronicoPersonal = $conn->real_escape_string($_POST['correoElectronicoPersonal']);
    $correoElectronicoTrabajo = $conn->real_escape_string($_POST['correoElectronicoTrabajo']);
    $calle = $conn->real_escape_string($_POST['calle']);
    $n_calle = $conn->real_escape_string($_POST['n_calle']);
    $restoDireccion = $conn->real_escape_string($_POST['restoDireccion']);
    $villaPoblacion = $conn->real_escape_string($_POST['villaPoblacion']);
    $comuna = $conn->real_escape_string($_POST['comuna']);
    $ciudad = $conn->real_escape_string($_POST['ciudad']);
    $tutorAcademico = isset($_POST['tutorAcademico']) ? 1 : 0;

    // Insertar los datos en la tabla padres_apoderados
    $sql = "INSERT INTO padres_apoderados (rut, parentesco, nombre, apellido_paterno, apellido_materno, telefono_particular, telefono_trabajo, correo_electronico_particular, correo_electronico_trabajo, calle, n_calle, resto_direccion, villa_poblacion, comuna, ciudad, tutor_academico, id_usuario) VALUES ('$rut', '$parentesco', '$nombre', '$apellidoPaterno', '$apellidoMaterno', '$telefonoParticular', '$telefonoTrabajo', '$correoElectronicoPersonal', '$correoElectronicoTrabajo', '$calle', '$n_calle', '$restoDireccion', '$villaPoblacion', '$comuna', '$ciudad', '$tutorAcademico', '$id_usuario')";

    if ($conn->query($sql)) {
        $_SESSION['mensaje'] = "Datos actualizados correctamente.";
    } else {
        $_SESSION['mensaje'] = "Error al actualizar datos: " . $conn->error;
    }

    // Recargar la página para mostrar los datos actualizados
    header("Location: bienvenido.php?page=padres_apoderados");
    exit;
}

if (isset($_POST['eliminar'])) {
    $idEliminar = $_POST['idEliminar'];
    $sqlEliminar = "DELETE FROM padres_apoderados WHERE id = $idEliminar AND id_usuario = $id_usuario";
    if ($conn->query($sqlEliminar)) {
        $_SESSION['mensaje'] = "Registro eliminado correctamente.";
    } else {
        $_SESSION['mensaje'] = "Error al eliminar el registro: " . $conn->error;
    }
    header("Location: bienvenido.php?page=padres_apoderados");
    exit;
}

// Consulta para obtener los datos de padres/apoderados
$queryPadres = "SELECT * FROM padres_apoderados WHERE id_usuario = $id_usuario";
$resultadoPadres = $conn->query($queryPadres);

function rut($rut) {
    return number_format(substr($rut, 0, -1), 0, "", ".") . '-' . substr($rut, strlen($rut) - 1, 1);
} */
?>


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
                    <th>Otros</th>
                </tr>
            </thead>
            <tbody>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>
                        <form method="post" action="padres_apoderados.php">
                                <input type="hidden" name="idEliminar" value="">
                                <button type="submit" name="eliminar" class="btn btn-danger btn-sm">Eliminar</button>
                            </form>
                        </td>
                    </tr>
            </tbody>
        </table>
    </div>
    
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
