<?php

namespace App\Services;

use App\Models\Task;

class TaskService
{
    public function store(array $data, int $userId): array
    {
        $task = Task::create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'status' => $data['status'],
            'due_date' => $data['due_date'] ?? null,
            'user_id' => $userId,
        ]);

        return [
            'ok' => true,
            'message' => 'Tarefa criada com sucesso',
            'task' => $task,
        ];
    }
}
