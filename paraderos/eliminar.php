<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

include "../config/conexion.php";
include "../utils/response.php";

$id = $_GET['id'] ?? 0;

$sql = "DELETE FROM paraderos
WHERE id_paradero = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if($stmt->execute()){

    response(true, "Paradero eliminado");

}else{

    response(false, "Error al eliminar");

}
?>