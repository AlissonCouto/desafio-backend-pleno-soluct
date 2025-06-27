<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Services\TaskService;
use App\Classes\ApiResponseClass;


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
}
