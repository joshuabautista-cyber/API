<?php

namespace App\Http\Controllers;
use App\Models\College;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\CollegeRequest;
use App\Http\Resources\CollegeResources;

class CollegeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try{
            $query = College::query();

            if ($request->has('search')) {
                $search = $request->query('search');
                $query->where('college_name', 'like', "%$search%");
            }

            $colleges = $query->paginate(10);

            return CollegeResources::collection($colleges);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while fetching colleges.'], 500);
        }
    }
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(CollegeRequest $request)
    {
        $colleges = College::create($request->validated());

        return new CollegeResources($colleges);
    }

    /**
     * Display the specified resource.
     */
    // Change (College $college) to ($id)
    public function show($id)
    {
        // Manually find the college using the custom primary key
        $college = College::find($id);

        // Check if it exists
        if (!$college) {
            return response()->json(['message' => 'College ID ' . $id . ' not found in database'], 404);
        }

        return new CollegeResources($college);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CollegeRequest $request, College $college)
    {
        $college->update($request->validated());

        return new CollegeResources($college);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $dept = College::findOrFail($id);
        $dept->delete();

        return response()->json(['message'=>'Deleted Succesfully'], 204);
    }
}
