<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

include "../config/conexion.php";
include "../utils/response.php";

$data = json_decode(file_get_contents("php://input"), true);

$nombre = $data['nombre'] ?? '';
$dni = $data['dni'] ?? '';
$telefono = $data['telefono'] ?? '';

$placa = $data['placa'] ?? '';
$modelo = $data['modelo'] ?? '';
$color = $data['color'] ?? '';

$codigo_nfc = $data['codigo_nfc'] ?? '';

if (
    empty($nombre) ||
    empty($dni) ||
    empty($placa) ||
    empty($codigo_nfc)
) {
    response(false, "Complete los campos obligatorios");
    exit;
}

$sql = "INSERT INTO taxistas(
            nombre,
            dni,
            telefono,
            estado
        ) VALUES (?, ?, ?, 1)";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "sss",
    $nombre,
    $dni,
    $telefono
);

if ($stmt->execute()) {

    $id_taxista = $conn->insert_id;

    $sqlVehiculo = "INSERT INTO vehiculos(
                        placa,
                        modelo,
                        color,
                        id_taxista,
                        estado
                    ) VALUES (?, ?, ?, ?, 1)";

    $stmtVehiculo = $conn->prepare($sqlVehiculo);

    $stmtVehiculo->bind_param(
        "sssi",
        $placa,
        $modelo,
        $color,
        $id_taxista
    );

    $stmtVehiculo->execute();

    $sqlNfc = "INSERT INTO tarjetas_nfc(
                    codigo_nfc,
                    id_taxista,
                    estado
                ) VALUES (?, ?, 1)";

    $stmtNfc = $conn->prepare($sqlNfc);

    $stmtNfc->bind_param(
        "si",
        $codigo_nfc,
        $id_taxista
    );

    $stmtNfc->execute();

    response(true, "Registro completo");

} else {

    response(false, "Error al registrar");

}