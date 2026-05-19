<?php
header("Content-Type: application/json");
include "../config/conexion.php";
include "../utils/response.php";

$sql = "SELECT * FROM taxistas";

$result = $conn->query($sql);

$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

response(true, "Lista de asistencias", $data);
?>