<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegistrationRequest;
use Illuminate\Http\Request;
use App\Models\Registration;
use App\Models\Class_Sched;
use App\Models\Semester;
use App\Models\Enrollment;
use App\Http\Resources\RegistrationResources;
use Illuminate\Support\Facades\DB;

class RegistrationController extends Controller
{
    /**
     * Display a listing of the resource.
     * RETRIEVE ALL
     */
    public function index()
    {
        $registrations = Registration::with(['user', 'classSchedule', 'enrollment'])->get();
        return RegistrationResources::collection($registrations);
    }

    /**
     * Get the latest/active semester for preregistration
     */
    public function getActiveSemester()
    {
        try {
            $semester = Semester::where('semester_status', 'active')
                            ->orderBy('semester_id', 'desc')
                            ->first();
            
            if (!$semester) {
                $semester = Semester::orderBy('semester_id', 'desc')->first();
            }

            \Log::info("Active semester found: " . ($semester ? $semester->semester_id : 'none'));
            
            return response()->json([
                'success' => true,
                'data' => $semester
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Error fetching semester: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching semester',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all subjects available (across all semesters) - no semester filter
     */
    public function getAllSubjectsOffered(Request $request)
    {
        try {
            $search = $request->search;
            $perPage = $request->per_page ?? 10;

            \Log::info("Fetching ALL subjects offered (no semester filter), search: {$search}");

            $query = DB::table('tbl_class_schedules');

            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('subject_code', 'LIKE', "%{$search}%")
                    ->orWhere('cat_no', 'LIKE', "%{$search}%")
                    ->orWhere('subject_title', 'LIKE', "%{$search}%");
                });
            }

            // Order by subject code
            $query->orderBy('subject_code', 'asc');

            // Get total count before pagination
            $totalCount = $query->count();
            \Log::info("Total subjects found: {$totalCount}");

            // Paginate results
            $offset = ($request->page - 1) * $perPage;
            $subjects = $query->select(
                'schedId',
                'semester_id',
                'course_id',
                'subject_code',
                'cat_no',
                'subject_title',
                'units',
                'section',
                'slot_no',
                'class_type',
                'lab_type',
                'dept_id'
            )
                            ->offset($offset)
                            ->limit($perPage)
                            ->get();

            // Calculate pagination meta
            $currentPage = (int) ($request->page ?? 1);
            $lastPage = ceil($totalCount / $perPage);

            return response()->json([
                'success' => true,
                'data' => $subjects,
                'meta' => [
                    'current_page' => $currentPage,
                    'last_page' => $lastPage,
                    'per_page' => $perPage,
                    'total' => $totalCount,
                    'from' => $totalCount > 0 ? $offset + 1 : null,
                    'to' => $totalCount > 0 ? min($offset + $perPage, $totalCount) : null,
                ]
            ], 200);

        } catch (\Exception $e) {
            \Log::error('Error fetching all subjects offered: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching subjects',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get subjects offered for a semester
     */
    public function getSubjectsOffered(Request $request)
    {
    try {
        $semester_id = $request->semester_id;
        $search = $request->search;
        $perPage = $request->per_page ?? 10;

        \Log::info("Fetching subjects offered with semester_id: {$semester_id}, search: {$search}");

        // Query class schedules
        $query = DB::table('tbl_class_schedules');

            // Filter by semester if provided
            if ($semester_id) {
                $query->where('semester_id', $semester_id);
            }

            // Apply search filter
            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('subject_code', 'LIKE', "%{$search}%")
                    ->orWhere('cat_no', 'LIKE', "%{$search}%")
                    ->orWhere('subject_title', 'LIKE', "%{$search}%");
                });
            }

            // Order by subject code
            $query->orderBy('subject_code', 'asc');

            // Get total count before pagination
            $totalCount = $query->count();
            \Log::info("Total subjects found: {$totalCount}");

            // Paginate results
            $offset = ($request->page - 1) * $perPage;
            $subjects = $query->select(
                'schedId',
                'semester_id',
                'course_id',
                'subject_code',
                'cat_no',
                'subject_title',
                'units',
                'section',
                'slot_no',
                'class_type',
                'lab_type',
                'dept_id'
            )
                            ->offset($offset)
                            ->limit($perPage)
                            ->get();

            // Calculate pagination meta
            $currentPage = (int) ($request->page ?? 1);
            $lastPage = ceil($totalCount / $perPage);

            return response()->json([
                'success' => true,
                'data' => $subjects,
                'meta' => [
                    'current_page' => $currentPage,
                    'last_page' => $lastPage,
                    'per_page' => $perPage,
                    'total' => $totalCount,
                    'from' => $totalCount > 0 ? $offset + 1 : null,
                    'to' => $totalCount > 0 ? min($offset + $perPage, $totalCount) : null,
                ]
            ], 200);

        } catch (\Exception $e) {
            \Log::error('Error fetching subjects offered: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching subjects',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get preregistered subjects for a user in a semester
     */
    public function getPreregisteredSubjects(Request $request)
    {
        try {
            $user_id = $request->user_id;
            $semester_id = $request->semester_id;

            if (!$user_id || !$semester_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'User ID and Semester ID are required'
                ], 400);
            }

            // Get enrollment for this semester
            $enrollment = Enrollment::where('user_id', $user_id)
                ->where('semester_id', $semester_id)
                ->first();

            if (!$enrollment) {
                return response()->json([
                    'success' => true,
                    'data' => [],
                    'message' => 'No enrollment found for this semester'
                ], 200);
            }

            // Get preregistered subjects
            $preregistered = Registration::where('user_id', $user_id)
                ->where('enrollment_id', $enrollment->enrollment_id)
                ->with('classSchedule')
                ->get();

            // Format the response
            $formattedSubjects = $preregistered->map(function($reg) {
                return [
                    'registration_id' => $reg->registration_id,
                    'schedId' => $reg->schedId,
                    'subject_code' => $reg->classSchedule->subject_code ?? 'N/A',
                    'cat_no' => $reg->classSchedule->cat_no ?? 'N/A',
                    'subject_title' => $reg->classSchedule->subject_title ?? 'N/A',
                    'section' => $reg->classSchedule->section ?? 'N/A',
                    'units' => $reg->classSchedule->units ?? 0,
                    'enrollment_type' => $reg->enrollment_type,
                    'ra_status' => $reg->ra_status,
                    'status' => $reg->status,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $formattedSubjects,
                'enrollment_id' => $enrollment->enrollment_id
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
     */
    public function store(RegistrationRequest $request)
    {
        try {
            $registration = Registration::create($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Subject added to preregistration successfully',
                'data' => new RegistrationResources($registration)
            ], 201);

        } catch (\Exception $e) {
            \Log::error('Error creating registration: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error adding subject to preregistration',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     * FIND ONE
     */
    public function show(string $id)
    {
        try {
            $registration = Registration::with(['user', 'classSchedule', 'enrollment'])
                ->findOrFail($id);

            return new RegistrationResources($registration);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Registration not found'
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RegistrationRequest $request, string $id)
    {
        try {
            $registration = Registration::findOrFail($id);
            $registration->update($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Registration updated successfully',
                'data' => new RegistrationResources($registration)
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating registration',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $registration = Registration::findOrFail($id);
            $registration->delete();

            return response()->json([
                'success' => true,
                'message' => 'Subject removed from preregistration successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error removing subject',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}