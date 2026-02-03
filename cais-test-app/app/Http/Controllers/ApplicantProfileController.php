<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApplicantProfileRequest;
use App\Http\Resources\ApplicantProfileResource;
use App\Models\ApplicantProfile;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ApplicantProfileController extends Controller
{
    /**
     * Display a listing of all applicant profiles.
     */
    public function index(): JsonResponse
    {
        $profiles = ApplicantProfile::with('user')->get();
        
        return response()->json([
            'success' => true,
            'data' => ApplicantProfileResource::collection($profiles),
        ]);
    }

    /**
     * Display the specified applicant profile by user_id.
     */
    public function show(string $userId): JsonResponse
    {
        $profile = ApplicantProfile::where('user_id', $userId)->first();
        
        if (!$profile) {
            return response()->json([
                'success' => false,
                'message' => 'Profile not found for this user.',
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => new ApplicantProfileResource($profile),
        ]);
    }

    /**
     * Store a newly created applicant profile.
     */
    public function store(ApplicantProfileRequest $request): JsonResponse
    {
        $validated = $request->validated();
        
        // Check if profile already exists for this user
        $existingProfile = ApplicantProfile::where('user_id', $validated['user_id'])->first();
        if ($existingProfile) {
            return response()->json([
                'success' => false,
                'message' => 'Profile already exists for this user. Use update instead.',
            ], 409);
        }
        
        $profile = ApplicantProfile::create($validated);
        
        return response()->json([
            'success' => true,
            'message' => 'Profile created successfully.',
            'data' => new ApplicantProfileResource($profile),
        ], 201);
    }

    /**
     * Update the specified applicant profile by user_id.
     */
    public function update(ApplicantProfileRequest $request, string $userId): JsonResponse
    {
        $profile = ApplicantProfile::where('user_id', $userId)->first();
        
        if (!$profile) {
            return response()->json([
                'success' => false,
                'message' => 'Profile not found for this user.',
            ], 404);
        }
        
        $validated = $request->validated();
        $validated['is_updated'] = 1; // Mark as updated
        
        $profile->update($validated);
        
        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully.',
            'data' => new ApplicantProfileResource($profile->fresh()),
        ]);
    }

    /**
     * Remove the specified applicant profile by user_id.
     */
    public function destroy(string $userId): JsonResponse
    {
        $profile = ApplicantProfile::where('user_id', $userId)->first();
        
        if (!$profile) {
            return response()->json([
                'success' => false,
                'message' => 'Profile not found for this user.',
            ], 404);
        }
        
        $profile->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Profile deleted successfully.',
        ]);
    }

    /**
     * Get profile by applicant_id instead of user_id.
     */
    public function getByApplicantId(string $applicantId): JsonResponse
    {
        $profile = ApplicantProfile::find($applicantId);
        
        if (!$profile) {
            return response()->json([
                'success' => false,
                'message' => 'Profile not found.',
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => new ApplicantProfileResource($profile),
        ]);
    }
}
