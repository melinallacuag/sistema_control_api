<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

include "../config/conexion.php";

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode([
        "status" => false,
        "message" => "No llegaron datos"
    ]);
    exit();
}

$nombre  = $data['nombre'] ?? '';
$usuario = $data['usuario'] ?? '';
$password = $data['password'] ?? '';
$id_rol  = intval($data['id_rol'] ?? 0);
$estado  = intval($data['estado'] ?? 1);

$passwordHash = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO usuarios
(nombre, usuario, password, id_rol, estado)
VALUES (?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

if (!$stmt) {

    echo json_encode([
        "status" => false,
        "message" => "Error prepare: " . $conn->error
    ]);

    exit();
}

$stmt->bind_param(
    "sssii",
    $nombre,
    $usuario,
    $passwordHash,
    $id_rol,
    $estado
);

if ($stmt->execute()) {

    echo json_encode([
        "status" => true,
        "message" => "Usuario registrado"
    ]);

} else {

    echo json_encode([
        "status" => false,
        "message" => "Error execute: " . $stmt->error
    ]);
}
?>