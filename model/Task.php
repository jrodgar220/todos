<?php
class Task {
    public ?int $id;
    public int $user_id;
    public string $description;
    public bool $completed;

    public function __construct(?int $id, int $user_id, string $description, bool $completed = false) {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->description = $description;
        $this->completed = $completed;
    }

    public function toArray(): array {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'description' => $this->description,
            'completed' => $this->completed
        ];
    }
}
?>
