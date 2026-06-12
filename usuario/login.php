<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Content-Type: application/json");

// RESPONDER AL PREFLIGHT
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

include "../config/conexion.php";
include "../utils/response.php";

// RECIBIR JSON
$data = json_decode(file_get_contents("php://input"), true);

$usuario = $data['usuario'] ?? '';
$password = $data['password'] ?? '';

if(empty($usuario) || empty($password)){
    response(false, "Complete los campos");
    exit;
}

$sql = "SELECT 
            u.id_usuario,
            u.nombre,
            u.usuario,
            u.password,
            u.estado,
            u.id_controlador,
            c.nombre AS controlador,
            c.id_paradero,
            r.id_rol,
            r.nombre AS rol,
            p.nombre AS paradero
        FROM usuarios u
        INNER JOIN roles r 
        ON u.id_rol = r.id_rol
        LEFT JOIN controladores c
        ON u.id_controlador = c.id_controlador
        LEFT JOIN paraderos p
        ON c.id_paradero = p.id_paradero
        WHERE u.usuario = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("s", $usuario);

$stmt->execute();

$result = $stmt->get_result();

if($result->num_rows > 0){

    $row = $result->fetch_assoc();

    if(password_verify($password, $row['password'])){

        if($row['estado'] == 0){

            response(false, "Usuario inactivo");
            exit;

        }

        unset($row['password']);

        response(true, "Login correcto", $row);

    }else{

        response(false, "Contraseña incorrecta");

    }

}else{

    response(false, "Usuario no encontrado");

}