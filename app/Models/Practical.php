<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Practical extends Model
{
    use HasFactory;
    protected $table = 'practical';
    public $timestamps = [ "created_at" ]; // enable only to created_at
    const UPDATED_AT = null;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'youtube_link', 'subject_id', 'created_at',
    ];

    public function subject() {
        return $this->belongsTo(Subject::class);
    }

    public function inputs() {
        return $this->hasMany(PracticalInput::class);
    }

    public function outputs() {
        return $this->hasMany(PracticalOutput::class);
    }

}
