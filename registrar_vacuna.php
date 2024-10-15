<?php
// Incluir archivo de conexión
include 'conexion.php';

// Variables para mensajes
$mensaje = "";
$nombre_nino = "";
$tipo_vacuna = "";
$fecha_vacunacion = "";
$dosis_nueva = 0;
$nombre_personal = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nino_id = $_POST['nino_id'];
    $vacuna_id = $_POST['vacuna_id'];
    $fecha_vacunacion = $_POST['fecha_vacunacion'];
    $personal_id = $_POST['personal_id']; // Recibir el ID del personal

    // Obtener el nombre del niño
    $sql_nino = "SELECT nombre, apellido FROM nino WHERE id = $nino_id";
    $resultado_nino = $conn->query($sql_nino);
    if ($resultado_nino) {
        $nino = $resultado_nino->fetch_assoc();
        $nombre_nino = $nino['nombre'] . ' ' . $nino['apellido'];
    }

    // Obtener el tipo de vacuna
    $sql_vacuna = "SELECT tipo FROM vacuna_tipo WHERE id = $vacuna_id";
    $resultado_vacuna = $conn->query($sql_vacuna);
    if ($resultado_vacuna) {
        $vacuna = $resultado_vacuna->fetch_assoc();
        $tipo_vacuna = $vacuna['tipo'];
    }

    // Verificar cuántas dosis ha recibido el niño de esta vacuna
    $sql_dosis = "SELECT COUNT(*) as dosis_actual FROM vacunas WHERE nino_id = $nino_id AND tipo_id = $vacuna_id";
    $resultado_dosis = $conn->query($sql_dosis);
    if ($resultado_dosis) {
        $dosis_actual = $resultado_dosis->fetch_assoc()['dosis_actual'];
    }

    // Obtener cuántas dosis requiere la vacuna seleccionada
    $sql_dosis_requeridas = "SELECT dosis_requeridas FROM vacuna_tipo WHERE id = $vacuna_id";
    $resultado_requeridas = $conn->query($sql_dosis_requeridas);
    if ($resultado_requeridas) {
        $dosis_requeridas = $resultado_requeridas->fetch_assoc()['dosis_requeridas'];
    }

    // Verificar si el niño ya recibió todas las dosis
    if ($dosis_actual < $dosis_requeridas) {
        // Calcular la próxima dosis
        $dosis_nueva = $dosis_actual + 1;

        // Registrar la vacuna con la dosis correspondiente y el personal
        $sql_insert = "INSERT INTO vacunas (tipo_id, dosis, fecha_vacunacion, nino_id, personal_id) 
                       VALUES ($vacuna_id, $dosis_nueva, '$fecha_vacunacion', $nino_id, $personal_id)";
        if ($conn->query($sql_insert) === TRUE) {
            $mensaje = "Vacuna registrada exitosamente.";
        } else {
            $mensaje = "Error al registrar la vacuna: " . $conn->error;
        }
    } else {
        $mensaje = "El niño ya ha recibido todas las dosis requeridas de esta vacuna.";
    }

    // Obtener el nombre del personal que registró la vacuna
    $sql_personal = "SELECT nombre, apellido FROM personal WHERE id = $personal_id";
    $resultado_personal = $conn->query($sql_personal);
    if ($resultado_personal) {
        $personal = $resultado_personal->fetch_assoc();
        $nombre_personal = $personal['nombre'] . ' ' . $personal['apellido'];
    }
}

// Obtener la lista de niños y vacunas después de manejar el formulario
$sql_ninos = "SELECT id, nombre, apellido FROM nino";
$ninos = $conn->query($sql_ninos);

$sql_vacunas = "SELECT id, tipo FROM vacuna_tipo";
$vacunas = $conn->query($sql_vacunas);

// Obtener la lista de personal
$sql_personal = "SELECT id, nombre, apellido FROM personal";
$personal_list = $conn->query($sql_personal);

// Cerrar la conexión al final, después de todas las consultas
$conn->close();
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Vacuna</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">VacMed</a>
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
        <h2>Registrar Vacuna</h2>

        <?php if (!empty($mensaje)) { ?>
            <div class="alert alert-info">
                <?php echo $mensaje; ?>
                <?php if ($mensaje == "Vacuna registrada exitosamente.") { ?>
                    <p><strong>Nombre del niño:</strong> <?php echo $nombre_nino; ?></p>
                    <p><strong>Tipo de vacuna:</strong> <?php echo $tipo_vacuna; ?></p>
                    <p><strong>Número de dosis:</strong> <?php echo $dosis_nueva; ?></p>
                    <p><strong>Fecha de vacunación:</strong> <?php echo $fecha_vacunacion; ?></p>
                    <p><strong>Registrado por:</strong> <?php echo $nombre_personal; ?></p>
                <?php } ?>
            </div>
        <?php } ?>

        <form action="registrar_vacuna.php" method="post">
            <div class="mb-3">
                <label for="nino_id" class="form-label">Niño:</label>
                <select class="form-control" id="nino_id" name="nino_id" required>
                    <option value="">Seleccionar niño</option>
                    <?php while ($row_nino = $ninos->fetch_assoc()) { ?>
                        <option value="<?php echo $row_nino['id']; ?>">
                            <?php echo $row_nino['nombre'] . " " . $row_nino['apellido']; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="vacuna_id" class="form-label">Vacuna:</label>
                <select class="form-control" id="vacuna_id" name="vacuna_id" required>
                    <option value="">Seleccionar vacuna</option>
                    <?php while ($row_vacuna = $vacunas->fetch_assoc()) { ?>
                        <option value="<?php echo $row_vacuna['id']; ?>">
                            <?php echo $row_vacuna['tipo']; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="fecha_vacunacion" class="form-label">Fecha de Vacunación:</label>
                <input type="date" class="form-control" id="fecha_vacunacion" name="fecha_vacunacion" required>
            </div>

            <div class="mb-3">
                <label for="personal_id" class="form-label">Personal que registra:</label>
                <select class="form-control" id="personal_id" name="personal_id" required>
                    <option value="">Seleccionar personal</option>
                    <?php while ($row_personal = $personal_list->fetch_assoc()) { ?>
                        <option value="<?php echo $row_personal['id']; ?>">
                            <?php echo $row_personal['nombre'] . " " . $row_personal['apellido']; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Registrar Vacuna</button>
        </form>
    </div>

    <script src="js/bootstrap.bundle.min.js"></script>
</body>

</html>
