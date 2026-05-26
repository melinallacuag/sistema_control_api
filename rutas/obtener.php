<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

include "../config/conexion.php";
include "../utils/response.php";

$id_ruta = $_GET['id_ruta'] ?? '';

if(empty($id_ruta)){
    response(false, "ID requerido");
    exit;
}

$sql = "SELECT * FROM rutas WHERE id_ruta = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_ruta);
$stmt->execute();

$result = $stmt->get_result();

if($result->num_rows > 0){

    $data = $result->fetch_assoc();

    response(true, "Ruta encontrada", $data);

}else{

    response(false, "Ruta no encontrada");

}
?>