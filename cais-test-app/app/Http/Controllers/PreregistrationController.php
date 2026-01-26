<?php

namespace App\Http\Controllers;

use App\Models\Preregistration;
use App\Models\Enrollment;
use App\Http\Requests\PreregistrationRequest;
use App\Http\Resources\PreregistrationResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PreregistrationController extends Controller
{
    /**
     * Display a listing of preregistrations
     */
    public function index(Request $request)
    {
        try {
            $query = Preregistration::query();

            // Filter by user_id if provided
            if ($request->has('user_id')) {
                $query->where('user_id', $request->user_id);
            }

            // Filter by semester_id if provided
            if ($request->has('semester_id')) {
                $query->where('semester_id', $request->semester_id);
            }

            // Filter by status if provided
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            $preregistrations = $query->orderBy('created_at', 'desc')->get();

            return response()->json([
                'success' => true,
                'data' => PreregistrationResource::collection($preregistrations),
                'count' => $preregistrations->count()
            ], 200);

        } catch (\Exception $e) {
            \Log::error('Error fetching preregistrations: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching preregistrations',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created preregistration
     */
    public function store(PreregistrationRequest $request)
    {
        try {
            $validated = $request->validated();

            // Check if already preregistered
            $existing = Preregistration::where('user_id', $validated['user_id'])
                ->where('semester_id', $validated['semester_id'])
                ->where('schedId', $validated['schedId'])
                ->first();

            if ($existing) {
                return response()->json([
                    'success' => false,
                    'message' => 'Course already preregistered'
                ], 409);
            }

            $preregistration = Preregistration::create($validated);

            \Log::info("Preregistration created: prereg_id={$preregistration->prereg_id}");

            return response()->json([
                'success' => true,
                'message' => 'Course preregistered successfully',
                'data' => new PreregistrationResource($preregistration)
            ], 201);

        } catch (\Exception $e) {
            \Log::error('Error creating preregistration: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error creating preregistration',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified preregistration
     */
    public function show(string $id)
    {
        try {
            $preregistration = Preregistration::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => new PreregistrationResource($preregistration)
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Preregistration not found'
            ], 404);
        }
    }

    /**
     * Update the specified preregistration
     */
    public function update(PreregistrationRequest $request, string $id)
    {
        try {
            $preregistration = Preregistration::findOrFail($id);
            $preregistration->update($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Preregistration updated successfully',
                'data' => new PreregistrationResource($preregistration)
            ], 200);

        } catch (\Exception $e) {
            \Log::error('Error updating preregistration: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating preregistration',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified preregistration
     */
    public function destroy(string $id)
    {
        try {
            $preregistration = Preregistration::findOrFail($id);
            
            // Only allow deletion if status is 'pending'
            if ($preregistration->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete a preregistration that has already been enrolled'
                ], 400);
            }

            $preregistration->delete();

            return response()->json([
                'success' => true,
                'message' => 'Preregistration removed successfully'
            ], 200);

        } catch (\Exception $e) {
            \Log::error('Error deleting preregistration: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error deleting preregistration',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get preregistered courses for a specific user (pending status)
     */
    public function getUserPreregistrations(Request $request)
    {
        try {
            $userId = $request->user_id;

            if (!$userId) {
                return response()->json([
                    'success' => false,
                    'message' => 'User ID is required'
                ], 400);
            }

            $preregistrations = Preregistration::where('user_id', $userId)
                ->where('status', 'pending')
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => PreregistrationResource::collection($preregistrations),
                'count' => $preregistrations->count()
            ], 200);

        } catch (\Exception $e) {
            \Log::error('Error fetching user preregistrations: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching preregistrations',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Enroll a preregistered course (moves to enrollment table)
     */
    public function enrollCourse(Request $request)
    {
        try {
            $preregId = $request->prereg_id;

            if (!$preregId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Preregistration ID is required'
                ], 400);
            }

            $preregistration = Preregistration::findOrFail($preregId);

            // Check if already enrolled
            if ($preregistration->status === 'enrolled') {
                return response()->json([
                    'success' => false,
                    'message' => 'Course already enrolled'
                ], 409);
            }

            DB::beginTransaction();

            // Create enrollment record with pending approval status
            $enrollment = Enrollment::create([
                'user_id' => $preregistration->user_id,
                'semester_id' => $preregistration->semester_id,
                'course_id' => $preregistration->course_id,
                'section' => $preregistration->section,
                'prereg_id' => $preregistration->prereg_id,
                'schedId' => $preregistration->schedId,
                'approval_status' => 'pending',
                'registration_only_tag' => false
            ]);

            // Update preregistration status to enrolled
            $preregistration->update(['status' => 'enrolled']);

            DB::commit();

            \Log::info("Enrollment created from prereg: prereg_id={$preregId}, enrollment_id={$enrollment->enrollment_id}");

            return response()->json([
                'success' => true,
                'message' => 'Course enrolled successfully. Waiting for RA approval.',
                'data' => [
                    'enrollment_id' => $enrollment->enrollment_id,
                    'prereg_id' => $preregId,
                    'approval_status' => 'pending'
                ]
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error enrolling course: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error enrolling course',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk enroll all pending preregistrations for a user
     */
    public function enrollAll(Request $request)
    {
        try {
            $userId = $request->user_id;

            if (!$userId) {
                return response()->json([
                    'success' => false,
                    'message' => 'User ID is required'
                ], 400);
            }

            $pendingPreregs = Preregistration::where('user_id', $userId)
                ->where('status', 'pending')
                ->get();

            if ($pendingPreregs->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No pending preregistrations to enroll'
                ], 404);
            }

            DB::beginTransaction();

            $enrolledCount = 0;
            $enrollments = [];

            foreach ($pendingPreregs as $prereg) {
                $enrollment = Enrollment::create([
                    'user_id' => $prereg->user_id,
                    'semester_id' => $prereg->semester_id,
                    'course_id' => $prereg->course_id,
                    'section' => $prereg->section,
                    'prereg_id' => $prereg->prereg_id,
                    'schedId' => $prereg->schedId,
                    'approval_status' => 'pending',
                    'registration_only_tag' => false
                ]);

                $prereg->update(['status' => 'enrolled']);
                $enrollments[] = $enrollment->enrollment_id;
                $enrolledCount++;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "{$enrolledCount} courses enrolled successfully. Waiting for RA approval.",
                'data' => [
                    'enrolled_count' => $enrolledCount,
                    'enrollment_ids' => $enrollments
                ]
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error bulk enrolling courses: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error enrolling courses',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
