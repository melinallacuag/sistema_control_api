<?php

header("Content-Type: application/json");

include "../config/conexion.php";
include "../utils/response.php";

$data=json_decode(file_get_contents("php://input"),true);

$id_registro = $data['id_registro'];
$id_controlador_llegada = $data['id_controlador_llegada'];

$sql="UPDATE registros
SET
hora_llegada=?,
estado='FINALIZADO',
id_controlador_llegada=?
WHERE id_registro=?";

$horaLlegada=date("Y-m-d H:i:s");

$stmt=$conn->prepare($sql);

$stmt->bind_param(
    "sii",
    $horaLlegada,
    $id_controlador_llegada,
    $id_registro
);

if($stmt->execute()){

    response(true,"Llegada registrada");

}else{

    response(false,"Error");

}