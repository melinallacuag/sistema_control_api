<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Allow-Headers: Content-Type");

include "../config/conexion.php";
include "../utils/response.php";

$data = json_decode(file_get_contents("php://input"), true);

$id = $data['id_controlador'] ?? 0;

$nombre = $data['nombre'] ?? '';
$codigo_unico = $data['codigo_unico'] ?? '';
$id_paradero = $data['id_paradero'] ?? '';
$estado = $data['estado'] ?? 1;

$sql = "UPDATE controladores
SET
nombre = ?,
codigo_unico = ?,
id_paradero = ?,
estado = ?
WHERE id_controlador = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "ssiii",
    $nombre,
    $codigo_unico,
    $id_paradero,
    $estado,
    $id
);

if($stmt->execute()){

    response(true, "Controlador actualizado");

}else{

    response(false, "Error al actualizar");

}
?>