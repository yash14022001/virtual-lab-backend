<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Validator;
use App\Models\Student;
use Auth;

use App\Models\University;
use App\Models\Department;
use App\Models\Subject;
use App\Models\Practical;
use App\Models\Inquiry;
use App\Mail\Mailer;

class StudentAPIController extends Controller
{
    public function university(Request $request) {
        try {
            $allUniversities = University::all();
            return response()->json([
                'success' => true,
                'universities' => $allUniversities,
            ]);
        }
        catch(\Exception $e) {
            return response()->json([
                'success' => false,
            ]);
        }
    }

    public function departmentFromUniversityId(Request $request) {
        try {
            $departments = Department::where('university_id', $request->universityId)->get();
            return response()->json([
                'success' => true,
                'departments' => $departments,
            ]);
        }
        catch(\Exception $e) {
            return response()->json([
                'success' => false,
            ]);
        }
    }

    public function subjectFromDepartmentId(Request $request) {
        try {
            $subjects = Subject::where('department_id', $request->departmentId)->get();
            return response()->json([
                'success' => true,
                'subjects' => $subjects,
            ]);
        }
        catch(\Exception $e) {
            return response()->json([
                'success' => false,
            ]);
        }
    }

    public function allPracticalFromSubjectId(Request $request) {
        try {
            $practicals = Practical::where('subject_id', $request->subjectId)->get();
            return response()->json([
                'success' => true,
                'practicals' => $practicals,
            ]);
        }
        catch(\Exception $e) {
            return response()->json([
                'success' => false,
            ]);
        }
    }

    public function practicalFromPracticalId(Request $request) {
        try {
            $practical = Practical::find($request->practicalId);
            
            $inputsArr = Array();
            foreach ($practical->inputs as $input) {
                $singleInputArr = Array();
                $singleInputArr["inputName"] = $input->name;
                $singleInputArr["inputType"] = $input->input->name;
                $singleInputArr["inputValues"] = Array();
                foreach($input->inputValues()->orderby('serial_num')->get() as $inputValue) {
                    array_push($singleInputArr['inputValues'], $inputValue->value);
                }
                array_push($inputsArr, $singleInputArr);
            }

            $outputsArr = Array();
            foreach ($practical->outputs as $output) {
                $singleOutputArr = Array();
                $singleOutputArr["outputName"] = $output->name;
                $singleOutputArr["outputType"] = $output->output->name;
                $singleOutputArr["outputValues"] = Array();
                foreach($output->outputValues()->orderby('serial_num')->get() as $outputValue) {
                    array_push($singleOutputArr['outputValues'], $outputValue->value);
                }
                array_push($outputsArr, $singleOutputArr);
            }
            
            return response()->json([
                // 'success' => true,
                'practical' => $practical,
                'inputs' => $inputsArr,
                'outputs' => $outputsArr,
            ]);
        }
        catch(\Exception $e) {
            return response()->json([
                'success' => false,
            ]);
        }
        
    }

    public function postInquiry(Request $request) {
        $validator = Validator::make($request->all(), ['inquiry' => 'required|min:1|max:1000']); 	
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => "Inquiry should be less than 1000 characters.",
            ]);
        }

        $user = auth()->guard('api')->user();

        $inquiry = new Inquiry();
        $inquiry->student_id = $user->id;
        $inquiry->message = $request->inquiry;
        $inquiry->save();

        $detailsForMail = [
            'userEmail' => $user->email,
            'indexOfInquiry' => $inquiry->id,
            'userName' => $user->name,
            'inquiry' => $request->inquiry,
        ];

        Mail::to("adm1n.virtual.lab@gmail.com")->send(new Mailer($detailsForMail));

        return response()->json([
            'success' => true,
        ]); 
    }

    

}
