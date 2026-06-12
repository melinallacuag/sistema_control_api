<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

include "../config/conexion.php";
include "../utils/response.php";

$sql = "SELECT
r.id_registro,
t.nombre as taxista,
v.placa,
ru.nombre as ruta,
r.fecha,
r.hora_salida,
r.hora_llegada,
r.estado
FROM registros r
INNER JOIN taxistas t
ON r.id_taxista=t.id_taxista
INNER JOIN vehiculos v
ON r.id_vehiculo=v.id_vehiculo
INNER JOIN rutas ru
ON r.id_ruta=ru.id_ruta";

$result = $conn->query($sql);

$data=[];

while($row=$result->fetch_assoc()){
    $data[]=$row;
}

response(true,"Lista",$data);