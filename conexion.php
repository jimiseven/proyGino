<?php
// Datos de conexión
$host = 'localhost';  // Servidor de la base de datos (puede cambiarse si es necesario)
$dbname = 'bc_vac';   // Nombre de la base de datos
$username = 'root';   // Usuario de la base de datos
$password = '';       // Contraseña del usuario

try {
    // Crear una nueva instancia de PDO para conectarse a la base de datos MySQL
    $conexion = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    
    // Configurar PDO para lanzar excepciones en caso de error
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Conexión exitosa a la base de datos";
    
} catch (PDOException $e) {
    // Si ocurre un error, lo capturamos y mostramos un mensaje
    echo "Error de conexión: " . $e->getMessage();
}
?>
