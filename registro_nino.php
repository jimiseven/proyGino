<?php
// Incluir archivo de conexión
include 'conexion.php';

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Datos del niño
    $nombre_nino = $_POST['nombre_nino'];
    $apellido_nino = $_POST['apellido_nino'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $sexo = $_POST['sexo'];
    $carnet_nino = $_POST['carnet_nino'];  // Carnet de identidad del niño

    // Datos del tutor
    $nombre_tutor = $_POST['nombre_tutor'];
    $apellido_tutor = $_POST['apellido_tutor'];
    $carnet_identidad_tutor = $_POST['carnet_identidad_tutor'];
    $celular_tutor = $_POST['celular_tutor'];
    $relacion_tutor = $_POST['relacion_tutor'];

    // Iniciar una transacción
    $conn->begin_transaction();

    try {
        // Insertar los datos del tutor en la tabla 'tutor'
        $sql_tutor = "INSERT INTO tutor (nombre, apellidos, carnet_identidad, celular, relacion) 
                      VALUES ('$nombre_tutor', '$apellido_tutor', '$carnet_identidad_tutor', '$celular_tutor', '$relacion_tutor')";
        if (!$conn->query($sql_tutor)) {
            throw new Exception("Error al insertar los datos del tutor: " . $conn->error);
        }

        // Obtener el último ID insertado (ID del tutor)
        $id_tutor = $conn->insert_id;

        // Insertar los datos del niño en la tabla 'nino'
        $sql_nino = "INSERT INTO nino (nombre, apellido, fecha_nacimiento, sexo, id_tutor, carnet_identidad) 
                     VALUES ('$nombre_nino', '$apellido_nino', '$fecha_nacimiento', '$sexo', '$id_tutor', '$carnet_nino')";

        if (!$conn->query($sql_nino)) {
            throw new Exception("Error al insertar los datos del niño: " . $conn->error);
        }

        // Confirmar la transacción
        $conn->commit();
        $mensaje = "Registro exitoso";

    } catch (Exception $e) {
        // Si hay algún error, se revierte la transacción
        $conn->rollback();
        $mensaje = "Error al registrar: " . $e->getMessage();
    }

    // Cerrar la conexión
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Niño y Tutor</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Vac1Med</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarText">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="listar_ninos.php">Listado Niños</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="registro_nino.php">Registro Niños</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="lista_vac.php">Listado Vacunas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="registrar_vacuna.php">Registro Vacunas</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-5">
        <h2>Registro de Niño y Tutor</h2>

        <?php if (!empty($mensaje)) { ?>
            <div class="alert alert-info"><?php echo $mensaje; ?></div>
        <?php } ?>

        <form action="registro_nino.php" method="post">
            <!-- Sección del niño -->
            <h3>Datos del Niño</h3>
            <div class="mb-3">
                <label for="nombre_nino" class="form-label">Nombre del Niño:</label>
                <input type="text" class="form-control" id="nombre_nino" name="nombre_nino" required>
            </div>
            <div class="mb-3">
                <label for="apellido_nino" class="form-label">Apellido del Niño:</label>
                <input type="text" class="form-control" id="apellido_nino" name="apellido_nino" required>
            </div>
            <div class="mb-3">
                <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento:</label>
                <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" required>
            </div>
            <div class="mb-3">
                <label for="sexo" class="form-label">Sexo:</label>
                <select class="form-control" id="sexo" name="sexo" required>
                    <option value="niño">Niño</option>
                    <option value="niña">Niña</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="carnet_nino" class="form-label">Carnet de Identidad del Niño:</label>
                <input type="text" class="form-control" id="carnet_nino" name="carnet_nino" required>
            </div>

            <!-- Sección del tutor -->
            <h3>Datos del Tutor</h3>
            <div class="mb-3">
                <label for="nombre_tutor" class="form-label">Nombre del Tutor:</label>
                <input type="text" class="form-control" id="nombre_tutor" name="nombre_tutor" required>
            </div>
            <div class="mb-3">
                <label for="apellido_tutor" class="form-label">Apellido del Tutor:</label>
                <input type="text" class="form-control" id="apellido_tutor" name="apellido_tutor" required>
            </div>
            <div class="mb-3">
                <label for="carnet_identidad_tutor" class="form-label">Carnet de Identidad del Tutor:</label>
                <input type="text" class="form-control" id="carnet_identidad_tutor" name="carnet_identidad_tutor" required>
            </div>
            <div class="mb-3">
                <label for="celular_tutor" class="form-label">Celular del Tutor:</label>
                <input type="text" class="form-control" id="celular_tutor" name="celular_tutor" required>
            </div>
            <div class="mb-3">
                <label for="relacion_tutor" class="form-label">Relación con el Niño:</label>
                <select class="form-control" id="relacion_tutor" name="relacion_tutor" required>
                    <option value="padre/madre">Padre/Madre</option>
                    <option value="abuelo/abuela">Abuelo/Abuela</option>
                    <option value="tutor/tutora">Tutor/Tutora</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Registrar</button>
        </form>
    </div>

    <script src="js/bootstrap.bundle.min.js"></script>
</body>

</html>
