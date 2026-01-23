<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Http\Requests\ProfileRequest;
use App\Http\Resources\ProfileResources;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $profiles = Profile::with(['college', 'department', 'course'])->paginate(10);

            return ProfileResources::collection($profiles);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while fetching profiles.'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProfileRequest $request)
    {
        try {
            $data = $request->validated();
            $profile = Profile::create($data);

            $profile->load(['college', 'department', 'course']);

        return new ProfileResources($profile);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while creating profile.'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try{
            $profile = Profile::with(['college', 'department', 'course'])->findOrFail($id);

            return new ProfileResources($profile);
            
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while fetching profile.'], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProfileRequest $request, string $id)
    {
        try{
            $profile = Profile::findOrFail($id);

            $profile->update($request->validated());

            $profile->load(['college', 'department', 'course']);

            return new ProfileResources($profile);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while updating profile.'], 500);
        }
    }


    public function destroy(string $id)
    {
        try{
        $profile = Profile::findOrFail($id);
        $profile->delete();

            return response()->json(['message' => 'Profile deleted successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while deleting profile.'], 500);
        }
    }
}