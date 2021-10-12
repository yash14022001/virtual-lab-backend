<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PracticalOutputValues extends Model
{
    use HasFactory; 
    protected $table = 'practical_output_values';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'practical_output_id', 'value', 'serial_num',
    ];

    public function hasPracticalOutput() {
        return $this->belongsTo(PracticalOutput::class);
    }
}
