<?php
namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\College;   
use Illuminate\Http\Request;
use App\Http\Requests\DepartmentRequest;
use App\Http\Requests\EditRequest;
use App\Http\Resources\DepartmentResource;
use App\Http\Resources\DepartmentResources;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try{
            $query = Department::with('college');

            if ($request->has('college_id')) {
                $query->where('college_id', $request->query('college_id'));
            }
            $departments = $query->paginate(10);

            return DepartmentResources::collection($departments);

        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while fetching departments.'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DepartmentRequest $request)
    {
        $data = $request->validated();

        $college = \App\Models\College::findOrFail($data['college_id']);
        $data['college_name'] = $college->college_name; 
        $department = Department::create($data);
        $department->load('college'); 

        return new DepartmentResources($department);
    }
    

    /**
     * Display the specified resource.
     */
    public function show(Department $department)
    {
        return new DepartmentResources($department);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EditRequest $request, Department $department)
    {
        $department->update($request->validated());

        return new DepartmentResources($department);    
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $dept = Department::findOrFail($id);
        $dept->delete();

        return response()->json(['message'=>'Deleted Succesfully'], 204);
    }
}
