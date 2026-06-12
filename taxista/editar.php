<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Allow-Headers: Content-Type");

include "../config/conexion.php";
include "../utils/response.php";

$data = json_decode(file_get_contents("php://input"), true);


$id_taxista = $data['id_taxista'];

$nombre = $data['nombre'];
$dni = $data['dni'];
$telefono = $data['telefono'];

$placa = $data['placa'];
$modelo = $data['modelo'];
$color = $data['color'];

$codigo_nfc = $data['codigo_nfc'];


// =========================
// EDITAR TAXISTA
// =========================
$sqlTaxista = "UPDATE taxistas
               SET
                    nombre='$nombre',
                    dni='$dni',
                    telefono='$telefono'
               WHERE id_taxista='$id_taxista'";


// =========================
// EDITAR VEHICULO
// =========================
$sqlVehiculo = "UPDATE vehiculos
                SET
                    placa='$placa',
                    modelo='$modelo',
                    color='$color'
                WHERE id_taxista='$id_taxista'";


// =========================
// EDITAR NFC
// =========================
$sqlNfc = "UPDATE tarjetas_nfc
           SET
                codigo_nfc='$codigo_nfc'
           WHERE id_taxista='$id_taxista'";



if(
    $conn->query($sqlTaxista) &&
    $conn->query($sqlVehiculo) &&
    $conn->query($sqlNfc)
){

    response(true, "Actualizado correctamente");

}else{

    response(false, "Error al actualizar");

}

?>