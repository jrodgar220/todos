<?php
require_once '../dbconection/db.php';
require_once '../utils/auth.php';
require_once '../repository/TaskRepository.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: Authorization, Content-Type");
header("Access-Control-Allow-Methods: GET, POST, PATCH, DELETE, OPTIONS");
header("Content-Type: application/json");

// Responder preflight (CORS)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$user_id = verificarSesion();
$repository = new TaskRepository($pdo);
$method = $_SERVER['REQUEST_METHOD'];

// 🟢 OBTENER TAREAS
if ($method === 'GET') {
    $tasks = $repository->getTasksByUserId($user_id);
    echo json_encode(array_map(function ($task) {
        return $task->toArray();
    }, $tasks));
    exit();
}

// 🟢 CREAR UNA NUEVA TAREA (POST)
if ($method === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    // 🔴 Validar que la petición contiene 'description'
    if (!isset($data['description']) || empty(trim($data['description']))) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "La descripción es obligatoria."]);
        exit();
    }

    // 🔴 Limpiar y validar datos
    $description = trim($data['description']);
    if (strlen($description) > 255) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "La descripción no puede superar 255 caracteres."]);
        exit();
    }

    // 🟢 Asegurar que 'completed' sea un booleano (default: false)

    // 🔵 Crear tarea si pasa validaciones
    $task = new Task(null, $user_id, $description);
    if ($repository->createTask($task)) {
        echo json_encode(["success" => true, "message" => "Tarea creada exitosamente."]);
    } else {
        http_response_code(500);
        echo json_encode(["success" => false, "message" => "Error al guardar la tarea."]);
    }
    exit();
}

// 🟢 MARCAR COMO COMPLETADA (PATCH)
if ($method === 'PATCH') {
    parse_str(file_get_contents("php://input"), $data);

    // 🔴 Validar ID
    if (!isset($data['id']) || !is_numeric($data['id'])) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "ID de tarea inválido."]);
        exit();
    }

    // 🔴 Asegurar que 'completed' sea un booleano
    $completed = isset($data['completed']) ? filter_var($data['completed'], FILTER_VALIDATE_BOOLEAN) : true;

    // 🔵 Marcar como completada si la ID es válida
    if ($repository->markTaskAsCompleted((int)$data['id'], $user_id, $completed)) {
        echo json_encode(["success" => true, "message" => "Tarea actualizada."]);
    } else {
        http_response_code(500);
        echo json_encode(["success" => false, "message" => "No se pudo actualizar la tarea."]);
    }
    exit();
}

// 🟢 ELIMINAR TAREA (DELETE)
if ($method === 'DELETE') {
    parse_str(file_get_contents("php://input"), $data);

    // 🔴 Validar ID
    if (!isset($data['id']) || !is_numeric($data['id'])) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "ID de tarea inválido."]);
        exit();
    }

    // 🔵 Eliminar si la ID es válida
    if ($repository->deleteTask((int)$data['id'], $user_id)) {
        echo json_encode(["success" => true, "message" => "Tarea eliminada."]);
    } else {
        http_response_code(500);
        echo json_encode(["success" => false, "message" => "No se pudo eliminar la tarea."]);
    }
    exit();
}
?>
