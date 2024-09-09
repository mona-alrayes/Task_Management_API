<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TaskService;
use App\Http\Resources\TaskResource;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateStatusRequest;
use App\Http\Requests\UpdateTaskRequest;

class TaskController extends Controller
{
    protected $TaskService;

    public function __construct(TaskService $TaskService)
    {
        $this->TaskService = $TaskService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(request $request)
    {
        $tasks=$this->TaskService->getAllTasks($request);
        return response()->json([
            'status' => 'success',
            'message' => 'Tasks retrieved successfully',
            'users' => [
                'info' => TaskResource::collection($tasks['data']), // Convert the resource collection to array
                'current_page' => $tasks['current_page'], 
                'last_page' => $tasks['last_page'], 
                'per_page' => $tasks['per_page'], 
                'total' => $tasks['total'], 
            ],
        ], 200); // OK
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        $task = $this->TaskService->storeTask($request->validated());
        return response()->json([
            'status' => 'success',
            'message' => 'task created successfully',
            'task' => $task,
        ], 201); // Created
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $fetchedData = $this->TaskService->showTask($id);
        return response()->json([
            'status' => 'success',
            'message' => 'Task retrieved successfully',
            'Task' => TaskResource::make($fetchedData),
        ], 200); // OK
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, string $id)
    {
        $task = $this->TaskService->updateTask($request->validated(), $id);
        return response()->json([
            'status' => 'success',
            'message' => 'Book updated successfully',
            'book' => TaskResource::make($task),
        ], 200); // OK
    }

    public function updateByAssignedUser(UpdateStatusRequest $request, string $id)
    {
        $task = $this->TaskService->updateStatus($request->validated(), $id);
        return response()->json([
            'status' => 'success',
            'message' => 'Book updated successfully',
            'book' => TaskResource::make($task),
        ], 200); // OK
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $message = $this->TaskService->deleteTask($id);

        return response()->json([
            'status' => 'success',
            'message' => $message,
        ], 200); // OK
    }
}
