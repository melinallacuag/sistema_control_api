<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

include "../config/conexion.php";
include "../utils/response.php";

$data = json_decode(file_get_contents("php://input"), true);

$id_taxista = $data['id_taxista'];
$id_vehiculo = $data['id_vehiculo'];
$id_ruta = $data['id_ruta'];
$id_controlador_salida = $data['id_controlador_salida'];

$sql = "INSERT INTO registros(
id_taxista,
id_vehiculo,
id_ruta,
fecha,
hora_salida,
estado,
id_controlador_salida
)
VALUES(
?,?,?,?,?,?,?
)";

$fecha = date("Y-m-d");
$hora = date("Y-m-d H:i:s");
$estado = "EN_RUTA";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "iiisssi",
    $id_taxista,
    $id_vehiculo,
    $id_ruta,
    $fecha,
    $hora,
    $estado,
    $id_controlador_salida
);

if($stmt->execute()){

    response(true,"Salida registrada");

}else{

    response(false,"Error");

}