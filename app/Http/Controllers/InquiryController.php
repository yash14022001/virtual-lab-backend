<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Inquiry;

class InquiryController extends Controller
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
        $inquiries = Inquiry::orderby('created_at', 'desc')->paginate(20);
        return view('dashboard.students.inquiry', ['inquiries' => $inquiries]);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name'             => 'required|min:1|max:255|unique:university',
        ]);
        
        $university = University::find($id);
        $university->name     = $request->input('name');
        $university->save();

        $request->session()->flash('message', 'Successfully edited university');
        $request->session()->flash('alert-class', 'alert-success');
        return redirect()->route('university.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function create()
    // {
    //     $statuses = Status::all();
    //     return view('dashboard.notes.create', [ 'statuses' => $statuses ]);
    // }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name'             => 'required|min:1|max:255|unique:university',
        ]);
        
        $newUniversity = new University();
        $newUniversity->name     = $request->input('name');
        $newUniversity->save();
        
        $request->session()->flash('message', 'Successfully added University');
        $request->session()->flash('alert-class', 'alert-success');
        return redirect()->route('university.index');
    }

    // /**
    //  * Remove the specified resource from storage.
    //  *
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    public function destroy($id)
    {
        $inquiry = Inquiry::find($id);
        if($inquiry){
            try {
                $inquiry->delete();
            }
            catch(\Illuminate\Database\QueryException $e) {
                return redirect()->route('inquiry.index')->with('message', 'Soemthing went wrong!')->with('alert-class', 'alert-danger');
            }
        }
        return redirect()->route('inquiry.index')->with('message', 'Inquiry removed!')->with('alert-class', 'alert-success');
    }

    public function getAllDepartmentsFromId(Request $request) {
        try {
            if(!University::find($request->input('university_id'))) {
                return response()->json(['status'=>404,'message'=>'Something went wrong']);
            }
            else {
                return response()->json(['status'=>200,'data'=>University::find($request->input('university_id'))->departments]);
            }
        }
        catch (Exception $e) {
            return response()->json(['status'=>404,'message'=>'Something went wrong']);
        }
    }
}
