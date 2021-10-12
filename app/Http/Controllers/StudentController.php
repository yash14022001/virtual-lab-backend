<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Validator;
use App\Models\Student;
use Auth;
use App\Models\University;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordMailer;

class StudentController extends Controller
{
    public function login()
    {
        if (Auth::guard('student')->attempt(['email' => request('email'), 'password' => request('password')])) {
            $user = Auth::guard('student')->user();
            if(!$user->is_verified) {
              $user->is_verified = true;
              $user->save();  
            }
            $success['token'] = $user->createToken('appToken')->accessToken;
           //After successfull authentication, notice how I return json parameters
            return response()->json([
              'success' => true,
              'token' => $success,
              'user' => $user
          ]);
        } else {
       //if authentication is unsuccessfull, notice how I return json parameters
          return response()->json([
            'success' => false,
            'message' => 'Invalid Email or Password',
        ], 401);
        }
    }

    /**
     * Register api.
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:students',
            'number' => 'required|unique:students|regex:/[0-9]{10}/',
            'university_id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
          error_log('Some message here.');
          return response()->json([
            'success' => false,
            'message' => $validator->errors(),
          ], 401);
        }
        $input = $request->all();
        $generatedPassword = Str::random(10);
        $input['password'] = Hash::make($generatedPassword);

        $user = Student::create($input);
        $userId = $user->id;

        try {
          $detailsForMail = [
            'userName' => $user->name,
            'userPassword' => $generatedPassword,
          ];
          Mail::to($user->email)->send(new PasswordMailer($detailsForMail));
        } catch(\Exception $e) {
          Student::find($userId)->delete();
          return response()->json([
            'success' => false,
            'message' => "Something went wrong while registering...",
            "error_message" => $e->getMessage(),
          ], 401);
        }

        $success['token'] = $user->createToken('appToken')->accessToken;
        return response()->json([
          'success' => true,
          'token' => $success,
          'user' => $user
      ]);
    }

    public function logout(Request $res)
    {
      if (Auth::guard('api')->user()) {
        $user = Auth::guard('api')->user()->token();
        $user->revoke();

        return response()->json([
          'success' => true,
          'message' => 'Logout successfully'
      ]);
      } else {
        return response()->json([
          'success' => false,
          'message' => 'Unable to Logout'
        ]);
      }
     }

     public function forgotPassword(Request $request) {
        $validator = Validator::make($request->all(), ['email' => 'required|email']); 	
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => "Email is not correct.",
            ]);
        }

        $user = Student::where('email', $request->email)->get();
        if($user == null) {
          return response()->json([
                'success' => false,
                'message' => "Email not found.",
            ]);
        }
        $user = $user[0];
        $generatedPassword = Str::random(10);
        $user->password = bcrypt($generatedPassword);

        try {
          $detailsForMail = [
            'userName' => $user->name,
            'userPassword' => $generatedPassword,
          ];
          Mail::to($user->email)->send(new PasswordMailer($detailsForMail));
          $user->save();
        } catch(\Exception $e) {
          return response()->json([
            'success' => false,
            'message' => "Something went wrong while sending mail...",
            "error_message" => $e->getMessage(),
          ], 401);
        }

        return response()->json([
          'success' => true,
        ]);
    }

    public function index()
    {
        $students = Student::orderby('name')->with('university')->paginate(20);
        $universities = University::all();
        return view('dashboard.students.students', ['students' => $students, 'universities' => $universities]);
    }



    public function update(Request $request, $id)
    {
        // dd($request);
        $validatedData = $request->validate([
            'name' => 'required|min:1|max:255|unique:university',
            'email' => 'required|email',
            'number' => 'required|numeric|regex:/[0-9]{10}/',
            'university' => 'required',
        ]);

        $updateStudent = Student::find($id);
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
            'number' => 'required|numeric|regex:/[0-9]{10}/',
            'university' => 'required',
        ]);

        $newStudent = new Students();
        $newStudent->name     = $request->input('name');
        $newStudent->email     = $request->input('email');
        $newStudent->password     = Str::random(10);
        $newStudent->number     = $request->input('number');
        $newStudent->is_verified     = 0;
        $newStudent->university_id = (int) $request->input('university');
        $newStudent->save();
        
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
        $student = Student::find($id);
        if ($student) {
            $student->delete();
        }
        return redirect()->route('students.index')->with('message', 'Student Removed!')->with('alert-class', 'alert-danger');
    }
}
