<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

include "../config/conexion.php";
include "../utils/response.php";

$id_taxista = $_GET['id_taxista'];

$sql = "SELECT
            t.id_taxista,
            t.nombre,
            t.dni,
            t.telefono,

            v.placa,
            v.modelo,
            v.color,

            n.codigo_nfc

        FROM taxistas t

        LEFT JOIN vehiculos v
            ON t.id_taxista = v.id_taxista

        LEFT JOIN tarjetas_nfc n
            ON t.id_taxista = n.id_taxista

        WHERE t.id_taxista = '$id_taxista'";

$result = $conn->query($sql);

if($result->num_rows > 0){

    $data = $result->fetch_assoc();

    response(true, "Taxista encontrado", $data);

}else{

    response(false, "No encontrado");

}

?>