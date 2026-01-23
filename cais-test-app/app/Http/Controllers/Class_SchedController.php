<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Class_Sched; // Import the Model
use App\Http\Resources\Class_SchedResources; // Import the Resource

class Class_SchedController extends Controller
{
    /**
     * Display a listing of the resource (READ ALL).
     * Optionally filters by subject_code if provided in query.
     */
    public function index(Request $request)
    {
        try {
            $query = Class_Sched::query();

            // Filter by semester_id if provided
            if ($request->has('semester_id')) {
                $query->where('semester_id', $request->input('semester_id'));
            }

            // Search functionality
            if ($request->has('search')) {
                $search = $request->input('search');
                $query->where(function($q) use ($search) {
                    $q->where('subject_code', 'LIKE', "%{$search}%")
                    ->orWhere('sub_title', 'LIKE', "%{$search}%")
                    ->orWhere('cat_no', 'LIKE', "%{$search}%");
                });
            }

            $schedules = $query->paginate(15);

            return Class_SchedResources::collection($schedules);

        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while fetching class schedules.'], 500); 
        }
    }

    public function store(Request $request)
    {
        //
    }


    /**
     * Display the specified resource (FIND ONE).
     */
    public function show(string $id)
    {
        try {
        $schedule = Class_Sched::findOrFail($id);

        return new Class_SchedResources($schedule);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Class schedule not found.'], 404);
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
        //
    }
}
