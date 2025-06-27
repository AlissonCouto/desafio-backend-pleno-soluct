<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Services\TaskService;
use App\Classes\ApiResponseClass;
use App\Models\TaskHistory;
use App\Models\Task;

class TaskController extends Controller
{
    protected $service;

    public function __construct(TaskService $taskService)
    {
        $this->service = $taskService;
    }

    public function index(Request $request)
    {
        $userId = auth()->id();

        $result = $this->service->index($request->all(), $userId);

        return ApiResponseClass::success(
            ['tasks' => $result['tasks']],
            $result['message'],
            200
        );
    }

    public function store(StoreTaskRequest $request)
    {
        $userId = auth()->id();
        $result = $this->service->store($request->validated(), $userId);

        return ApiResponseClass::success(
            ['task' => $result['task']],
            $result['message'],
            201
        );
    }

    public function update(UpdateTaskRequest $request, $id)
    {
        $userId = auth()->id();

        $result = $this->service->update($id, $request->validated(), $userId);

        return ApiResponseClass::success(
            ['task' => $result['task']],
            $result['message'],
            $result['code'] ?? 200
        );
    }

    public function destroy($id)
    {
        $userId = auth()->id();

        $result = $this->service->destroy($id, $userId);

        return ApiResponseClass::success(
            null,
            $result['message'],
            $result['code']
        );
    }

    public function history($id)
    {
        $userId = auth()->id();

        $task = Task::where('id', $id)
            ->where('user_id', $userId)
            ->first();

        if (!$task) {
            return ApiResponseClass::error(
                'Tarefa não encontrada ou não pertence ao usuário',
                404
            );
        }

        $history = TaskHistory::where('task_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();

        return ApiResponseClass::success(
            ['history' => $history],
            'Histórico de alterações recuperado com sucesso',
            200
        );
    }
}
