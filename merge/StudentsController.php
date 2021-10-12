<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Students;
use App\Models\University;
use Exception;
use SebastianBergmann\Environment\Console;
//use Monolog\Handler\SwiftMailerHandler;
use Illuminate\Support\Facades\Mail;

class StudentsController extends Controller
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
        $students = Students::orderby('name')->with('university')->paginate(20);
        $universities = University::all();
        return view('dashboard.students.students', ['students' => $students, 'universities' => $universities]);
    }



    public function update(Request $request, $id)
    {
        dd($request);
        $validatedData = $request->validate([
            'name' => 'required|min:1|max:255|unique:university',
            'email' => 'required|email',
            'number' => 'required|numeric|min:10|max:10',
            'university' => 'required',
        ]);

        $updateStudent = Students::find($id);
        $updateStudent->name     = $request->input('name');
        $updateStudent->email     = $request->input('email');
        $updateStudent->number     = $request->input('number');
        $updateStudent->university_id = (int) $request->input('university');
        $updateStudent->save();

        $request->session()->flash('message', 'Successfully Added Student');
        $request->session()->flash('alert-class', 'alert-success');
        return redirect()->route('students.index');
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
            'name' => 'required|min:1|max:255|unique:university',
            'email' => 'required|email',
            'number' => 'required|numeric|min:10|max:10',
            'university' => 'required',
        ]);

        $newStudent = new Students();
        $newStudent->name     = $request->input('name');
        $newStudent->email     = $request->input('email');
        $newStudent->password     = (string) rand(1111111, 9999999);
        $newStudent->number     = $request->input('number');
        $newStudent->is_verified     = 0;
        $newStudent->university_id = (int) $request->input('university');
        $newStudent->save();
        // Mail::send([], [], function ($message) use ($request) {
        //     $message->to($request->input('email'));
        //     $message->subject('Login Credentials');
        //     $message->setBody("Email ID: $request->input('email');");
        // });
        // Students::create([
        //     'name' => $request->name,
        //     'email' => $request->email,
        //     'password' => (string) rand(1111111, 9999999),
        //     'number' => $request->number,
        //     'is_verified' => 0,
        //     'university_id' => (int) $request->university
        // ]);
        $request->session()->flash('message', 'Successfully Added Student');
        $request->session()->flash('alert-class', 'alert-success');
        return redirect()->route('students.index');
    }

    // /**
    //  * Remove the specified resource from storage.
    //  *
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    public function destroy($id)
    {
        $student = Students::find($id);
        if ($student) {
            $student->delete();
        }
        return redirect()->route('students.index')->with('message', 'Student Removed!')->with('alert-class', 'alert-danger');
    }

    // public function getAllDepartmentsFromId(Request $request)
    // {
    //     try {
    //         if (!University::find($request->input('university_id'))) {
    //             return response()->json(['status' => 404, 'message' => 'Something went wrong']);
    //         } else {
    //             return response()->json(['status' => 200, 'data' => University::find($request->input('university_id'))->departments]);
    //         }
    //     } catch (Exception $e) {
    //         return response()->json(['status' => 404, 'message' => 'Something went wrong']);
    //     }
    // }

}
