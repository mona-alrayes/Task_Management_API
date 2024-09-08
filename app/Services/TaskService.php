<?php

namespace App\Services;

use Exception;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class TaskService
 * 
 * This service handles operations related to tasks, including fetching, storing, updating, and deleting tasks.
 */
class TaskService
{
    /**
     * Retrieve all tasks with optional filters and sorting.
     * 
     * @param Request $request
     * The request object containing optional filters (author) and sorting options (sort_by, sort_order).
     * 
     * @return array
     * An array containing paginated task resources.
     */
    public function getAllTasks(Request $request): array
    {
        try {
            // Create a query builder instance for the Task model
            $tasks = Task::with(['users' => function ($query) {
                $query->select('id', 'user_id'); // Specify the columns you want
            }])->paginate(5);

            // // Apply filters based on request parameters
            // $query->when($request->author, function ($q, $author) {
            //     return $q->where('author', $author);
            // });
            // // Apply filter based on category name
            // $query->when($request->category_name, function ($q, $category) {
            //     return $q->whereHas('category', function ($q) use ($category) {
            //         $q->where('name', $category);
            //     });
            // });
            // // Filter tasks and brings only avaliable tasks
            // $query->when($request->has('available') && $request->available == 'true', function ($q) {
            //     $q->whereDoesntHave('borrowRecords', function ($q) {
            //         $q->whereNotNull('returned_at');
            //     });
            // });

            // // Apply sorting if specified
            // if ($request->sort_by) {
            //     $sortOrder = $request->sort_order ?? 'asc';
            //     $query->orderBy($request->sort_by, $sortOrder);
            // }

            // Paginate the results

            // Return the paginated tasks as an array
            return [
                'data' => $tasks->items(), // the items on the current page
                'current_page' => $tasks->currentPage(),
                'last_page' => $tasks->lastPage(),
                'per_page' => $tasks->perPage(),
                'total' => $tasks->total(),
            ];
        } catch (Exception $e) {
            throw new Exception('Failed to retrieve tasks: ' . $e->getMessage());
        }
    }

    /**
     * Store a new task.
     * 
     * @param array $data
     * An associative array containing 'title', 'author', 'published_at', and 'description'.
     * 
     * @return Task
     * The created task resource.
     * 
     * @throws \Exception
     * Throws an exception if the task creation fails.
     */
    public function storeTask(array $data): Task
    {
        try {
            $task = Task::create($data);

            if (!$task) {
                throw new Exception('Failed to create the task.');
            }

            return $task;
        } catch (Exception $e) {
            throw new Exception('Task creation failed: ' . $e->getMessage());
        }
    }

    /**
     * Retrieve a specific task by its ID.
     * 
     * @param int $id
     * The ID of the task to retrieve.
     * 
     * @return Task
     * The task resource.
     * 
     * @throws \Exception
     * Throws an exception if the task is not found.
     */
    public function showTask(int $id): Task
    {
        try {
            $task = Task::findOrFail($id);
            return $task;
        } catch (ModelNotFoundException $e) {
            throw new Exception('Task not found: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new Exception('Failed to retrieve task: ' . $e->getMessage());
        }
    }

    /**
     * Update an existing task.
     * 
     * @param array $data
     * The data array containing the fields to update.
     * @param string $id
     * The ID of the task to update.
     * 
     * @return Task
     * The updated task resource.
     * 
     * @throws \Exception
     * Throws an exception if the task is not found or update fails.
     */
    public function updateTask(array $data, string $id): Task
    {
        try {
            $task = Task::findOrFail($id);

            $task->update(array_filter($data));

            return $task;
        } catch (ModelNotFoundException $e) {
            throw new Exception('Task not found: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new Exception('Failed to update task: ' . $e->getMessage());
        }
    }

    /**
     * Delete a task by its ID.
     * 
     * @param string $id
     * The ID of the task to delete.
     * 
     * @return string
     * A message confirming the deletion.
     * 
     * @throws \Exception
     * Throws an exception if the task is not found.
     */
    public function deleteTask(string $id): string
    {
        try {
            $task = Task::findOrFail($id);

            $task->delete();

            return "Task deleted successfully.";
        } catch (ModelNotFoundException $e) {
            throw new Exception('Task not found: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new Exception('Failed to delete task: ' . $e->getMessage());
        }
    }
}