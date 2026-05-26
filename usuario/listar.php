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
            u.id_usuario,
            u.nombre,
            u.usuario,
            u.id_rol,
            r.nombre AS rol,
            u.estado,
            u.fecha_creacion
        FROM usuarios u
        INNER JOIN roles r 
        ON u.id_rol = r.id_rol";

$result = $conn->query($sql);

$data = [];

while($row = $result->fetch_assoc()){
    $data[] = $row;
}

response(true, "Lista de usuarios", $data);
?>