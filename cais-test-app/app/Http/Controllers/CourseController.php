<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\College;   
use App\Http\Requests\CourseRequest;
use App\Http\Requests\EditRequest;
use App\Http\Resources\CourseResource;
use App\Http\Resources\CourseResources;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = Course::with('college');

                if ($request->has('college_id')) {
                    $query->where('college_id', $request->query('college_id'));
                }

                $courses = $query->paginate(10);

            return CourseResources::collection($courses);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while fetching courses.'],500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CourseRequest $request)
    {
        $data = $request->validated();
        $course = Course::create($data);
        $course->load('college'); 

        return new CourseResources($course);
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course)
    {
        
        return new CourseResources($course); 
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CourseRequest $request, string $id) 
{
    $course = Course::findOrFail($id);
    $course->update($request->validated());

    return new CourseResources($course);    
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
       $course = Course::findOrFail($id);
       $course->delete();
       
       return response()->json(['message' => 'Course deleted successfully.']);
    }
}
