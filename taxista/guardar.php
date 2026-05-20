<?php

include "../config/conexion.php";
include "../utils/response.php";

$nombre = $_POST['nombre'];
$dni = $_POST['dni'];
$telefono = $_POST['telefono'];

$placa = $_POST['placa'];
$modelo = $_POST['modelo'];
$color = $_POST['color'];

$codigo_nfc = $_POST['codigo_nfc'];

$sql = "INSERT INTO taxistas(nombre,dni,telefono,estado)
        VALUES('$nombre','$dni','$telefono',1)";

if($conn->query($sql)){

    $id_taxista = $conn->insert_id;

    $sqlVehiculo = "INSERT INTO vehiculos(
                        placa,
                        modelo,
                        color,
                        id_taxista,
                        estado
                    )
                    VALUES(
                        '$placa',
                        '$modelo',
                        '$color',
                        '$id_taxista',
                        1
                    )";

    $conn->query($sqlVehiculo);

    $sqlNfc = "INSERT INTO tarjetas_nfc(
                    codigo_nfc,
                    id_taxista,
                    estado
                )
                VALUES(
                    '$codigo_nfc',
                    '$id_taxista',
                    1
                )";

    $conn->query($sqlNfc);

    response(true, "Registro completo");

}else{
    response(false, "Error al registrar");
}
?>