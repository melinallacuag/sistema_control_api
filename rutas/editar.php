<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Allow-Headers: Content-Type");

include "../config/conexion.php";
include "../utils/response.php";

$data = json_decode(file_get_contents("php://input"), true);

$id_ruta = $data['id_ruta'] ?? '';
$nombre = $data['nombre'] ?? '';
$id_paradero_origen = $data['id_paradero_origen'] ?? '';
$id_paradero_destino = $data['id_paradero_destino'] ?? '';
$estado = $data['estado'] ?? 1;

if(empty($id_ruta)){
    response(false, "ID requerido");
    exit;
}

$sql = "UPDATE rutas 
        SET
            nombre = ?,
            id_paradero_origen = ?,
            id_paradero_destino = ?,
            estado = ?
        WHERE id_ruta = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "siiii",
    $nombre,
    $id_paradero_origen,
    $id_paradero_destino,
    $estado,
    $id_ruta
);

if($stmt->execute()){

    response(true, "Ruta actualizada");

}else{

    response(false, "Error al actualizar");

}
?>