<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Subject;
use App\Models\Department;
use App\Models\University;
use App\Models\Practical;
use App\Models\Input;
use App\Models\Output;
use App\Models\PracticalInput;
use App\Models\PracticalOutput;
use App\Models\PracticalInputValues;
use App\Models\PracticalOutputValues;

class PracticalController extends Controller
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

    public function _extractInputOutput($data, $prefix) {
        $filteredArray = array();
        foreach ($data as $key => $value) {
            $arr = explode("-", $key);
            if (count($arr) == 3) {
                if ($arr[0] == $prefix && $arr[1] == 'name' && is_numeric($arr[2])) {
                    $correspondingDropDownKey = $prefix."-drop-down-".$arr[2];
                    if (array_key_exists($correspondingDropDownKey, $data)) {
                        array_push($filteredArray, Array($value, $data[$correspondingDropDownKey]));
                    }
                }
            }
        }
        return $filteredArray;
    }

    public function _extractDeleteInputOutput($data, $prefix) {
        $filteredArray = array();
        foreach ($data as $key => $value) {
            $arr = explode("-", $key);
            if (count($arr) == 3) {
                if ($arr[0] == 'existing' && $arr[1] == $prefix && is_numeric($arr[2])) {
                    array_push($filteredArray, (int)$arr[2]);
                }
            }
        }
        return $filteredArray;
    }

    public function _hasAnyUndefinedInputOutput($IOArray, $prefix) {
        $hasAnyUndefinedIO = false;

        foreach($IOArray as $key => $value) {
            if ($prefix == 'input' && !Input::find((int) $value[1])) {
                $hasAnyUndefinedIO = true;
                break;
            }
            else if($prefix == 'output' && !Output::find((int) $value[1])) {
                $hasAnyUndefinedIO = true;
                break;
            }
        }

        return $hasAnyUndefinedIO;
    }

    public function undefinedError($request) {
        $request->session()->flash('message', 'Something went wrong!');
        $request->session()->flash('alert-class', 'alert-danger');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $practicals = Practical::with('subject.department.university')->paginate( 20 );
        return view('dashboard.practical.practical-list', ['practicals' => $practicals]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $universities = University::all();
        $inputs = Input::all();
        $outputs = Output::all();
        return view('dashboard.practical.practical-create', [ 'universities' => $universities, "inputs" => $inputs, "outputs" => $outputs ]);
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
            'title'             => 'required|min:1|max:255',
            'subject_id'           => 'required',
            'youtube_link'         => 'nullable|url',
        ]); 
        
        
        $inputsArray = $this->_extractInputOutput($request->all(), 'input');
        $outputsArray = $this->_extractInputOutput($request->all(), 'output');

        if($this->_hasAnyUndefinedInputOutput($inputsArray, 'input') || 
        $this->_hasAnyUndefinedInputOutput($inputsArray, 'input')) {
            $this->undefinedError($request);
            return redirect()->route('practical.create')->withInput();
        }
        
        $practical = new Practical();
        $practical->title     = $request->input('title');
        $practical->subject_id   = $request->input('subject_id');
        $practical->youtube_link = $request->input('youtube_link');
        $practical->save();

        foreach($inputsArray as $key => $value) {
            $inputName = $value[0];
            $inputTypeId = $value[1];
            
            $newPracticalInput = new PracticalInput();
            $newPracticalInput->practical_id = $practical->id;
            $newPracticalInput->name = $inputName;
            $newPracticalInput->input_id = $inputTypeId;
            $newPracticalInput->save();
        }

        foreach($outputsArray as $key => $value) {
            $outputName = $value[0];
            $outputTypeId = $value[1];
            
            $newPracticalOutput = new PracticalOutput();
            $newPracticalOutput->practical_id = $practical->id;
            $newPracticalOutput->name = $outputName;
            $newPracticalOutput->output_id = $outputTypeId;
            $newPracticalOutput->save();
        }
        $request->session()->flash('message', 'Successfully created practical');
        return redirect()->route('practical.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $practical = Practical::with('subject.department.university')->find($id);
        return view('dashboard.practical.practical-show', [ 'practical' => $practical ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $practical = Practical::find($id);
        $universities = University::all();
        $departments = University::find($practical->subject->department->university->id)->departments;
        $subjects = Department::find($practical->subject->department->id)->subjects;
        $inputs = Input::all();
        $outputs = Output::all();
        return view('dashboard.practical.practical-edit', [ 'universities' => $universities, 'practical' => $practical, 'departments' => $departments, 'subjects' => $subjects, 'inputs' => $inputs, 'outputs' => $outputs ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // dd($request->all());
        $validatedData = $request->validate([
            'title'             => 'required|min:1|max:255',
            'subject_id'           => 'required',
            'youtube_link'         => 'nullable|url',
        ]);
        
        $practical = Practical::find($id);
        $practical->title     = $request->input('title');
        $practical->subject_id   = $request->input('subject_id');
        $practical->youtube_link = $request->input('youtube_link');
        $practical->save();

        $hasAnyChangeInInputsOutpus = false;
        $inputsArray = $this->_extractInputOutput($request->all(), 'input');
        $outputsArray = $this->_extractInputOutput($request->all(), 'output');

        if(count($inputsArray) >= 1 || count($outputsArray) >= 1) {
            $hasAnyChangeInInputsOutpus = true;
        }

        if($this->_hasAnyUndefinedInputOutput($inputsArray, 'input') || 
        $this->_hasAnyUndefinedInputOutput($inputsArray, 'input')) {
            $this->undefinedError($request);
            return redirect()->route('practical.edit')->withInput();
        }

        foreach($inputsArray as $key => $value) {
            $inputName = $value[0];
            $inputTypeId = $value[1];
            
            $newPracticalInput = new PracticalInput();
            $newPracticalInput->practical_id = $practical->id;
            $newPracticalInput->name = $inputName;
            $newPracticalInput->input_id = $inputTypeId;
            $newPracticalInput->save();
        }

        foreach($outputsArray as $key => $value) {
            $outputName = $value[0];
            $outputTypeId = $value[1];
            
            $newPracticalOutput = new PracticalOutput();
            $newPracticalOutput->practical_id = $practical->id;
            $newPracticalOutput->name = $outputName;
            $newPracticalOutput->output_id = $outputTypeId;
            $newPracticalOutput->save();
        }

        $deleteInputArray = $this->_extractDeleteInputOutput($request->all(), 'input');
        $deleteOutputArray = $this->_extractDeleteInputOutput($request->all(), 'output');

        // dd($request->all());
        // dd($deleteInputArray);
        // dd($deleteOutputArray);

        if(count($deleteInputArray) >= 1 || count($deleteOutputArray) >= 1) {
            $hasAnyChangeInInputsOutpus = true;
        }

        if($hasAnyChangeInInputsOutpus) {
            foreach($practical->inputs as $input) {
                PracticalInputValues::where('practical_input_id', $input->id)->delete();
            }

            foreach($practical->outputs as $output) {
                PracticalOutputValues::where('practical_output_id', $output->id)->delete();
            }
        }

        foreach ($deleteInputArray as $id) {
            try {
                PracticalInput::where('id', $id)->delete();
            }
            catch(Exception $e) {}
        }

        foreach ($deleteOutputArray as $id) {
            try {
                PracticalOutput::where('id', $id)->delete();
            }
            catch(Exception $e) {}
        }

        $request->session()->flash('message', 'Successfully edited practical');
        return redirect()->route('practical.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $practical = Practical::find($id);
        foreach($practical->inputs as $input) {
            PracticalInputValues::where('practical_input_id', $input->id)->delete();
        }

        foreach($practical->outputs as $output) {
            PracticalOutputValues::where('practical_output_id', $output->id)->delete();
        }
        PracticalInput::where('practical_id', $id)->delete();
        PracticalOutput::where('practical_id', $id)->delete();
        if($practical){
            $practical->delete();
        }
        return redirect()->route('practical.index');
    }
}
