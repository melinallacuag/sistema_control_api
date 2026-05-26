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

$sql = "DELETE FROM rutas WHERE id_ruta = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("i", $id_ruta);

if($stmt->execute()){

    response(true, "Ruta eliminada");

}else{

    response(false, "Error al eliminar");

}
?>