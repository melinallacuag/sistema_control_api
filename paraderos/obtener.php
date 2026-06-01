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

$id = $_GET['id'] ?? 0;

$sql = "SELECT * FROM paraderos
WHERE id_paradero = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();

if($result->num_rows > 0){

    response(true, "Paradero encontrado", $result->fetch_assoc());

}else{

    response(false, "Paradero no encontrado");

}
?>