<?php if (isset($_SESSION['NAME'])): ?>
<nav class="navbar navbar-light bg-light">
    <a class="navbar-brand" href="#">Bienvenido, <?php echo $_SESSION['NAME']; ?>!</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <a class="nav-link" href="bienvenido.php?page=inicio">Inicio</a>
        </li>
        <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="datosAlumnoDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Datos Alumno
                </a>
                <div class="dropdown-menu" aria-labelledby="datosAlumnoDropdown">
                    <a class="dropdown-item" href="bienvenido.php?page=datos_alumno">Ver Datos</a>
                    <a class="dropdown-item" href="bienvenido.php?page=agregar_alumno">Agregar Alumno</a>
                </div>
        </li>
        <!-- <li class="nav-item">
            <a class="nav-link" href="bienvenido.php?page=emergencias">Emergencias</a>
        </li> -->
        <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="datosAlumnoDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Emergencias
                </a>
                <div class="dropdown-menu" aria-labelledby="datosAlumnoDropdown">
                    <a class="dropdown-item" href="bienvenido.php?page=emergencias">Ver Datos</a>
                    <a class="dropdown-item" href="bienvenido.php?page=agregar_contacto_emergencia">Agregar contacto de emergencias</a>
                </div>
        </li>
        <li class="nav-item">
    <a class="nav-link" href="bienvenido.php?page=padres_apoderados">Padres/Apoderados</a>
</li>
<li class="nav-item">
    <a class="nav-link" href="bienvenido.php?page=tutor_economico">Tutor Económico</a>
</li>
<li class="nav-item">
    <a class="nav-link" href="bienvenido.php?page=pago_electronico">Pago Electrónico</a>
</li>
<li class="nav-item">
    <a class="nav-link" href="bienvenido.php?page=cuadratura_caja">Cuadratura de Caja</a>
</li>
<li class="nav-item">
    <a class="nav-link" href="bienvenido.php?page=pago_rut_alumno">Pago con RUT</a>
</li>
<li class="nav-item">
    <a class="nav-link" href="bienvenido.php?page=pago_cheque_anual">Pago Anual con Cheques</a>
</li>

            <li class="nav-item">
                <a class="nav-link" href="logout.php">Cerrar sesión</a>
            </li>
        </ul>
    </div>
</nav>
<?php endif; ?>
