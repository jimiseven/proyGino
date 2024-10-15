<?php
// Incluir archivo de conexión
include 'conexion.php';

// Verificar si se ha recibido el ID del niño
$nino_id = isset($_GET['nino_id']) ? $_GET['nino_id'] : 0;

// Obtener la información del niño
$sql_nino = "SELECT nombre, apellido, fecha_nacimiento, TIMESTAMPDIFF(MONTH, fecha_nacimiento, CURDATE()) as edad_meses FROM nino WHERE id = $nino_id";
$result_nino = $conn->query($sql_nino);
$nino = $result_nino->fetch_assoc();

// Obtener las vacunas del niño
$sql_vacunas = "SELECT vt.tipo, v.dosis, v.fecha_vacunacion FROM vacunas v 
                JOIN vacuna_tipo vt ON v.tipo_id = vt.id 
                WHERE v.nino_id = $nino_id
                ORDER BY v.tipo_id, v.dosis";
$result_vacunas = $conn->query($sql_vacunas);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vacunas de Infante</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <style>
        .info-card {
            background-color: #222;
            color: orange;
            padding: 20px;
            border-radius: 10px;
            border: 2px solid orange;
        }

        .info-card h3 {
            text-align: center;
        }

        .info-card th,
        .info-card td {
            color: orange;
            text-align: center;
            padding: 10px;
        }

        .table-header {
            text-align: center;
            font-weight: bold;
        }
    </style>
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
    <div class="container mt-5 info-card">
        <h3>DATOS DE INFANTE</h3>
        <p><strong>Nombre Completo:</strong> <?php echo $nino['nombre'] . " " . $nino['apellido']; ?></p>
        <p><strong>Edad (Meses):</strong> <?php echo $nino['edad_meses']; ?></p>
        <!-- Botón para ver el calendario de vacunas -->
        <a href="calendario_vacunas.php?nino_id=<?php echo $nino_id; ?>" class="btn btn-warning btn-calendar">Ver Calendario de Vacunas</a>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="table-header">Vacuna</th>
                    <th class="table-header">Dosis 1</th>
                    <th class="table-header">Dosis 2</th>
                    <th class="table-header">Dosis 3</th>
                    <th class="table-header">Dosis 4</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Crear un array para agrupar las dosis de cada vacuna
                $vacunas = [];
                while ($row = $result_vacunas->fetch_assoc()) {
                    $vacunas[$row['tipo']][] = $row;
                }

                // Mostrar las vacunas y sus dosis
                foreach ($vacunas as $tipo_vacuna => $dosis_vacuna) {
                    echo "<tr>";
                    echo "<td>$tipo_vacuna</td>";

                    // Mostrar hasta 4 dosis (si existen)
                    for ($i = 1; $i <= 4; $i++) {
                        $dosis = array_filter($dosis_vacuna, function ($v) use ($i) {
                            return $v['dosis'] == $i;
                        });

                        if (!empty($dosis)) {
                            $dosis = array_values($dosis)[0];
                            echo "<td>" . date("d-m-Y", strtotime($dosis['fecha_vacunacion'])) . "</td>";
                        } else {
                            echo "<td>-</td>";
                        }
                    }
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>

<?php $conn->close(); ?>