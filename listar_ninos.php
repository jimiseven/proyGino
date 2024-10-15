<?php
// Incluir archivo de conexión
include 'conexion.php';

// Consulta SQL para obtener los registros de la tabla 'nino'
$sql = "SELECT id, nombre, apellido, fecha_nacimiento FROM nino";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Niños</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Listado de Niños Registrados</h2>

        <!-- Verificar si hay resultados -->
        <?php if ($result->num_rows > 0) { ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Fecha de Nacimiento</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Recorrer los resultados y mostrarlos en la tabla
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["id"] . "</td>";
                        echo "<td>" . $row["nombre"] . "</td>";
                        echo "<td>" . $row["apellido"] . "</td>";
                        echo "<td>" . $row["fecha_nacimiento"] . "</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        <?php } else { ?>
            <div class="alert alert-info">No hay niños registrados.</div>
        <?php } ?>

        <!-- Cerrar la conexión -->
        <?php $conn->close(); ?>
    </div>
</body>
</html>
