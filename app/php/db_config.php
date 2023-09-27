<?php
// Configuración de la base de datos
$db_host = "db"; 
$db_username = "testing"; 
$db_password = "testing"; 
$db_name = "testing"; 

// Crear una conexión a la base de datos
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión a la base de datos: " . $conn->connect_error);
}
?>
