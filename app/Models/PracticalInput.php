<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PracticalInput extends Model
{
    use HasFactory;
    protected $table = 'practical_input';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'practical_id', 'input_id',
    ];

    public function practical() {
        return $this->belongsTo(Practical::class);
    }

    public function input() {
        return $this->belongsTo(Input::class);
    }

    public function inputValues() {
        return $this->hasMany(PracticalInputValues::class);
    }
}
