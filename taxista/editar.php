<?php

header("Content-Type: application/json");

include "../config/conexion.php";
include "../utils/response.php";

$id_taxista = $_POST['id_taxista'];

$nombre = $_POST['nombre'];
$dni = $_POST['dni'];
$telefono = $_POST['telefono'];

$placa = $_POST['placa'];
$modelo = $_POST['modelo'];
$color = $_POST['color'];

$codigo_nfc = $_POST['codigo_nfc'];


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