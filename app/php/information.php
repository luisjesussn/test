<?php
// Incluir el archivo de configuración de la base de datos
require_once("db_config.php");

// Consultar estados (states) en la base de datos
$sql = "SELECT * FROM states";
$stmt = $conn->prepare($sql);

if ($stmt->execute()) {
    // Obtener el resultado de la consulta y almacenar estados en un array
    $result = $stmt->get_result();
    $states = array();
    $provinces = array();
    $candidates = array();

    while ($row = $result->fetch_assoc()) {
        $states[] = $row;
    }

    if ($states) {
        // Si se encontraron estados, obtener la región seleccionada (por defecto, la primera región)
        $id_region = $_GET["region"] ?? $states[0]['id'];

        // Consultar provincias (provinces) relacionadas con la región seleccionada
        $sql = "SELECT * FROM provinces WHERE id_region = $id_region";
        $stmt = $conn->prepare($sql);

        if ($stmt->execute()) {
            // Obtener el resultado de la consulta y almacenar provincias en un array
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                $provinces[] = $row;
            }
        }
    }

    // Consultar candidatos (candidates) en la base de datos
    $sql = "SELECT * FROM candidates";
    $stmt = $conn->prepare($sql);

    if ($stmt->execute()) {
        // Obtener el resultado de la consulta y almacenar candidatos en un array
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $candidates[] = $row;
        }
    }

    if ($states && $provinces) {
        // Si se encontraron estados y provincias, preparar una respuesta exitosa
        $response = array(
            'success' => true,
            'states' => $states,
            'provinces' => $provinces,
            'candidates' => $candidates
        );
    } else {
        // Si no se encontraron estados o provincias, preparar una respuesta de error
        $response = array(
            'success' => false,
            'message' => 'Error al registrar: ' . $stmt->error
        );
    }
} else {
    // Si hubo un error al ejecutar la consulta de estados, preparar una respuesta de error
    $response = array(
        'success' => false,
        'message' => 'Error al registrar: ' . $stmt->error
    );
}

// Cerrar la conexión a la base de datos y enviar la respuesta en formato JSON
$stmt->close();
$conn->close();
header('Content-Type: application/json');
echo json_encode($response);
?>
