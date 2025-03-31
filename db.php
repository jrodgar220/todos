<?php
/*$host = 'localhost';
$dbname = 'todo_app';
$username = 'root'; // Cambia esto si tienes otro usuario
$password = ''; // Cambia si tienes contraseña

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}*/


// URL de conexión que te da Render


$url = "postgresql://todos_1yhh_user:946w8qnw9vVLrvsIgpPZvdWmqIcIV5rQ@dpg-cvlcp7jipnbc73di699g-a.frankfurt-postgres.render.com/todos_1yhh";
// Extraer los datos usando parse_url()
$parsedUrl = parse_url($url);

$host = $parsedUrl['host']; // dpg-cvlcp7jipnbc73di699g-a
$port = $parsedUrl['port'] ?? "5432"; // Asegurar el puerto 5432 si no aparece
$dbname = ltrim($parsedUrl['path'], '/'); // todos_1yhh
$username = $parsedUrl['user']; // todos_1yhh_user
$password = $parsedUrl['pass']; // 946w8qnw9vVLrvsIgpPZvdWmqIcIV5rQ

// Crear conexión PDO
$dsn = "pgsql:host=$host;port=$port;dbname=$dbname";

try {
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
    //echo "✅ Conexión exitosa a PostgreSQL.";
} catch (PDOException $e) {
    die("❌ Error de conexión: " . $e->getMessage());
}
?>



