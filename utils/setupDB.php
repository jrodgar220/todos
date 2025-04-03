<?php
require_once '../dbconection/db.php';

try {
    // Crear tabla de usuarios
    $sqlUsers = "CREATE TABLE IF NOT EXISTS users (
        id SERIAL PRIMARY KEY,
        username VARCHAR(50) UNIQUE NOT NULL,
        password_hash TEXT NOT NULL,
        token TEXT DEFAULT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";

    // Crear tabla de tareas
    $sqlTasks = "CREATE TABLE IF NOT EXISTS tasks (
        id SERIAL PRIMARY KEY,
        user_id INT NOT NULL,
        description TEXT NOT NULL,
        completed BOOLEAN DEFAULT FALSE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )";

    // Ejecutar las consultas
    $pdo->exec($sqlUsers);
    $pdo->exec($sqlTasks);

    echo "✅ Tablas creadas correctamente.";
} catch (PDOException $e) {
    die("❌ Error al crear tablas: " . $e->getMessage());
}


$username = 'admin';
$password = 'admin'; // Contraseña en texto plano
$password_hash = password_hash($password, PASSWORD_BCRYPT); // Encriptar contraseña

$stmt = $pdo->prepare("INSERT INTO users (username, password_hash) VALUES (?, ?)");
$stmt->execute([$username, $password_hash]);

echo "Usuario creado correctamente.";
?>
