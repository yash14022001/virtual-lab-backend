<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Department;
use App\Models\University;
use App\Models\Subject;

class SubjectController extends Controller
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
        $subjects = Subject::with('department.university')->paginate(20);
        $universities = University::all();
        return view('dashboard.university.subject', ['subjects' => $subjects, 'universities'=>$universities]);
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

    public function departmentExistsFromId($id) {
        try {
            if(! Department::find($id)) {
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
        return redirect()->route('subject.index');
    }

    public function undefinedError($request) {
        $request->session()->flash('message', 'Something went wrong!');
        $request->session()->flash('alert-class', 'alert-danger');
        return redirect()->route('subject.index');
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name'             => 'required|min:1|max:255',
            'university'       => 'required',
            'department'       => 'required',
        ]);
        // dd($request);

        if(!$this->universityExistsFromId($request->input('university'))) {
            return $this->undefinedError($request);
        }

        if(!$this->departmentExistsFromId($request->input('department'))) {
            return $this->undefinedError($request);
        }
        
        $newSubject = Subject::find($id);
        $newSubject->name     = $request->input('name');
        $newSubject->department_id = (int) $request->input('department');
        $newSubject->save();
        

        $request->session()->flash('message', 'Successfully edited subject');
        $request->session()->flash('alert-class', 'alert-success');
        return redirect()->route('subject.index');
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
            'department'       => 'required',
        ]);

        if(!$this->departmentExistsFromId($request->input('department'))) {
            return $this->undefinedError($request);
        }
        
        $newSubject = new Subject();
        $newSubject->name     = $request->input('name');
        $newSubject->department_id = (int) $request->input('department');
        $newSubject->save();
        
        $request->session()->flash('message', 'Successfully created subject');
        $request->session()->flash('alert-class', 'alert-success');
        return redirect()->route('subject.index');
    }

    // /**
    //  * Remove the specified resource from storage.
    //  *
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    public function destroy($id)
    {
        $subject = Subject::find($id);
        if($subject){
            try {
                $subject->delete();
            }
            catch(\Illuminate\Database\QueryException $e) {
                return redirect()->route('subject.index')->with('message', 'This subject is associated with 1 or more practicals, Please delete those practicals before deleting this subject!')->with('alert-class', 'alert-danger');
            }
        }
        return redirect()->route('subject.index')->with('message', 'Subject deleted!')->with('alert-class', 'alert-success');
    }
}
