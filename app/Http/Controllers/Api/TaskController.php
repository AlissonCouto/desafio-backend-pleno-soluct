<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Services\TaskService;
use App\Classes\ApiResponseClass;

class TaskController extends Controller
{
    protected $service;

    public function __construct(TaskService $taskService)
    {
        $this->service = $taskService;
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
}
