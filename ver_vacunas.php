<?php
// Incluir archivo de conexión
include 'conexion.php';

// Verificar si se ha recibido el ID del niño
$nino_id = isset($_GET['nino_id']) ? $_GET['nino_id'] : 0;

// Obtener la información del niño y su tutor
$sql_nino = "SELECT n.nombre, n.apellido, n.sexo, n.fecha_nacimiento, 
             TIMESTAMPDIFF(MONTH, n.fecha_nacimiento, CURDATE()) as edad_meses,
             CONCAT(t.nombre, ' ', t.apellidos) as nombre_completo_tutor, 
             t.carnet_identidad as carnet_tutor, t.relacion as relacion_tutor 
             FROM nino n
             JOIN tutor t ON n.id_tutor = t.id
             WHERE n.id = $nino_id";
$result_nino = $conn->query($sql_nino);
$nino = $result_nino->fetch_assoc();
$fecha_nacimiento = $nino['fecha_nacimiento'];

// Función para sumar días a la fecha de nacimiento
function sumar_dias($fecha, $dias) {
    return date('Y-m-d', strtotime($fecha . " + $dias days"));
}

// Definir las fechas de las dosis para las vacunas MMR, Hepatitis B y Polio
$calendario = [
    'MMR' => [
        ['dosis' => 1, 'fecha' => sumar_dias($fecha_nacimiento, 0)],
        ['dosis' => 2, 'fecha' => sumar_dias($fecha_nacimiento, 60)]
    ],
    'Hepatitis B' => [
        ['dosis' => 1, 'fecha' => sumar_dias($fecha_nacimiento, 0)],
        ['dosis' => 2, 'fecha' => sumar_dias($fecha_nacimiento, 30)],
        ['dosis' => 3, 'fecha' => sumar_dias($fecha_nacimiento, 60)],
        ['dosis' => 4, 'fecha' => sumar_dias($fecha_nacimiento, 90)]
    ],
    'Polio' => [
        ['dosis' => 1, 'fecha' => sumar_dias($fecha_nacimiento, 30)],
        ['dosis' => 2, 'fecha' => sumar_dias($fecha_nacimiento, 60)],
        ['dosis' => 3, 'fecha' => sumar_dias($fecha_nacimiento, 90)]
    ]
];

// Obtener las vacunas del niño
$sql_vacunas = "SELECT vt.tipo, v.dosis, v.fecha_vacunacion FROM vacunas v 
                JOIN vacuna_tipo vt ON v.tipo_id = vt.id 
                WHERE v.nino_id = $nino_id
                ORDER BY v.tipo_id, v.dosis";
$result_vacunas = $conn->query($sql_vacunas);

// Agrupar las vacunas administradas para verificar qué dosis ya se ha dado
$vacunas_administradas = [];
while ($row = $result_vacunas->fetch_assoc()) {
    $vacunas_administradas[$row['tipo']][$row['dosis']] = $row['fecha_vacunacion'];
}

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
            background-color: #f9f9f9;
            color: #333;
            padding: 20px;
            border-radius: 10px;
            border: 2px solid #007bff;
            margin-top: 20px;
        }

        .info-card h3 {
            text-align: center;
            color: #007bff;
        }

        .info-card .info-group {
            display: flex;
            justify-content: space-between;
        }

        .info-card .info-group div {
            width: 48%;
        }

        .table-header {
            text-align: center;
            font-weight: bold;
            background-color: #007bff;
            color: white;
        }

        .btn-calendar {
            margin-top: 15px;
            display: block;
            width: 100%;
            text-align: center;
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
            </ul>
        </div>
    </nav>

    <div class="container mt-5 info-card">
        <h3>DATOS DEL INFANTE</h3>
        <div class="info-group">
            <div>
                <p><strong>Nombre Completo:</strong> <?php echo $nino['nombre'] . " " . $nino['apellido']; ?></p>
                <p><strong>Sexo:</strong> <?php echo ucfirst($nino['sexo']); ?></p>
                <p><strong>Edad (Meses):</strong> <?php echo $nino['edad_meses']; ?></p>
                <p><strong>Fecha de Nacimiento:</strong> <?php echo date("d-m-Y", strtotime($nino['fecha_nacimiento'])); ?></p>
            </div>
            <div>
                <p><strong>Nombre del Tutor:</strong> <?php echo $nino['nombre_completo_tutor']; ?></p>
                <p><strong>Carnet del Tutor:</strong> <?php echo $nino['carnet_tutor']; ?></p>
                <p><strong>Relación con el Niño:</strong> <?php echo ucfirst($nino['relacion_tutor']); ?></p>
            </div>
        </div>

        <!-- Botón para ver el calendario de vacunas -->
        <a href="calendario_vacunas.php?nino_id=<?php echo $nino_id; ?>" class="btn btn-primary btn-calendar">Ver Calendario de Vacunas</a>

        <table class="table table-bordered mt-4">
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
                // Mostrar las vacunas y sus dosis
                foreach ($calendario as $vacuna => $dosis_info) {
                    echo "<tr>";
                    echo "<td>$vacuna</td>";

                    // Mostrar las fechas planificadas de las dosis y si ya fueron administradas
                    foreach ($dosis_info as $dosis) {
                        $fecha_sugerida = date("d-m-Y", strtotime($dosis['fecha']));
                        $estado = isset($vacunas_administradas[$vacuna][$dosis['dosis']]) ? " (Administrada)" : "";
                        echo "<td>$fecha_sugerida $estado</td>";
                    }

                    // Rellenar las columnas restantes si hay menos de 4 dosis
                    $dosis_count = count($dosis_info);
                    for ($i = $dosis_count; $i < 4; $i++) {
                        echo "<td>-</td>";
                    }

                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script src="js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php $conn->close(); ?>
