<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Allow-Headers: Content-Type");

include "../config/conexion.php";
include "../utils/response.php";

$data = json_decode(file_get_contents("php://input"), true);

$id = $data['id_paradero'] ?? 0;

$nombre = $data['nombre'] ?? '';
$ubicacion = $data['ubicacion'] ?? '';
$estado = $data['estado'] ?? 1;

if(empty($nombre) || empty($ubicacion)){
    response(false, "Complete los campos");
    exit;
}

$sql = "UPDATE paraderos
SET
nombre = ?,
ubicacion = ?,
estado = ?
WHERE id_paradero = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "ssii",
    $nombre,
    $ubicacion,
    $estado,
    $id
);

if($stmt->execute()){

    response(true, "Paradero actualizado");

}else{

    response(false, "Error al actualizar");

}
?>