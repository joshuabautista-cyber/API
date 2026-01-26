<?php

namespace App\Http\Controllers;

use App\Http\Requests\EnrollmentRequest;
use Illuminate\Http\Request;
use App\Http\Resources\EnrollmentResources;
use App\Models\Enrollment;
use Illuminate\Support\Facades\DB;

class EnrollmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = Enrollment::with(['user', 'course', 'semester', 'classSchedule', 'preregistration']);

            // Filter by user_id if provided
            if ($request->has('user_id')) {
                $query->where('user_id', $request->user_id);
            }

            // Filter by semester_id if provided
            if ($request->has('semester_id')) {
                $query->where('semester_id', $request->semester_id);
            }

            // Filter by approval_status if provided
            if ($request->has('approval_status')) {
                $query->where('approval_status', $request->approval_status);
            }

            $enrollments = $query->orderBy('created_at', 'desc')->get();

            // Format response with class schedule details
            $formattedEnrollments = $enrollments->map(function($enrollment) {
                $classSched = $enrollment->classSchedule;
                $prereg = $enrollment->preregistration;
                
                return [
                    'enrollment_id' => $enrollment->enrollment_id,
                    'user_id' => $enrollment->user_id,
                    'semester_id' => $enrollment->semester_id,
                    'course_id' => $enrollment->course_id,
                    'section' => $enrollment->section,
                    'prereg_id' => $enrollment->prereg_id,
                    'schedId' => $enrollment->schedId,
                    'subject_code' => $classSched?->subject_code ?? $prereg?->subject_code ?? 'N/A',
                    'subject_title' => $classSched?->subject_title ?? $prereg?->subject_title ?? 'N/A',
                    'units' => $classSched?->units ?? $prereg?->units ?? 0,
                    'approval_status' => $enrollment->approval_status,
                    'remarks' => $enrollment->remarks,
                    'approved_by' => $enrollment->approved_by,
                    'approved_at' => $enrollment->approved_at,
                    'created_at' => $enrollment->created_at,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $formattedEnrollments,
                'count' => $formattedEnrollments->count()
            ], 200);
            
        } catch (\Exception $e) {
            \Log::error('Failed to retrieve enrollments: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve enrollments', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Get preregistered subjects for a specific user (registration_only_tag = true)
     */
    public function getPreregisteredSubjects(Request $request)
    {
        try {
            $user_id = $request->user_id;

            if (!$user_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'User ID is required'
                ], 400);
            }

            \Log::info("Fetching preregistered enrollments for user_id: {$user_id}");

            // Get all preregistered enrollments for this user (registration_only_tag = true)
            // Join with class schedules to get subject details
            $enrollments = DB::table('tbl_enrollments as e')
                ->leftJoin('tbl_class_schedules as cs', function($join) {
                    $join->on('e.semester_id', '=', 'cs.semester_id')
                         ->on('e.course_id', '=', 'cs.course_id')
                         ->on('e.section', '=', 'cs.section');
                })
                ->where('e.user_id', $user_id)
                ->where('e.registration_only_tag', true)
                ->select(
                    'e.enrollment_id',
                    'e.user_id',
                    'e.semester_id',
                    'e.course_id',
                    'e.section',
                    'e.registration_only_tag',
                    'e.created_at',
                    DB::raw('MIN(cs.schedId) as schedId'),
                    DB::raw('MIN(cs.subject_code) as subject_code'),
                    DB::raw('MIN(cs.subject_title) as subject_title'),
                    DB::raw('MIN(cs.units) as units'),
                    DB::raw('MIN(cs.cat_no) as cat_no')
                )
                ->groupBy('e.enrollment_id', 'e.user_id', 'e.semester_id', 'e.course_id', 'e.section', 'e.registration_only_tag', 'e.created_at')
                ->get();

            // Format the response
            $formattedSubjects = $enrollments->map(function($enrollment) {
                return [
                    'enrollment_id' => $enrollment->enrollment_id,
                    'schedId' => $enrollment->schedId,
                    'user_id' => $enrollment->user_id,
                    'semester_id' => $enrollment->semester_id,
                    'course_id' => $enrollment->course_id,
                    'subject_code' => $enrollment->subject_code ?? 'N/A',
                    'subject_title' => $enrollment->subject_title ?? 'N/A',
                    'course_name' => $enrollment->subject_title ?? 'N/A',
                    'units' => $enrollment->units ?? 0,
                    'section' => $enrollment->section,
                    'registration_only_tag' => $enrollment->registration_only_tag,
                    'created_at' => $enrollment->created_at,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $formattedSubjects
            ], 200);

        } catch (\Exception $e) {
            \Log::error('Error fetching preregistered subjects: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching preregistered subjects',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     * Create enrollment for preregistration (registration_only_tag = true)
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|integer',
                'semester_id' => 'required|integer',
                'course_id' => 'required|integer',
                'section' => 'required|string',
            ]);

            \Log::info("Creating enrollment for preregistration: " . json_encode($validated));

            // Create enrollment with registration_only_tag = true for preregistration
            $enrollment = Enrollment::create([
                'user_id' => $validated['user_id'],
                'semester_id' => $validated['semester_id'],
                'course_id' => $validated['course_id'],
                'section' => $validated['section'],
                'registration_only_tag' => true  // Mark as preregistration only
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Course preregistered successfully',
                'data' => $enrollment
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error creating enrollment: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error creating enrollment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try{
            $enrollment = Enrollment::with(['user', 'course', 'semester'])->findOrFail($id);

            return new EnrollmentResources($enrollment);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Enrollment not found', 'message' => $e->getMessage()], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $enrollment = Enrollment::findOrFail($id);

            // Only allow cancellation if approval_status is 'pending'
            if ($enrollment->approval_status === 'approved') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot cancel an approved enrollment'
                ], 400);
            }

            // If this enrollment came from preregistration, update the preregistration status back to pending
            if ($enrollment->prereg_id) {
                $preregistration = \App\Models\Preregistration::find($enrollment->prereg_id);
                if ($preregistration) {
                    $preregistration->update(['status' => 'pending']);
                }
            }

            $enrollment->delete();

            return response()->json([
                'success' => true,
                'message' => 'Enrollment cancelled successfully'
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Enrollment not found'
            ], 404);
        } catch (\Exception $e) {
            \Log::error('Error cancelling enrollment: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error cancelling enrollment',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
