<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

include "../config/conexion.php";
include "../utils/response.php";

$data = json_decode(file_get_contents("php://input"), true);

$nombre = $data['nombre'] ?? '';
$ubicacion = $data['ubicacion'] ?? '';
$estado = $data['estado'] ?? 1;

if(empty($nombre) || empty($ubicacion)){
    response(false, "Complete los campos");
    exit;
}

$sql = "INSERT INTO paraderos(nombre, ubicacion, estado)
VALUES (?, ?, ?)";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "ssi",
    $nombre,
    $ubicacion,
    $estado
);

if($stmt->execute()){

    response(true, "Paradero registrado");

}else{

    response(false, "Error al registrar");

}
?>