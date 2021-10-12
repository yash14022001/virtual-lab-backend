<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PracticalInputValues extends Model
{
    use HasFactory;
    protected $table = 'practical_input_values';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'practical_input_id', 'value', 'serial_num',
    ];

    public function hasPracticalInput() {
        return $this->belongsTo(PracticalInput::class);
    }
}
