<?php

namespace App\Services;

use App\Models\Task;

class TaskService
{

    public function index(array $filters, int $userId): array
    {
        $query = Task::where('user_id', $userId);

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['title'])) {
            $query->where('title', 'like', '%' . $filters['title'] . '%');
        }

        if (!empty($filters['created_from']) && !empty($filters['created_to'])) {
            $query->whereBetween('created_at', [$filters['created_from'], $filters['created_to']]);
        }

        if (!empty($filters['due_from']) && !empty($filters['due_to'])) {
            $query->whereBetween('due_date', [$filters['due_from'], $filters['due_to']]);
        }

        $orderBy = $filters['order_by'] ?? 'created_at';
        $direction = $filters['direction'] ?? 'desc';
        $query->orderBy($orderBy, $direction);

        $perPage = $filters['per_page'] ?? 10;
        $tasks = $query->paginate($perPage);

        return [
            'ok' => true,
            'message' => 'Tarefas listadas com sucesso',
            'tasks' => $tasks
        ];
    }

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
