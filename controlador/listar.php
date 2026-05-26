<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

include "../config/conexion.php";
include "../utils/response.php";

$sql = "SELECT
            c.id_controlador,
            c.nombre,
            c.codigo_unico,
            c.tipo,
            c.estado,
            p.nombre AS paradero
        FROM controladores c
        INNER JOIN paraderos p
        ON c.id_paradero = p.id_paradero";

$result = $conn->query($sql);

$data = [];

while($row = $result->fetch_assoc()){
    $data[] = $row;
}

response(true, "Lista de controladores", $data);
?>