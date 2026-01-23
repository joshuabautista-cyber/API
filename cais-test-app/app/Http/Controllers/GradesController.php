<?php

namespace App\Http\Controllers;

use App\Models\Grades;
use App\Http\Requests\GradesRequest;
use App\Http\Resources\GradesResources;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GradesController extends Controller
{
    /**
     * Display a listing of the resource.
     * RETRIEVE ALL
     */
    public function index(Request $request)
    {
        // CASE A: App is asking for a specific student's grades
        if ($request->has('user_id') && $request->has('semester_id')) {
            $userId = $request->user_id;
            $semesterId = $request->semester_id;

            try {
                // Direct query from tbl_grades with joins to get course info
                $grades = DB::table('tbl_grades as g')
                    ->join('tbl_class_schedules as s', 'g.schedId', '=', 's.schedId')
                    ->leftJoin('tbl_course as c', 's.course_id', '=', 'c.course_id')
                    ->where('g.user_id', $userId)
                    ->where('g.semester_id', $semesterId)
                    ->select(
                        'g.grade_id',
                        'g.user_id',
                        'g.semester_id',
                        'g.schedId',
                        's.subject_code',
                        's.subject_title',
                        's.course_id',
                        'c.course_name',
                        'g.units',
                        'g.grades',
                        'g.remarks',
                        'g.status',
                        'g.weight'
                    )
                    ->get();

                \Log::info("Fetching grades for user_id: {$userId}, semester_id: {$semesterId}");
                \Log::info("Found " . count($grades) . " grade records");

                return response()->json(['data' => $grades], 200);
                
            } catch (\Exception $e) {
                \Log::error('Error fetching grades: ' . $e->getMessage());
                return response()->json([
                    'message' => 'Error fetching grades',
                    'error' => $e->getMessage()
                ], 500);
            }
        }

        // CASE B: Default Admin View
        try {
            $grades = Grades::with([
                'student', 'faculty', 'subject', 'classSchedule', 'department'
            ])->get();
            return GradesResources::collection($grades);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(GradesRequest $request)
    {

    }

    /**
     * Display the specified resource.
     * FIND ONE
     */
    public function show(string $id)
    {
        // Find by primary key (grade_id) or fail with 404
        try {
            $grade = Grades::with([
                'student', 
                'faculty', 
                'subject', 
                'classSchedule', 
                'department',
            ])->findOrFail($id);

            return new GradesResources($grade);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Grade not found'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(GradesRequest $request, string $id)
    {

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

    }
}