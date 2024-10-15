<?php
// Incluir archivo de conexión
include 'conexion.php';

// Verificar si se ha recibido el ID del niño
$nino_id = isset($_GET['nino_id']) ? $_GET['nino_id'] : 0;

// Obtener la información del niño
$sql_nino = "SELECT nombre, apellido FROM nino WHERE id = $nino_id";
$result_nino = $conn->query($sql_nino);
$nino = $result_nino->fetch_assoc();

// Obtener la lista de tipos de vacunas
$sql_vacunas = "SELECT id, tipo FROM vacuna_tipo";
$result_vacunas = $conn->query($sql_vacunas);

// Obtener la lista de personal
$sql_personal = "SELECT id, nombre, apellido FROM personal";
$result_personal = $conn->query($sql_personal);

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $vacuna_id = $_POST['vacuna_id'];
    $fecha_vacunacion = $_POST['fecha_vacunacion'];
    $personal_id = $_POST['personal_id']; // Recibir el ID del personal

    // Verificar cuál es la siguiente dosis que le corresponde al niño para esta vacuna
    $sql_dosis = "SELECT COALESCE(MAX(dosis) + 1, 1) AS siguiente_dosis FROM vacunas WHERE tipo_id = $vacuna_id AND nino_id = $nino_id";
    $result_dosis = $conn->query($sql_dosis);
    $dosis = $result_dosis->fetch_assoc()['siguiente_dosis'];

    // Insertar el registro de la vacuna, incluyendo el personal que la administra
    $sql_insert = "INSERT INTO vacunas (tipo_id, dosis, fecha_vacunacion, nino_id, personal_id)
                   VALUES ($vacuna_id, $dosis, '$fecha_vacunacion', $nino_id, $personal_id)";

    if ($conn->query($sql_insert) === TRUE) {
        // Redirigir al calendario de vacunas del infante
        header("Location: calendario_vacunas.php?nino_id=$nino_id");
        exit();
    } else {
        $mensaje = "<div class='alert alert-danger'>Error al registrar la vacuna: " . $conn->error . "</div>";
    }
}
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
        <h3>Registrar Vacuna para el niño</h3>

        <p><strong>Nombre del Niño:</strong> <?php echo $nino['nombre'] . " " . $nino['apellido']; ?></p>

        <?php if (isset($mensaje)) {
            echo $mensaje;
        } ?>

        <!-- Formulario para registrar vacuna -->
        <form action="registrar_vacuna_form.php?nino_id=<?php echo $nino_id; ?>" method="post">
            <div class="mb-3">
                <label for="vacuna_id" class="form-label">Seleccionar Vacuna:</label>
                <select class="form-control" id="vacuna_id" name="vacuna_id" required>
                    <option value="">Seleccionar</option>
                    <?php while ($row_vacuna = $result_vacunas->fetch_assoc()) { ?>
                        <option value="<?php echo $row_vacuna['id']; ?>"><?php echo $row_vacuna['tipo']; ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="fecha_vacunacion" class="form-label">Fecha de Vacunación:</label>
                <input type="date" class="form-control" id="fecha_vacunacion" name="fecha_vacunacion" required>
            </div>

            <div class="mb-3">
                <label for="personal_id" class="form-label">Personal que administra la vacuna:</label>
                <select class="form-control" id="personal_id" name="personal_id" required>
                    <option value="">Seleccionar Personal</option>
                    <?php while ($row_personal = $result_personal->fetch_assoc()) { ?>
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

<?php $conn->close(); ?>
