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


?>
<div class="tutor-economico">
    <h2>Datos tutor económico</h2>
    <form method="post">
        <div class="form-group">
            <label for="rutTutor">RUT</label>
            <input type="text" class="form-control" name="rut" id="rutTutor" value="" maxlength="9">
        </div>
        <div class="form-group">
            <label for="nombresTutor">Nombre</label>
            <input type="text" class="form-control" name="nombre" id="nombresTutor" value="">
        </div>
        <div class="form-group">
            <label for="apPaternoTutor">Apellido Paterno</label>
            <input type="text" class="form-control" name="apellido_paterno" id="apPaternoTutor" value="">
        </div>
        <div class="form-group">
            <label for="apMaternoTutor">Apellido Materno</label>
            <input type="text" class="form-control" name="apellido_materno" id="apMaternoTutor" value="">
        </div>
        <div class="form-group">
            <label for="telefono_particular">Teléfono particular</label>
            <input type="text" class="form-control" name="telefono_particular" id="telefono_particular" value="">
        </div>
        <div class="form-group">
            <label for="telefono_trabajo">Teléfono trabajo</label>
            <input type="text" class="form-control" name="telefono_trabajo" id="telefono_trabajo" value="">
        </div>
        <div class="form-group">
            <label for="calleTutor">Calle</label>
            <input type="text" class="form-control" name="calle" id="calleTutor" value="">
        </div>
        <div class="form-group">
            <label for="nCalleTutor">N° Calle</label>
            <input type="text" class="form-control" name="n_calle" id="nCalleTutor" value="">
        </div>
        <div class="form-group">
            <label for="restoDireccionTutor">Resto Dirección</label>
            <input type="text" class="form-control" name="resto_direccion" id="restoDireccionTutor" value="">
        </div>
        <div class="form-group">
            <label for="villaPoblacionTutor">Villa/Población</label>
            <input type="text" class="form-control" name="villa_poblacion" id="villaPoblacionTutor" value="">
        </div>
        <div class="form-group">
            <label for="comunaTutor">Comuna</label>
            <input type="text" class="form-control" name="comuna" id="comunaTutor" value="">
        </div>
        <div class="form-group">
            <label for="ciudadTutor">Ciudad</label>
            <input type="text" class="form-control" name="ciudad" id="ciudadTutor" value="">
        </div>
        <div class="form-group">
            <label for="correoPersonalTutor">Correo Electrónico Personal</label>
            <input type="email" class="form-control" name="correo_electronico_particular" id="correoPersonalTutor" value="">
        </div>
        <div class="form-group">
            <label for="correoTrabajoTutor">Correo Electrónico Trabajo</label>
            <input type="email" class="form-control" name="correo_electronico_trabajo" id="correoTrabajoTutor" value="">
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
</div>