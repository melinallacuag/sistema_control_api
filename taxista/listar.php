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
            t.id_taxista,
            t.nombre,
            t.dni,
            t.telefono,
            v.placa,
            v.modelo,
            n.codigo_nfc
        FROM taxistas t
        LEFT JOIN vehiculos v
            ON t.id_taxista = v.id_taxista
        LEFT JOIN tarjetas_nfc n
            ON t.id_taxista = n.id_taxista";

$result = $conn->query($sql);

$data = [];

while($row = $result->fetch_assoc()){
    $data[] = $row;
}

response(true,"Lista de taxistas",$data);

?>