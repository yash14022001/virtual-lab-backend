<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\AfterSheet;



use App\Models\User;
use App\Models\Practical;
use App\Models\PracticalInput;
use App\Models\PracticalOutput;

use \PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class UsersExport implements WithHeadings, WithEvents
{

    protected $practicalId, $inputSpan, $outputSpan, $inputs, $outputs;

    function __construct($practicalId) {
            $this->practicalId = $practicalId;
    }


    public function headings(): array
    {
        $practical = Practical::find($this->practicalId);
        
        $this->inputSpan = $practical->inputs->count();
        $this->outputSpan = $practical->outputs->count();

        $practicalHeadingRow = Array($practical->title);

        $this->inputs = Array();
        foreach($practical->inputs as $input) {
            $inputTypeName = $input->input->name;
            if(array_key_exists($inputTypeName, $this->inputs)) {
                array_push($this->inputs[$inputTypeName], $input->name . " - [" . $input->id . "]");
            }
            else {
                $this->inputs[$inputTypeName] = Array($input->name . " - [" . $input->id . "]");
            }
        }

        $this->outputs = Array();
        foreach($practical->outputs as $output) {
            $outputTypeName = $output->output->name;
            if(array_key_exists($outputTypeName, $this->outputs)) {
                array_push($this->outputs[$outputTypeName], $output->name . " - [" . $output->id . "]");
            }
            else {
                $this->outputs[$outputTypeName] = Array($output->name . " - [" . $output->id . "]");
            }
        }

        $firstHeadingRow = Array();
        for ($i=0; $i < $this->inputSpan; $i++) { 
            array_push($firstHeadingRow, 'Inputs');
        }
        for ($i=0; $i < $this->outputSpan; $i++) { 
            array_push($firstHeadingRow, 'Outputs');
        }

        $secondHeadingRow = Array();
        $thirdHeadingRow = Array();
        foreach($this->inputs as $inputType => $inputsArr) {
            for ($i=0; $i < count($inputsArr); $i++) { 
                array_push($secondHeadingRow, $inputType);
                array_push($thirdHeadingRow, $inputsArr[$i]);
            }
        }

        foreach($this->outputs as $outputType => $outputsArr) {
            for ($i=0; $i < count($outputsArr); $i++) { 
                array_push($secondHeadingRow, $outputType);
                array_push($thirdHeadingRow, $outputsArr[$i]);
            }
        }


        return [
            $practicalHeadingRow,
            $firstHeadingRow,
            $secondHeadingRow,
            $thirdHeadingRow,
        ];
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $event->sheet->getDelegate()->mergeCells(Coordinate::stringFromColumnIndex(1) . "1:" . Coordinate::stringFromColumnIndex($this->inputSpan + $this->outputSpan) . "1");
                $event->sheet->getDelegate()->mergeCells(Coordinate::stringFromColumnIndex(1) . "2:" . Coordinate::stringFromColumnIndex($this->inputSpan) . "2");
                $event->sheet->getDelegate()->mergeCells(Coordinate::stringFromColumnIndex($this->inputSpan + 1) . "2:" . Coordinate::stringFromColumnIndex($this->inputSpan + $this->outputSpan) . "2");

                $spanPointer = 1;
                foreach($this->inputs as $inputType => $inputsArr) {
                    $event->sheet->getDelegate()->mergeCells(Coordinate::stringFromColumnIndex($spanPointer) . "3:" . Coordinate::stringFromColumnIndex($spanPointer + count($inputsArr) - 1) . "3");
                    $spanPointer += count($inputsArr);
                }

                foreach($this->outputs as $outputType => $outputsArr) {
                    $event->sheet->getDelegate()->mergeCells(Coordinate::stringFromColumnIndex($spanPointer) . "3:" . Coordinate::stringFromColumnIndex($spanPointer + count($outputsArr) - 1) . "3");
                    $spanPointer += count($outputsArr);
                }
            },
        ];
    }
}
