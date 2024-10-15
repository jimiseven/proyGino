<?php
// Incluir archivo de conexión
include 'conexion.php';

// Variables para mensajes
$mensaje = "";

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nino_id = $_POST['nino_id'];
    $vacuna_id = $_POST['vacuna_id'];
    $fecha_vacunacion = $_POST['fecha_vacunacion'];

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

        // Registrar la vacuna con la dosis correspondiente
        $sql_insert = "INSERT INTO vacunas (tipo_id, dosis, fecha_vacunacion, nino_id) 
                       VALUES ($vacuna_id, $dosis_nueva, '$fecha_vacunacion', $nino_id)";
        if ($conn->query($sql_insert) === TRUE) {
            $mensaje = "Vacuna registrada exitosamente. Dosis: $dosis_nueva";
        } else {
            $mensaje = "Error al registrar la vacuna: " . $conn->error;
        }
    } else {
        $mensaje = "El niño ya ha recibido todas las dosis requeridas de esta vacuna.";
    }
}

// Obtener la lista de niños y vacunas después de manejar el formulario
$sql_ninos = "SELECT id, nombre, apellido FROM nino";
$ninos = $conn->query($sql_ninos);

$sql_vacunas = "SELECT id, tipo FROM vacuna_tipo";
$vacunas = $conn->query($sql_vacunas);

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
    <div class="container mt-5">
        <h2>Registrar Vacuna</h2>

        <?php if (!empty($mensaje)) { ?>
            <div class="alert alert-info"><?php echo $mensaje; ?></div>
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

            <button type="submit" class="btn btn-primary">Registrar Vacuna</button>
        </form>
    </div>
</body>
</html>
