<?php
// Datos de conexión
$servername = "localhost"; // Cambia a tu servidor si es necesario
$username = "root"; // Cambia al nombre de usuario de tu base de datos
$password = ""; // Cambia a la contraseña de tu base de datos
$dbname = "bc_vac"; // Nombre de la base de datos

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Configuración correcta
echo "Conexión exitosa a la base de datos.";

// Puedes cerrar la conexión cuando no la necesites más
// $conn->close();
?>
