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

$id_usuario = $_GET['id_usuario'];

$sql = "SELECT * FROM usuarios
        WHERE id_usuario = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("i", $id_usuario);

$stmt->execute();

$result = $stmt->get_result();

$data = $result->fetch_assoc();

if($data){
    response(true, "Usuario encontrado", $data);
}else{
    response(false, "Usuario no encontrado");
}
?>