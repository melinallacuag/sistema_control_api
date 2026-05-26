<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: POST, PUT, OPTIONS");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

include "../config/conexion.php";
include "../utils/response.php";

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    response(false, "No llegaron datos");
    exit();
}

$id_usuario = $data['id_usuario'] ?? 0;
$nombre     = $data['nombre'] ?? '';
$usuario    = $data['usuario'] ?? '';
$id_rol     = $data['id_rol'] ?? 0;
$estado     = $data['estado'] ?? 1;
$password   = $data['password'] ?? '';

if (!empty($password)) {

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    $sql = "UPDATE usuarios
            SET nombre = ?,
                usuario = ?,
                password = ?,
                id_rol = ?,
                estado = ?
            WHERE id_usuario = ?";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "sssiii",
        $nombre,
        $usuario,
        $passwordHash,
        $id_rol,
        $estado,
        $id_usuario
    );

} else {

    $sql = "UPDATE usuarios
            SET nombre = ?,
                usuario = ?,
                id_rol = ?,
                estado = ?
            WHERE id_usuario = ?";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "ssiii",
        $nombre,
        $usuario,
        $id_rol,
        $estado,
        $id_usuario
    );
}

if ($stmt->execute()) {

    response(true, "Usuario actualizado");

} else {

    response(false, "Error al actualizar: " . $stmt->error);
}