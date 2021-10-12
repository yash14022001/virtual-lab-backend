<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;
    protected $table = 'subject';

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'department_id',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function practicals() {
        return $this->hasMany(Subject::class);
    }
}
