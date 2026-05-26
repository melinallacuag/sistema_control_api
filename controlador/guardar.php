<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

include "../config/conexion.php";
include "../utils/response.php";

$data = json_decode(file_get_contents("php://input"), true);

$nombre = $data['nombre'] ?? '';
$codigo_unico = $data['codigo_unico'] ?? '';
$id_paradero = $data['id_paradero'] ?? '';
$tipo = $data['tipo'] ?? '';
$estado = $data['estado'] ?? 1;

if(
    empty($nombre) ||
    empty($codigo_unico) ||
    empty($id_paradero) ||
    empty($tipo)
){
    response(false, "Complete los campos");
    exit;
}

$sql = "INSERT INTO controladores
(nombre, codigo_unico, id_paradero, tipo, estado)
VALUES (?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "ssisi",
    $nombre,
    $codigo_unico,
    $id_paradero,
    $tipo,
    $estado
);

if($stmt->execute()){

    response(true, "Controlador registrado");

}else{

    response(false, "Error al registrar");

}
?>