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

// Definir las fechas de las dosis para las vacunas MMR, Hepatitis B y Polio
$calendario = [
    // Vacunas MMR
    ['fecha' => sumar_dias($fecha_nacimiento, 0),    'vacuna' => 'MMR', 'dosis' => 1, 'tipo_id' => 1],
    ['fecha' => sumar_dias($fecha_nacimiento, 60),   'vacuna' => 'MMR', 'dosis' => 2, 'tipo_id' => 1],

    // Vacunas Hepatitis B
    ['fecha' => sumar_dias($fecha_nacimiento, 0),    'vacuna' => 'Hepatitis B', 'dosis' => 1, 'tipo_id' => 2],
    ['fecha' => sumar_dias($fecha_nacimiento, 30),   'vacuna' => 'Hepatitis B', 'dosis' => 2, 'tipo_id' => 2],
    ['fecha' => sumar_dias($fecha_nacimiento, 60),   'vacuna' => 'Hepatitis B', 'dosis' => 3, 'tipo_id' => 2],
    ['fecha' => sumar_dias($fecha_nacimiento, 90),   'vacuna' => 'Hepatitis B', 'dosis' => 4, 'tipo_id' => 2],

    // Vacunas Polio
    ['fecha' => sumar_dias($fecha_nacimiento, 30),   'vacuna' => 'Polio', 'dosis' => 1, 'tipo_id' => 3],
    ['fecha' => sumar_dias($fecha_nacimiento, 60),   'vacuna' => 'Polio', 'dosis' => 2, 'tipo_id' => 3],
    ['fecha' => sumar_dias($fecha_nacimiento, 90),   'vacuna' => 'Polio', 'dosis' => 3, 'tipo_id' => 3]
];

// Obtener las vacunas administradas al niño
$sql_vacunas_administradas = "SELECT tipo_id, dosis, fecha_vacunacion FROM vacunas WHERE nino_id = $nino_id";
$result_vacunas_administradas = $conn->query($sql_vacunas_administradas);
$vacunas_administradas = [];

// Guardar las dosis administradas en un array para verificación
while ($row = $result_vacunas_administradas->fetch_assoc()) {
    $vacunas_administradas[$row['tipo_id']][$row['dosis']] = $row['fecha_vacunacion'];
}

// Aplicar filtro por fecha si se han enviado los filtros
if (isset($_GET['fecha_inicio']) && isset($_GET['fecha_fin']) && $_GET['fecha_inicio'] != '' && $_GET['fecha_fin'] != '') {
    $fecha_inicio = $_GET['fecha_inicio'];
    $fecha_fin = $_GET['fecha_fin'];

    $calendario = array_filter($calendario, function ($evento) use ($fecha_inicio, $fecha_fin) {
        return $evento['fecha'] >= $fecha_inicio && $evento['fecha'] <= $fecha_fin;
    });
}

// Aplicar filtro por tipo de vacuna si se ha enviado el filtro
if (isset($_GET['tipo_vacuna']) && $_GET['tipo_vacuna'] != '') {
    $tipo_vacuna = $_GET['tipo_vacuna'];

    $calendario = array_filter($calendario, function ($evento) use ($tipo_vacuna) {
        return $evento['tipo_id'] == $tipo_vacuna;
    });
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

        .calendar-table th,
        .calendar-table td {
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

        .btn-register {
            margin-top: 10px;
        }

        .filter-container {
            display: flex;
            justify-content: flex-end;
            align-items: center;
        }

        .filter-form {
            display: flex;
            justify-content: flex-end;
            align-items: center;
        }

        .filter-form .col-md-4 {
            margin-left: 10px;
        }

        .filter-form button {
            margin-left: 10px;
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

    <div class="container mt-5 calendar-card">
        <h3>CALENDARIO DE VACUNAS DEL INFANTE</h3>
        <p><strong>Nombre Completo:</strong> <?php echo $nino['nombre'] . " " . $nino['apellido']; ?></p>
        <p><strong>Edad (Meses):</strong> <?php echo round((strtotime(date('Y-m-d')) - strtotime($nino['fecha_nacimiento'])) / (30 * 24 * 60 * 60)); ?></p>

        <!-- Contenedor de filtros y botón de registrar vacuna -->
        <div class="filter-container">
            <!-- Botón para registrar vacuna -->
            <a href="registrar_vacuna_form.php?nino_id=<?php echo $nino_id; ?>" class="btn btn-success btn-register">Registrar Vacuna</a>

            <!-- Formulario de filtros (solo tipo de vacuna) -->
            <form method="GET" action="calendario_vacunas.php" class="filter-form">
                <input type="hidden" name="nino_id" value="<?php echo $nino_id; ?>">

                <div class="col-md-4">
                    <label for="tipo_vacuna" class="form-label">Vacuna:</label>
                    <select name="tipo_vacuna" class="form-control">
                        <option value="">Todas</option>
                        <option value="1" <?php echo isset($_GET['tipo_vacuna']) && $_GET['tipo_vacuna'] == 1 ? 'selected' : ''; ?>>MMR</option>
                        <option value="2" <?php echo isset($_GET['tipo_vacuna']) && $_GET['tipo_vacuna'] == 2 ? 'selected' : ''; ?>>Hepatitis B</option>
                        <option value="3" <?php echo isset($_GET['tipo_vacuna']) && $_GET['tipo_vacuna'] == 3 ? 'selected' : ''; ?>>Polio</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Filtrar</button>
            </form>
        </div>

        <!-- Tabla de Calendario -->
        <table class="table table-bordered calendar-table mt-4">
            <thead>
                <tr>
                    <th>FECHA SUGERIDA</th>
                    <th class="table-border">TAREA</th>
                    <th class="table-border">ESTADO</th>
                    <th class="table-border">FECHA DE ADMINISTRACIÓN</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($calendario as $evento) {
                    $fecha_formateada = date('d \d\e F', strtotime($evento['fecha']));
                    $estado = isset($vacunas_administradas[$evento['tipo_id']][$evento['dosis']]) ? "Administrada" : "Pendiente";
                    $fecha_administracion = isset($vacunas_administradas[$evento['tipo_id']][$evento['dosis']]) ? date('d \d\e F', strtotime($vacunas_administradas[$evento['tipo_id']][$evento['dosis']])) : "-";

                    echo "<tr>";
                    echo "<td>$fecha_formateada</td>";
                    echo "<td>dosis : {$evento['dosis']} - {$evento['vacuna']}</td>";
                    echo "<td>$estado</td>";
                    echo "<td>$fecha_administracion</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script src="js/bootstrap.bundle.min.js"></script>
</body>

</html>
