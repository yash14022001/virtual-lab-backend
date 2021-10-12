<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\UsersExport;
use App\Imports\UsersImport;
use App\Models\Practical;
use Excel;

class ExcelSheetController extends Controller
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
    public function index($id)
    {
        $practical = Practical::find($id);
        if(!$practical) {
            return abort(404);
        }
        return view('dashboard.practical.practical-excel-sheet', ['practical' => $practical]);
    }

    public function download($id) {
        $practical = Practical::find($id);
        if(!$practical) {
            return abort(404);
        }
        return Excel::download(new UsersExport($id), $practical->title . '.xlsx');
    }

    public function upload(Request $request, $id) {
        $validatedData = $request->validate([
            'file' => 'required|file|mimes:xlsx',
        ]);

        $imports = new UsersImport($id);
        Excel::import($imports, request()->file('file'));
        if($imports->hasAnyErrors()) {
            $request->session()->flash('message', 'There was problem with the uploaded file...');
            $request->session()->flash('alert-class', 'alert-danger');
        }
        else {
            $request->session()->flash('message', 'Successfully imported inputs and outputs from the uploaded file...');
            $request->session()->flash('alert-class', 'alert-success');
        }
        return redirect()->route('practical.excel-sheet.index', ['id' => $id]);
    }
}
