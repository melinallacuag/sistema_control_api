<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

include "../config/conexion.php";
include "../utils/response.php";

$sql = "SELECT 
            r.id_ruta,
            r.nombre,
            r.estado,

            po.id_paradero AS id_paradero_origen,
            po.nombre AS paradero_origen,

            pd.id_paradero AS id_paradero_destino,
            pd.nombre AS paradero_destino

        FROM rutas r

        INNER JOIN paraderos po 
        ON r.id_paradero_origen = po.id_paradero

        INNER JOIN paraderos pd 
        ON r.id_paradero_destino = pd.id_paradero

        ORDER BY r.id_ruta DESC";

$result = $conn->query($sql);

$data = [];

while($row = $result->fetch_assoc()){
    $data[] = $row;
}

response(true, "Lista de rutas", $data);
?>