<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PracticalOutput extends Model
{
    use HasFactory;
    protected $table = 'practical_output';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'practical_id', 'output_id',
    ];

    public function practical() {
        return $this->belongsTo(Practical::class);
    }

    public function output() {
        return $this->belongsTo(Output::class);
    }

    public function outputValues() {
        return $this->hasMany(PracticalOutputValues::class);
    }
}
