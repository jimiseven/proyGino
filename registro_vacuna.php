<?php
// Conectar a la base de datos
$host = 'localhost';
$dbname = 'bc_vac';
$username = 'root';
$password = '';

try {
    $conexion = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Obtener los niños registrados para seleccionarlos en el formulario
    $sql = "SELECT id, nombre, apellido FROM ninos";
    $stmt = $conexion->prepare($sql);
    $stmt->execute();
    $ninos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Verificar si el formulario ha sido enviado
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Obtener los datos del formulario
        $tipo = $_POST['tipo'];
        $dosis = $_POST['dosis'];
        $fecha_vacunacion = $_POST['fecha_vacunacion'];
        $nino_id = $_POST['nino_id'];

        // Insertar los datos en la tabla vacunas
        $sql = "INSERT INTO vacunas (tipo, dosis, fecha_vacunacion, nino_id) VALUES (:tipo, :dosis, :fecha_vacunacion, :nino_id)";
        $stmt = $conexion->prepare($sql);

        // Ejecutar la consulta
        $stmt->execute([
            ':tipo' => $tipo,
            ':dosis' => $dosis,
            ':fecha_vacunacion' => $fecha_vacunacion,
            ':nino_id' => $nino_id
        ]);

        echo "Vacuna registrada exitosamente.";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Vacuna</title>
    <!-- Enlace local a Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Registro de Vacuna</h2>
        <!-- Formulario para registrar una vacuna -->
        <form action="" method="POST">
            <div class="mb-3">
                <label for="tipo" class="form-label">Tipo de Vacuna</label>
                <select class="form-control" id="tipo" name="tipo" required>
                    <option value="" disabled selected>Seleccione el tipo de vacuna</option>
                    <option value="A">Tipo A</option>
                    <option value="B">Tipo B</option>
                    <option value="C">Tipo C</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="dosis" class="form-label">Dosis</label>
                <input type="number" class="form-control" id="dosis" name="dosis" min="1" required>
            </div>
            <div class="mb-3">
                <label for="fecha_vacunacion" class="form-label">Fecha de Vacunación</label>
                <input type="date" class="form-control" id="fecha_vacunacion" name="fecha_vacunacion" required>
            </div>
            <div class="mb-3">
                <label for="nino_id" class="form-label">Niño</label>
                <select class="form-control" id="nino_id" name="nino_id" required>
                    <option value="" disabled selected>Seleccione un niño</option>
                    <?php foreach ($ninos as $nino): ?>
                        <option value="<?php echo $nino['id']; ?>">
                            <?php echo $nino['nombre'] . ' ' . $nino['apellido']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Registrar Vacuna</button>
        </form>
    </div>

    <!-- Vinculamos el archivo JS de Bootstrap de manera local -->
    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
