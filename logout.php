<?php
header('Access-Control-Allow-Origin: *'); // Permite solicitudes de cualquier origen
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE'); // Métodos permitidos
header('Access-Control-Allow-Headers: Content-Type'); // Cabeceras permitidas
header("Content-Type: application/json");

// Si es una solicitud preflight (OPTIONS), respondemos correctamente
/*Una solicitud preflight es un tipo de solicitud HTTP que se realiza automáticamente 
como parte de las políticas de Cross-Origin Resource Sharing (CORS) cuando un navegador 
intenta hacer una solicitud HTTP de un dominio (origen) diferente al dominio del servidor 
que la recibe. Este tipo de solicitud es realizada por el navegador antes de que la 
solicitud principal (como una petición GET, POST, PUT, etc.) se realice efectivamente.*/
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit;
}
//session_start();
//session_destroy(); // Elimina todos los datos de sesión
include 'db.php';

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

?>
