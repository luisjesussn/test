<?php
/**
 * Archivo: submit.php
 * Descripción: Este script PHP maneja la recepción y procesamiento de datos del formulario de registro.
 * El formulario envía datos como nombre, alias, RUT, correo electrónico, estado, provincia, candidato y cómo se enteraron.
 * El script verifica si ya existe un registro con el mismo correo electrónico o RUT antes de insertar nuevos datos.
 * Luego, registra los datos en la base de datos y devuelve una respuesta JSON.
 */

// Incluye el archivo de configuración de la base de datos
require_once("db_config.php");

// Verifica si la solicitud es de tipo POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtiene los datos del formulario
    $name = $_POST['name'];
    $alias = $_POST['alias'];
    $rut = $_POST['rut'];
    $email = $_POST['email'];
    $state = $_POST['state'];
    $province = $_POST['province'];
    $candidate = $_POST['candidate'];
    $with_us = $_POST['with_us'];

    // Verifica si ya existe un registro con el mismo correo electrónico o RUT
    $sql = "SELECT * FROM formulario WHERE rut = '$rut' OR email = '$email'";
    $stmt = $conn->prepare($sql);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        if ($row) {
            // Ya existe un registro con el mismo correo electrónico o RUT
            $response = array(
                'success' => false,
                'message' => 'Ya existe un registro con este mail u rut'
            );
            header('Content-Type: application/json');
            echo json_encode($response);
            exit();
        }
    }

    // Prepara la consulta SQL para insertar nuevos datos
    $sql = "INSERT INTO formulario (name, alias, rut, email, state, province, candidate, with_us) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssss", $name, $alias, $rut, $email, $state, $province, $candidate, $with_us);

    // Ejecuta la consulta SQL para insertar los datos
    if ($stmt->execute()) {
        $response = array(
            'success' => true,
            'message' => 'Registro exitoso'
        );
    } else {
        $response = array(
            'success' => false,
            'message' => 'Error al registrar: ' . $stmt->error
        );
    }

    // Cierra la conexión y envía una respuesta JSON
    $stmt->close();
    $conn->close();

    header('Content-Type: application/json');
    echo json_encode($response);
}
