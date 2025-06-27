<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

use App\Models\Task;
use App\Models\TaskHistory;

class TaskService
{

    public function index(array $filters, int $userId): array
    {
        $cacheKey = 'tasks_index_user_' . $userId . '_' . md5(json_encode($filters));

        $tasks = Cache::remember($cacheKey, now()->addMinutes(1), function () use ($filters, $userId) {
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

            return $query->paginate($perPage);
        });

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

    public function update(int $id, array $data, int $userId): array
    {
        $task = Task::where('id', $id)
            ->where('user_id', $userId)
            ->first();

        if (!$task) {
            return [
                'ok' => false,
                'message' => 'Tarefa não encontrada ou não pertence ao usuário',
                'code' => 404
            ];
        }

        // Registrando o histórico
        foreach ($data as $field => $newValue) {
            $oldValue = $task->$field;

            if ($oldValue != $newValue) {
                TaskHistory::create([
                    'task_id' => $task->id,
                    'user_id' => $userId,
                    'field_changed' => $field,
                    'old_value' => $oldValue,
                    'new_value' => $newValue,
                ]);
            }
        }

        $task->update($data);

        return [
            'ok' => true,
            'message' => 'Tarefa atualizada com sucesso',
            'task' => $task
        ];
    }

    public function destroy(int $id, int $userId): array
    {
        $task = Task::where('id', $id)
            ->where('user_id', $userId)
            ->first();

        if (!$task) {
            return [
                'ok' => false,
                'message' => 'Tarefa não encontrada ou não pertence ao usuário',
                'code' => 404
            ];
        }

        $task->delete();

        return [
            'ok' => true,
            'message' => 'Tarefa deletada com sucesso',
            'code' => 200
        ];
    }
}
