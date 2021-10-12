<?php

namespace App\Imports;

use App\Models\Practical;
use App\Models\PracticalInputValues;
use App\Models\PracticalOutputValues;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class UsersImport implements ToCollection
{
    protected $hasAnyError = false, $practicalId, $totalInputOutput, $inputsIDHash, $outpusIDHash, $totalInputs, $totalOutputs,
    $errorMessage;

    function __construct($practicalId) {
        $this->practicalId = $practicalId;
        $practical = Practical::find($this->practicalId);
        $this->totalInputs = $practical->inputs->count();
        $this->totalOutputs = $practical->outputs->count();
        $this->totalInputOutput = $practical->inputs->count() + $practical->outputs->count();
        $this->inputsIDHash = Array();
        foreach($practical->inputs as $input) {
            $this->inputsIDHash[$input->id] = 0;
        }
        $this->outpusIDHash = Array();
        foreach($practical->outputs as $output) {
            $this->outpusIDHash[$output->id] = 0;
        }
    }

    public function hasAnyErrors() : bool {
        return $this->hasAnyError;
    }

    public function collection(Collection $rows)
    {
        $index = -1;
        $idArr = Array();
        foreach ($rows as $row) 
        {
            ++$index;
            if($index <= 2) continue;
            if($index == 3) {
                $idArr = $this->extractIdsFromRow($row);
                if($this->hasAnyErrorsInIds($idArr)) {
                    $this->hasAnyError = true;
                    return;
                }
                $this->deleteExisitingInputOutputs($idArr);
                continue;
            }

            for ($i=0; $i < $this->totalInputOutput; $i++) { 
                if($i >= $this->totalInputs) {
                    $practicalOutputValue = new PracticalOutputValues();
                    $practicalOutputValue->value = $row[$i];
                    $practicalOutputValue->serial_num = $index - 3;
                    $practicalOutputValue->practical_output_id = $idArr[$i];
                    $practicalOutputValue->save();
                }
                else {
                    $practicalInputValue = new PracticalInputValues();
                    $practicalInputValue->value = $row[$i];
                    $practicalInputValue->serial_num = $index - 3;
                    $practicalInputValue->practical_input_id = $idArr[$i];
                    $practicalInputValue->save();
                }
            }
            
        }
    }

    public function extractIdsFromRow($row) {
        $idArr = Array();
        foreach ($row as $cell) {
            $cellParts = explode(' - ', $cell);
            $id = trim(trim(end($cellParts), ']'), '[');
            array_push($idArr, $id);
        }
        return $idArr;
    }

    public function hasAnyErrorsInIds($idArr) {
        if(count($idArr) != $this->totalInputOutput) return true;
        foreach($idArr as $id) {
            if(!is_numeric($id)) {
                return true;
            }
        }
        
        return false;
    }

    public function deleteExisitingInputOutputs($idArr) {
        for ($i=0; $i < $this->totalInputOutput; $i++) { 
            if($i >= $this->totalInputs) {
                PracticalOutputValues::where('practical_output_id', $idArr[$i])->delete();
            }
            else {
                PracticalInputValues::where('practical_input_id', $idArr[$i])->delete();
            }
        }
    }
}
