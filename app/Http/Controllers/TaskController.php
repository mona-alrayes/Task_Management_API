<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\Request;

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
    public function index()
    {
        $tasks=Task::with('user')->paginate(10);
        return response()->json([
            'status' => 'success',
            'message' => 'Tasks retrieved successfully',
            'users' => [
                'info'=>TaskResource::collection($tasks->items()),
                'current_page' => $tasks->currentPage(),
                'last_page' => $tasks->lastPage(),
                'per_page' => $tasks->perPage(),
                'total' => $tasks->total(),
            ],
        ], 200); // OK
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
       $validatedTask = $request->validated();
        return response()->json([
            'status' => 'success',
            'message' => 'task created successfully',
            'book' => $task,
        ], 201); // Created
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Task retrieved successfully',
            'book' => TaskResource::make($fetchedData),
        ], 200); // OK
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
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
