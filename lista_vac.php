<?php
// Incluir archivo de conexión
include 'conexion.php';

// Consulta SQL para obtener los registros de vacunas junto con la información del niño y el tipo de vacuna
$sql = "SELECT v.id, n.nombre, n.apellido, vt.tipo, v.dosis, v.fecha_vacunacion
        FROM vacunas v
        INNER JOIN nino n ON v.nino_id = n.id
        INNER JOIN vacuna_tipo vt ON v.tipo_id = vt.id
        ORDER BY v.fecha_vacunacion DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Vacunas Registradas</title>
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
        </div>
    </nav>
    <div class="container mt-5">
        <h2>Listado de Vacunas Registradas</h2>

        <!-- Verificar si hay resultados -->
        <?php if ($result->num_rows > 0) { ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre del Niño</th>
                        <th>Tipo de Vacuna</th>
                        <th>Número de Dosis</th>
                        <th>Fecha de Vacunación</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Recorrer los resultados y mostrarlos en la tabla
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["id"] . "</td>";
                        echo "<td>" . $row["nombre"] . " " . $row["apellido"] . "</td>";
                        echo "<td>" . $row["tipo"] . "</td>";
                        echo "<td>Dosis " . $row["dosis"] . "</td>";
                        echo "<td>" . $row["fecha_vacunacion"] . "</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        <?php } else { ?>
            <div class="alert alert-info">No hay vacunas registradas.</div>
        <?php } ?>

        <!-- Cerrar la conexión -->
        <?php $conn->close(); ?>
    </div>

    <script src="js/bootstrap.bundle.min.js"></script>
</body>

</html>