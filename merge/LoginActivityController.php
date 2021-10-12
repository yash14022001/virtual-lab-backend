<?php

namespace App\Http\Controllers;

use App\Models\Login_activity;
use App\Models\Students;
use Illuminate\Http\Request;

class LoginActivityController extends Controller
{
    public function index()
    {
        $login_activities = Login_activity::orderby('login_time')->with('students')->paginate(20);
        $students = Students::all();
        return view('dashboard.students.login_activity', ['login_activities' => $login_activities, 'students' => $students]);
    }
}
