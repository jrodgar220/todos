<?php
require_once '../dbconection/db.php'; // Asegúrate de que la conexión a la BD esté incluida

try {
    // Preparar la consulta SQL para obtener todos los usuarios
    $stmt = $pdo->prepare("SELECT id, username,  created_at FROM users");
    $stmt->execute();

    // Obtener los resultados en un array asociativo
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Devolver los datos en formato JSON
    echo json_encode(["success" => true, "users" => $users]);
} catch (PDOException $e) {
    // Manejar errores de la base de datos
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Error en la base de datos", "error" => $e->getMessage()]);
}
?>
