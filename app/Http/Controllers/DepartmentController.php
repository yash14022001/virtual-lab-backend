<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Department;
use App\Models\University;

class DepartmentController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $departments = Department::orderby('name')->with('university')->paginate(20);
        $universities = University::all();
        return view('dashboard.university.department', ['departments' => $departments, 'universities'=>$universities]);
    }

    public function universityExistsFromId($id) {
        try {
            if(! University::find($id)) {
                return false;
            }
        }
        catch (Exception $e){
            return false;
        }
        return true;
    }

    public function universityIncorrectError($request) {
        $request->session()->flash('message', 'Please select correct university!');
        $request->session()->flash('alert-class', 'alert-danger');
        return redirect()->route('department.index');
    }

    public function undefinedError($request) {
        $request->session()->flash('message', 'Something went wrong!');
        $request->session()->flash('alert-class', 'alert-danger');
        return redirect()->route('department.index');
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name'             => 'required|min:1|max:255',
            'university'       => 'required',
        ]);

        if(!$this->universityExistsFromId($request->input('university'))) {
            return $this->undefinedError($request);
        }
        
        $newDepartment = Department::find($id);
        $newDepartment->name     = $request->input('name');
        $newDepartment->university_id = (int) $request->input('university');
        $newDepartment->save();

        $request->session()->flash('message', 'Successfully edited department');
        $request->session()->flash('alert-class', 'alert-success');
        return redirect()->route('department.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name'             => 'required|min:1|max:255',
            'university'       => 'required',
        ]);

        if(!$this->universityExistsFromId($request->input('university'))) {
            return $this->undefinedError($request);
        }
        
        $newDepartment = new Department();
        $newDepartment->name     = $request->input('name');
        $newDepartment->university_id = (int) $request->input('university');
        $newDepartment->save();
        
        $request->session()->flash('message', 'Successfully created department');
        $request->session()->flash('alert-class', 'alert-success');
        return redirect()->route('department.index');
    }

    // /**
    //  * Remove the specified resource from storage.
    //  *
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    public function destroy($id)
    {
        $department = Department::find($id);
        if($department){
            try {
                $department->delete();
            }
            catch(\Illuminate\Database\QueryException $e) {
                return redirect()->route('department.index')->with('message', 'This department is associated with 1 or more subject, Please delete those subjects before deleting this department!')->with('alert-class', 'alert-danger');
            }
        }
        return redirect()->route('department.index')->with('message', 'Department deleted!')->with('alert-class', 'alert-success');
    }

    public function getAllSubjectsFromId(Request $request) {
        try {
            if(!Department::find($request->input('department_id'))) {
                return response()->json(['status'=>404,'message'=>'Something went wrong']);
            }
            else {
                return response()->json(['status'=>200,'data'=>Department::find($request->input('department_id'))->subjects]);
            }
        }
        catch (Exception $e) {
            return response()->json(['status'=>404,'message'=>'Something went wrong']);
        }
    }
}
