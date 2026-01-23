<?php

namespace App\Http\Controllers;

use App\Models\User; // Import the User Model
use App\Http\Resources\UserResources; // Import the Resource
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     * GET /api/users
     */
    public function index()
    {
        // Fetch users and "eager load" the profile to avoid performance issues
        $users = User::with('profile')->paginate(10);

        return UserResources::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // (Logic for creating users will go here later)
    }

    /**
     * Display the specified resource.
     * GET /api/users/{id}
     */
    public function show(string $id)
    {
        // Find user by ID or fail (404), with profile data attached
        $user = User::with('profile')->findOrFail($id);

        return new UserResources($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // (Logic for updating users will go here later)
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // (Logic for deleting users will go here later)
    }
}


/**
 * 

 */