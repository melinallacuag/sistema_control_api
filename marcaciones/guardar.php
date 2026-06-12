<?php

header("Content-Type: application/json");

include "../config/conexion.php";
include "../utils/response.php";

$data=json_decode(file_get_contents("php://input"),true);

$id_nfc = $data['id_nfc'];
$id_controlador = $data['id_controlador'];
$id_registro = $data['id_registro'];

$fecha_hora=date("Y-m-d H:i:s");

$sql="INSERT INTO marcaciones(
id_nfc,
id_controlador,
id_registro,
fecha_hora
)
VALUES(?,?,?,?)";

$stmt=$conn->prepare($sql);

$stmt->bind_param(
    "iiis",
    $id_nfc,
    $id_controlador,
    $id_registro,
    $fecha_hora
);

if($stmt->execute()){

    response(true,"Marcación registrada");

}else{

    response(false,"Error");

}