<?php
 require 'db.php'; // Asegura que la conexión a la BD esté disponible

function verificarSesion() {
    global $pdo; // Usar la conexión definida en db.php

    $headers = getallheaders();
    
    if (!isset($headers['Authorization'])) {
        http_response_code(401);
        echo json_encode(["success" => false, "message" => "No autorizado"]);
        exit();
    }

    $token = str_replace("Bearer ", "", $headers['Authorization']);

    // Verificar el token en la base de datos
    $stmt = $pdo->prepare("SELECT id FROM users WHERE token = ?");
    $stmt->execute([$token]);
    $user = $stmt->fetch();

    if (!$user) {
        http_response_code(403);
        echo json_encode(["success" => false, "message" => "Token inválido"]);
        exit();
    }

    return $user['id']; // Devolver el ID del usuario autenticado
}
?>