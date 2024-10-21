<?php
$servername = "localhost"; // Cambia si es necesario
$username = "root"; // Cambia según tu configuración
$password = ""; // Debería ser la contraseña correcta para tu base de datos
$dbname = "bc_vac"; // El nombre de tu base de datos

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// No debe haber ningún echo aquí para "Conexión exitosa"
// Solo mantén la conexión abierta
?>
