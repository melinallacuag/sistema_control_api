<?php

header("Content-Type: application/json");

include "../config/conexion.php";
include "../utils/response.php";

$sql="SELECT
m.id_marcacion,
n.codigo_nfc,
c.nombre as controlador,
m.fecha_hora
FROM marcaciones m
INNER JOIN tarjetas_nfc n
ON m.id_nfc=n.id_nfc
INNER JOIN controladores c
ON m.id_controlador=c.id_controlador
ORDER BY m.fecha_hora DESC";

$result=$conn->query($sql);

$data=[];

while($row=$result->fetch_assoc()){
    $data[]=$row;
}

response(true,"Lista de marcaciones",$data);