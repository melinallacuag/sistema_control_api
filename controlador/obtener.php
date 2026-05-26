<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

include "../config/conexion.php";
include "../utils/response.php";

$id = $_GET['id'] ?? 0;

$sql = "SELECT * FROM controladores
        WHERE id_controlador = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();

if($result->num_rows > 0){

    response(true, "Controlador encontrado", $result->fetch_assoc());

}else{

    response(false, "No encontrado");

}
?>