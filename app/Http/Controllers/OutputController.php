<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Output;

class OutputController extends Controller
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
        $outputs = Output::orderby('name')->paginate(20);
        return view('dashboard.practical.outputs', ['outputs' => $outputs]);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name'             => 'required|min:1|max:255',
        ]);
        
        $output = Output::find($id);
        $output->name     = $request->input('name');
        $output->save();

        $request->session()->flash('message', 'Successfully edited output');
        $request->session()->flash('alert-class', 'alert-success');
        return redirect()->route('outputs.index');
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
            'name'             => 'required|min:1|max:255',
        ]);
        
        $newOutput = new Output();
        $newOutput->name     = $request->input('name');
        $newOutput->save();
        
        $request->session()->flash('message', 'Successfully created output');
        $request->session()->flash('alert-class', 'alert-success');
        return redirect()->route('outputs.index');
    }

    // /**
    //  * Remove the specified resource from storage.
    //  *
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    public function destroy($id)
    {
        $output = Output::find($id);
        if($output){
            try {
                $output->delete();
            }
            catch(\Illuminate\Database\QueryException $e) {
                return redirect()->route('outputs.index')->with('message', 'This output is connected to 1 or more practicals. Please remove those practicals first in order to remove this output!')->with('alert-class', 'alert-danger');
            }
        }
        return redirect()->route('outputs.index')->with('message', 'Output deleted!')->with('alert-class', 'alert-danger');
    }
}
