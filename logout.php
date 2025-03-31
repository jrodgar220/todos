<?php

include 'db.php';
include 'headers.php';


$headers = getallheaders();
if (!isset($headers['Authorization'])) {
    http_response_code(401);
    echo json_encode(["success" => false, "message" => "No autorizado"]);
    exit();
}

$token = str_replace("Bearer ", "", $headers['Authorization']);

// Borrar el token de la BD
$stmt = $pdo->prepare("UPDATE users SET token = NULL WHERE token = ?");
$stmt->execute([$token]);

echo json_encode(["success" => true, "message" => "Sesión cerrada"]);
?>
