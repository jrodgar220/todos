<?php
require_once '../dbconection/db.php';
require_once '../model/Task.php';

class TaskRepository {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getTasksByUserId(int $user_id): array {
        $stmt = $this->pdo->prepare("SELECT * FROM tasks WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(fn($task) => new Task($task['id'], $task['user_id'], $task['description'], (bool)$task['completed']), $tasks);
    }

    public function createTask(Task $task): bool {
        $stmt = $this->pdo->prepare("INSERT INTO tasks (user_id, description, completed) VALUES (?, ?, FALSE)");
        return $stmt->execute([$task->user_id, $task->description]);
    }

    public function markTaskAsCompleted(int $task_id, int $user_id): bool {
        $stmt = $this->pdo->prepare("UPDATE tasks SET completed = true WHERE id = ? AND user_id = ?");
        return $stmt->execute([$task_id, $user_id]);
    }

    public function deleteTask(int $task_id, int $user_id): bool {
        $stmt = $this->pdo->prepare("DELETE FROM tasks WHERE id = ? AND user_id = ?");
        return $stmt->execute([$task_id, $user_id]);
    }
}
?>
