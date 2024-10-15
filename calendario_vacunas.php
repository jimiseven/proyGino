<?php
// Incluir archivo de conexión
include 'conexion.php';

// Verificar si se ha recibido el ID del niño
$nino_id = isset($_GET['nino_id']) ? $_GET['nino_id'] : 0;

// Obtener la información del niño
$sql_nino = "SELECT nombre, apellido, fecha_nacimiento FROM nino WHERE id = $nino_id";
$result_nino = $conn->query($sql_nino);
$nino = $result_nino->fetch_assoc();
$fecha_nacimiento = $nino['fecha_nacimiento'];

// Función para sumar días a la fecha de nacimiento
function sumar_dias($fecha, $dias) {
    return date('Y-m-d', strtotime($fecha . " + $dias days"));
}

// Definir las fechas de las dosis para las vacunas
$calendario = [
    ['fecha' => sumar_dias($fecha_nacimiento, 0),    'vacuna' => 'Vacuna A', 'dosis' => 1, 'tipo_id' => 1],
    ['fecha' => sumar_dias($fecha_nacimiento, 60),   'vacuna' => 'Vacuna A', 'dosis' => 2, 'tipo_id' => 1],
    ['fecha' => sumar_dias($fecha_nacimiento, 0),    'vacuna' => 'Vacuna B', 'dosis' => 1, 'tipo_id' => 2],
    ['fecha' => sumar_dias($fecha_nacimiento, 30),   'vacuna' => 'Vacuna B', 'dosis' => 2, 'tipo_id' => 2],
    ['fecha' => sumar_dias($fecha_nacimiento, 60),   'vacuna' => 'Vacuna B', 'dosis' => 3, 'tipo_id' => 2],
    ['fecha' => sumar_dias($fecha_nacimiento, 90),   'vacuna' => 'Vacuna B', 'dosis' => 4, 'tipo_id' => 2],
    ['fecha' => sumar_dias($fecha_nacimiento, 30),   'vacuna' => 'Vacuna C', 'dosis' => 1, 'tipo_id' => 3],
    ['fecha' => sumar_dias($fecha_nacimiento, 60),   'vacuna' => 'Vacuna C', 'dosis' => 2, 'tipo_id' => 3],
    ['fecha' => sumar_dias($fecha_nacimiento, 90),   'vacuna' => 'Vacuna C', 'dosis' => 3, 'tipo_id' => 3]
];

// Obtener las vacunas administradas al niño
$sql_vacunas_administradas = "SELECT tipo_id, dosis FROM vacunas WHERE nino_id = $nino_id";
$result_vacunas_administradas = $conn->query($sql_vacunas_administradas);
$vacunas_administradas = [];

// Guardar las dosis administradas en un array para verificación
while ($row = $result_vacunas_administradas->fetch_assoc()) {
    $vacunas_administradas[$row['tipo_id']][$row['dosis']] = true;
}

// Ordenar las fechas de manera ascendente
array_multisort(array_column($calendario, 'fecha'), SORT_ASC, $calendario);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendario de Vacunas</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <style>
        .calendar-card {
            background-color: #f9f9f9;
            color: #333;
            padding: 20px;
            border-radius: 10px;
            border: 2px solid #007bff;
        }

        .calendar-card h3 {
            text-align: center;
            color: #007bff;
        }

        .calendar-table th, .calendar-table td {
            padding: 15px;
            text-align: center;
        }

        .calendar-table th {
            font-weight: bold;
            color: #333;
        }

        .calendar-table td {
            color: #555;
        }

        .table-border {
            border-bottom: 1px solid #007bff;
        }
    </style>
</head>

<body>
    <div class="container mt-5 calendar-card">
        <h3>CALENDARIO DE VACUNAS DEL INFANTE</h3>
        <p><strong>Nombre Completo:</strong> <?php echo $nino['nombre'] . " " . $nino['apellido']; ?></p>
        <p><strong>Edad (Meses):</strong> <?php echo round((strtotime(date('Y-m-d')) - strtotime($fecha_nacimiento)) / (30 * 24 * 60 * 60)); ?></p>

        <table class="table table-bordered calendar-table">
            <thead>
                <tr>
                    <th>FECHA</th>
                    <th class="table-border">TAREA</th>
                    <th class="table-border">ESTADO</th> <!-- Nueva columna para el estado -->
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($calendario as $evento) {
                    $fecha_formateada = date('d \d\e F', strtotime($evento['fecha']));
                    $estado = isset($vacunas_administradas[$evento['tipo_id']][$evento['dosis']]) ? "Administrada" : "Pendiente";

                    echo "<tr>";
                    echo "<td>$fecha_formateada</td>";
                    echo "<td>dosis : {$evento['dosis']} - {$evento['vacuna']}</td>";
                    echo "<td>$estado</td>"; // Mostrar si la vacuna ha sido administrada o no
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>

<?php $conn->close(); ?>
