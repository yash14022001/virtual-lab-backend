<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;
    protected $table = 'department';

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'university_id',
    ];

    public function university()
    {
        return $this->belongsTo(University::class);
    }

    public function subjects() {
        return $this->hasMany(Subject::class);
    }
}
