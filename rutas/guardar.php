<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

include "../config/conexion.php";
include "../utils/response.php";

$data = json_decode(file_get_contents("php://input"), true);

$nombre = $data['nombre'] ?? '';
$id_paradero_origen = $data['id_paradero_origen'] ?? '';
$id_paradero_destino = $data['id_paradero_destino'] ?? '';
$estado = $data['estado'] ?? 1;

if(
    empty($nombre) ||
    empty($id_paradero_origen) ||
    empty($id_paradero_destino)
){
    response(false, "Complete los campos");
    exit;
}

$sql = "INSERT INTO rutas
        (
            nombre,
            id_paradero_origen,
            id_paradero_destino,
            estado
        )
        VALUES (?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "siii",
    $nombre,
    $id_paradero_origen,
    $id_paradero_destino,
    $estado
);

if($stmt->execute()){

    response(true, "Ruta registrada");

}else{

    response(false, "Error al registrar");

}
?>