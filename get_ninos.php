<?php
// Desactivar la visualización de errores
error_reporting(0);
ini_set('display_errors', 0);

// Incluir archivo de conexión
include 'conexion.php';

// Consulta SQL para obtener los registros de la tabla 'nino'
$sql = "SELECT id, nombre, apellido, fecha_nacimiento FROM nino";
$result = $conn->query($sql);

$datos = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $datos[] = $row;
    }
}

// Devolver los datos como JSON sin imprimir mensajes adicionales
header('Content-Type: application/json');
echo json_encode($datos);

// Cerrar la conexión
$conn->close();
?>
