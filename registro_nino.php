<?php
// Conectar a la base de datos
$host = 'localhost';
$dbname = 'bc_vac';
$username = 'root';
$password = '';

try {
    $conexion = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Verificar si el formulario ha sido enviado
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Obtener los datos del formulario
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $fecha_nacimiento = $_POST['fecha_nacimiento'];

        // Preparar la consulta SQL para insertar el nuevo niño
        $sql = "INSERT INTO ninos (nombre, apellido, fecha_nacimiento) VALUES (:nombre, :apellido, :fecha_nacimiento)";
        $stmt = $conexion->prepare($sql);

        // Ejecutar la consulta con los valores del formulario
        $stmt->execute([
            ':nombre' => $nombre,
            ':apellido' => $apellido,
            ':fecha_nacimiento' => $fecha_nacimiento
        ]);

        echo "Niño registrado exitosamente.";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
