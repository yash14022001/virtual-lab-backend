<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Input;

class InputController extends Controller
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
        $inputs = Input::orderby('name')->paginate(20);
        return view('dashboard.practical.inputs', ['inputs' => $inputs]);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name'             => 'required|min:1|max:255',
        ]);
        
        $input = Input::find($id);
        $input->name     = $request->input('name');
        $input->save();

        $request->session()->flash('message', 'Successfully edited input');
        $request->session()->flash('alert-class', 'alert-success');
        return redirect()->route('inputs.index');
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
        ]);
        
        $newInput = new Input();
        $newInput->name     = $request->input('name');
        $newInput->save();
        
        $request->session()->flash('message', 'Successfully created input');
        $request->session()->flash('alert-class', 'alert-success');
        return redirect()->route('inputs.index');
    }

    // /**
    //  * Remove the specified resource from storage.
    //  *
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    public function destroy($id)
    {
        $input = Input::find($id);
        if($input){
            try {
                $input->delete();
            }
            catch(\Illuminate\Database\QueryException $e) {
                return redirect()->route('inputs.index')->with('message', 'This input is connected to 1 or more practicals. Please remove those practicals first in order to remove this input!')->with('alert-class', 'alert-danger');
            }
        }
        return redirect()->route('inputs.index')->with('message', 'Input deleted!')->with('alert-class', 'alert-siccess');
    }
}
