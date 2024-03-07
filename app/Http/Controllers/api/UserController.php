<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Fetch all users
    public function index()
    {
        $users = User::orderBy('id', 'DESC')->get();
        return response()->json($users);
    }

    // Show a specific user by ID
    public function show($id)
    {

        try {
            $user = User::findOrFail($id);
            return response()->json($user);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json(['error' => 'User not found.'], 404);
        }
    }

    // Update user details
    public function update(Request $request, $id)
    {

        try {
            $user = User::findOrFail($id);
            $user->update($request->only(['name', 'email']));
            return response()->json($user);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json(['error' => 'Failed to update user.'], 500);
        }
    }

    // Create a new user
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
        ]);

        try {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make(1234),
            ]);
            return response()->json($user, 201);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json(['error' => 'Failed to create user.'], 500);
        }
    }

    // Delete a user
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            return response()->json($user);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json(['error' => 'Failed to delete user.'], 500);
        }
    }
}
