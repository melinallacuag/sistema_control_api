<?php

header("Content-Type: application/json");

include "../config/conexion.php";
include "../utils/response.php";

$data = json_decode(file_get_contents("php://input"), true);

$codigo_nfc = $data['codigo_nfc'] ?? '';
$id_controlador = $data['id_controlador'] ?? 0;

if (empty($codigo_nfc) || empty($id_controlador)) {
    response(false, "Datos incompletos");
    exit;
}

try {

    // BUSCAR TARJETA NFC
    $sql = "SELECT * 
            FROM tarjetas_nfc 
            WHERE codigo_nfc = ? 
            AND estado = 1";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $codigo_nfc);
    $stmt->execute();

    $nfc = $stmt->get_result()->fetch_assoc();

    if (!$nfc) {
        response(false, "Tarjeta NFC no encontrada");
        exit;
    }

    $id_taxista = $nfc['id_taxista'];
    $id_nfc = $nfc['id_nfc'];

    // BUSCAR VEHÍCULO
    $sql = "SELECT * 
            FROM vehiculos 
            WHERE id_taxista = ?
            LIMIT 1";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_taxista);
    $stmt->execute();

    $vehiculo = $stmt->get_result()->fetch_assoc();

    if (!$vehiculo) {
        response(false, "Vehículo no encontrado");
        exit;
    }

    $id_vehiculo = $vehiculo['id_vehiculo'];

    // BUSCAR CONTROLADOR
    $sql = "SELECT * 
            FROM controladores
            WHERE id_controlador = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_controlador);
    $stmt->execute();

    $controlador = $stmt->get_result()->fetch_assoc();

    if (!$controlador) {
        response(false, "Controlador no encontrado");
        exit;
    }

    // BUSCAR REGISTRO ABIERTO
$sql = "SELECT
            r.*,
            ru.id_paradero_origen,
            ru.id_paradero_destino
        FROM registros r
        INNER JOIN rutas ru
            ON r.id_ruta = ru.id_ruta
        WHERE r.id_taxista = ?
        AND r.estado = 'EN_RUTA'
        LIMIT 1";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_taxista);
$stmt->execute();

$registro = $stmt->get_result()->fetch_assoc();

    /*
     * SALIDA
     */
    if (!$registro) {

        // BUSCAR RUTA SEGÚN EL PARADERO
        $sql = "SELECT *
                FROM rutas
                WHERE id_paradero_origen = ?
                LIMIT 1";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $controlador['id_paradero']);
        $stmt->execute();

        $ruta = $stmt->get_result()->fetch_assoc();

        if (!$ruta) {
            response(false, "No existe ruta configurada");
            exit;
        }

        $sql = "INSERT INTO registros(
                    id_taxista,
                    id_vehiculo,
                    id_ruta,
                    fecha,
                    hora_salida,
                    estado,
                    id_controlador_salida
                )
                VALUES(
                    ?,
                    ?,
                    ?,
                    CURDATE(),
                    NOW(),
                    'EN_RUTA',
                    ?
                )";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "iiii",
            $id_taxista,
            $id_vehiculo,
            $ruta['id_ruta'],
            $id_controlador
        );

        $stmt->execute();

        $id_registro = $conn->insert_id;

        // MARCACIÓN
        $sql = "INSERT INTO marcaciones(
                    id_nfc,
                    id_controlador,
                    id_registro,
                    fecha_hora
                )
                VALUES(
                    ?,
                    ?,
                    ?,
                    NOW()
                )";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "iii",
            $id_nfc,
            $id_controlador,
            $id_registro
        );

        $stmt->execute();

        response(true, "Salida registrada correctamente", [
            "tipo" => "SALIDA",
            "id_registro" => $id_registro
        ]);

        exit;
    }

    /*
 * VALIDAR DESTINO
 */
if ($controlador['id_paradero'] != $registro['id_paradero_destino']) {

    response(
        false,
        "Este controlador no corresponde al destino de la ruta"
    );

    exit;
}

    /*
     * LLEGADA
     */
    $sql = "UPDATE registros
            SET
                hora_llegada = NOW(),
                estado = 'FINALIZADO',
                id_controlador_llegada = ?
            WHERE id_registro = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "ii",
        $id_controlador,
        $registro['id_registro']
    );

    $stmt->execute();

    // MARCACIÓN
    $sql = "INSERT INTO marcaciones(
                id_nfc,
                id_controlador,
                id_registro,
                fecha_hora
            )
            VALUES(
                ?,
                ?,
                ?,
                NOW()
            )";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "iii",
        $id_nfc,
        $id_controlador,
        $registro['id_registro']
    );

    $stmt->execute();

    response(true, "Llegada registrada correctamente", [
        "tipo" => "LLEGADA",
        "id_registro" => $registro['id_registro']
    ]);

} catch (Exception $e) {

    response(false, $e->getMessage());

}