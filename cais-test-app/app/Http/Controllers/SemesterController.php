<?php

namespace App\Http\Controllers;

use App\Models\Semester;
use Illuminate\Http\Request;
use App\Http\Requests\SemesterRequest;   
use App\Http\Resources\SemesterResources;
use Illuminate\Support\Facades\DB;

class SemesterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $semesters = Semester::all();
        return response()->json($semesters); // or return SemesterResource::collection($semesters);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SemesterRequest $request)
    {
        // The request is already validated by SemesterRequest
        try{
            $semester = Semester::create($request->validated());
            return new SemesterResources($semester);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create semester', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Semester $semester)
    {
        // Laravel automatically finds the semester by ID (Route Model Binding)
    try{
    $userId = $request->query('user_id');
    $semesterId = $request->query('semester_id');

    $grades = DB::table('tbl_registration as r')
        ->join('tbl_class_schedules as s', 'r.schedId', '=', 's.schedId')
        ->join('tbl_course as c', 's.course_id', '=', 'c.course_id')
        // Left join because grades might not be encoded yet, but we still want to see the subject
        ->leftJoin('tbl_grades as g', function($join) use ($userId) {
            $join->on('g.sched_id', '=', 's.schedId')
                 ->where('g.student_id', '=', $userId);
        })
        ->where('r.user_id', $userId)
        ->where('s.semester_id', $semesterId)
        ->select(
            'c.course_code',
            'c.course_name',
            'c.units',
            'g.prelims',
            'g.midterm',
            'g.finals',
            'g.total_grade'
        )
        ->get();

    return response()->json(['data' => $grades]);

    } catch (\Exception $e) {
        return response()->json(['error' => 'Failed to retrieve grades', 'message' => $e->getMessage()], 500);
}
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SemesterRequest $request, Semester $semester)
    {
        try{
            $semester->update($request->validated());
            return new SemesterResources($semester);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update semester', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Semester $semester)
    {
        try{    
            $semester->delete();
            // Return 204 No Content (Standard for delete)
            return response()->noContent();
            
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete semester', 'message' => $e->getMessage()], 500);
        }
    }
}