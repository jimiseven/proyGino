<?php
<<<<<<< HEAD
// <<<<<<< HEAD
// // Datos de conexión
// $servername = "localhost"; // Cambia a tu servidor si es necesario
// $username = "root"; // Cambia al nombre de usuario de tu base de datos
// $password = ""; // Cambia a la contraseña de tu base de datos
// // $dbname = "bc_vac"; // Nombre de la base de datos
// $dbname = "bd_v2"; // Nombre de la base de datos
// =======
// Cargar variables de entorno manualmente si estás en local (opcional)
if (file_exists(__DIR__ . '/.env')) {
    $lines = file(__DIR__ . '/.env');
    foreach ($lines as $line) {
        if (trim($line) != '' && strpos(trim($line), '#') !== 0) {
            putenv(trim($line));
        }
    }
}

// Obtener las variables de entorno con getenv()
$servername = getenv('DB_HOST');
$username = getenv('DB_USER');
$password = getenv('DB_PASSWORD');
$dbname = getenv('DB_NAME');
// >>>>>>> version_1
=======
$servername = "localhost"; // Cambia si es necesario
$username = "root"; // Cambia según tu configuración
$password = ""; // Debería ser la contraseña correcta para tu base de datos
$dbname = "bc_vac"; // El nombre de tu base de datos
>>>>>>> version_1

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// No debe haber ningún echo aquí para "Conexión exitosa"
// Solo mantén la conexión abierta
?>
