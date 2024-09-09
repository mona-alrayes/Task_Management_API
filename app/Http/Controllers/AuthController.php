<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * AuthController constructor.
     *
     * Applies middleware to protect routes except for login and register.
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * Handle user login.
     *
     * Validates the request and attempts to authenticate the user using
     * the provided email and password. If authentication is successful,
     * returns the user's information along with a JWT token.
     *
     * @param Request $request The HTTP request object containing login credentials.
     * @return \Illuminate\Http\JsonResponse JSON response containing user info and token.
     */
    public function login(Request $request)
    {
        // Validate input fields
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        // Attempt to authenticate with credentials
        $credentials = $request->only('email', 'password');
        $token = Auth::attempt($credentials);

        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        // Get authenticated user
        $user = Auth::user();
        return response()->json([
            'status' => 'success',
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

    /**
     * Handle user registration.
     *
     * Validates the input data and creates a new user. Automatically logs
     * the user in and returns user information along with a JWT token.
     *
     * @param Request $request The HTTP request object containing registration data.
     * @return \Illuminate\Http\JsonResponse JSON response with created user info and token.
     */
    public function register(Request $request)
    {
        // Validate input fields
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        // Create the new user
        $user= New User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->save();
        
        // Log the user in and generate a token
        $token = Auth::login($user);

        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'user' => $user,
            'role' => $user->role, // Assuming role exists on User
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

    /**
     * Log the user out.
     *
     * Invalidate the user's token, effectively logging them out.
     *
     * @return \Illuminate\Http\JsonResponse JSON response confirming logout success.
     */
    public function logout()
    {
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }

    /**
     * Refresh the JWT token.
     *
     * Generates a new token for the authenticated user and returns
     * the updated token along with the user's information.
     *
     * @return \Illuminate\Http\JsonResponse JSON response with user info and new token.
     */
    public function refresh()
    {
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }
}
