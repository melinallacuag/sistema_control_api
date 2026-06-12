<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Content-Type: application/json");

// RESPONDER AL PREFLIGHT
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

include "../config/conexion.php";
include "../utils/response.php";

$sql = "SELECT
            c.id_controlador,
            c.nombre,
            c.codigo_unico,
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