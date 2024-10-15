<?php
// Conectar a la base de datos
$host = 'localhost';
$dbname = 'bc_vac';
$username = 'root';
$password = '';

try {
    $conexion = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Consulta para obtener los niños registrados
    $sql = "SELECT * FROM ninos";
    $stmt = $conexion->prepare($sql);
    $stmt->execute();

    // Obtener los resultados en un array
    $ninos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Niños Registrados</title>
    <!-- Enlace local a Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Lista de Niños Registrados</h2>
        <table class="table table-bordered table-striped mt-3">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Fecha de Nacimiento</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($ninos) > 0): ?>
                    <?php foreach ($ninos as $nino): ?>
                        <tr>
                            <td><?php echo $nino['id']; ?></td>
                            <td><?php echo $nino['nombre']; ?></td>
                            <td><?php echo $nino['apellido']; ?></td>
                            <td><?php echo $nino['fecha_nacimiento']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center">No hay niños registrados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Vinculamos el archivo JS de Bootstrap de manera local -->
    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
