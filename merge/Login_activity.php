<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Login_activity extends Model
{
    use HasFactory;
    protected $table = 'login_activity';

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'login_time', 'student_id',
    ];

    public function students()
    {
        return $this->belongsTo(Students::class);
    }
}
