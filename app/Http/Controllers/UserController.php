<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Http\Resources\UserResource;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
     /**
     * @var UserService
     * The service instance to handle user-related logic.
     */
    protected UserService $userService;

    /**
     * UserController constructor.
     *
     * @param UserService $userService
     * The service that handles user operations.
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
        $users=$this->userService->getUsers();
        return response()->json([
            'status' => 'success',
            'message' => 'Users retrieved successfully',
            'info'=>UserResource::collection($users->items()),
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'per_page' => $users->perPage(),
                'total' => $users->total(),
        ], 200); // OK
    }

    /**
     * Store a newly created resource in storage.
     * @throws ValidationException
     */
    public function store(StoreUserRequest $request): \Illuminate\Http\JsonResponse
    {
        $user = $this->userService->registerUser($request->validated());

        // Check if an error occurred in the user service
    if (isset($user['status']) && $user['status'] === 'error') {
        return response()->json([
            'status' => $user['status'],
            'message' => $user['message'],
            'errors' => $user['errors'],
        ], 500); // Internal Server Error
    }
        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'user' => new UserResource($user['user']),
            'token' => $user['token'],
        ], 201); // Created
    }

    /**
     * Display the specified resource.
     * @throws \Exception
     */
    public function show(string $user_id)
    {
        $user = $this->userService->getUserById($user_id);
        return response()->json([
            'status' => 'success',
            'message' => 'User retrieved successfully',
            'user' => new UserResource($user),
        ], 200); // OK
    }

    /**
     * Update the specified resource in storage.
     * @throws \Exception
     */
    public function update(UpdateUserRequest $request, string $id)
    {
        // $validatedData= $request->validated();
        $user = $this->userService->updateUser($request->validated() , $id );
       
        return response()->json([
            'status' => 'success',
            'message' => 'User updated successfully',
            'user' => new UserResource($user),
        ], 200); // OK
    }

    /**
     * Remove the specified resource from storage.
     * @throws \Exception
     */
    public function destroy(string $id)
    {
        $message=$this->userService->deleteUser($id);
        return response()->json([
           'status' => $message['status'],
           'message' => $message['message'],
        ], 200);
    }
}
