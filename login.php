<?php
include 'db.php'; // Conexión a la base de datos
include 'headers.php';
$data = json_decode(file_get_contents("php://input"), true);
$username = $data['username'];
$password = $data['password'];


// Buscar usuario en la BD
$stmt = $pdo->prepare("SELECT id, password_hash FROM users WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user['password_hash'])) {
    $token = md5(uniqid($username, true)); // Generar token único
    $user_id = $user['id'];

    // Guardar el token en la BD
    $stmt = $pdo->prepare("UPDATE users SET token = ? WHERE id = ?");
    $stmt->execute([$token, $user_id]);

    echo json_encode(["success" => true, "token" => $token]);
} else {
    http_response_code(401);
    echo json_encode(["success" => false, "message" => "Credenciales incorrectas"]);
}
?>
