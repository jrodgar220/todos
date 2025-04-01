<?php
include 'db.php';
include 'auth.php'; // Incluye la validación de sesión

header("Access-Control-Allow-Origin: *"); // Permitir solicitudes desde cualquier origen (cambiar en producción)
header("Access-Control-Allow-Methods: GET, POST, PATCH, DELETE, OPTIONS"); // Métodos permitidos
header("Access-Control-Allow-Headers: Authorization, Content-Type"); // Cabeceras permitidas
header("Content-Type: application/json");


// Manejo de preflight para OPTIONS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}


$user_id = verificarSesion(); // Obtiene el ID del usuario autenticado

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    // Obtener tareas del usuario autenticado
    $stmt = $pdo->prepare("SELECT * FROM tasks WHERE user_id = ?");
    $stmt->execute([$user_id]);
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}

if ($method === 'POST') {
    // Crear nueva tarea
    $data = json_decode(file_get_contents("php://input"), true);
    if (isset($data['description'])) {
        $stmt = $pdo->prepare("INSERT INTO tasks (user_id, description, completed) VALUES (?, ?, FALSE)");
        $stmt->execute([$user_id, $data['description']]);       
        echo json_encode(["success" => true]);
    }
}

if ($method === 'PATCH') {
    // Marcar tarea como completada
    parse_str(file_get_contents("php://input"), $data);
    if (isset($data['id'])) {
        $stmt = $pdo->prepare("UPDATE tasks SET completed = TRUE WHERE id = ? AND user_id = ?");        $stmt->execute([$data['id'], $user_id]);
        echo json_encode(["success" => true]);
    }
}

if ($method === 'DELETE') {
    // Eliminar tarea
    parse_str(file_get_contents("php://input"), $data);
    if (isset($data['id'])) {
        $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = ? AND user_id = ?");
        $stmt->execute([$data['id'], $user_id]);
        echo json_encode(["success" => true]);
    }
}
?>