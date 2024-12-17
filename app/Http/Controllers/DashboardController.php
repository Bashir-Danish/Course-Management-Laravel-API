<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\Course;
use App\Models\Department;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'totalStudents' => Student::count(),
            'totalTeachers' => Teacher::count(),
            'totalCourses' => Course::count(),
            'totalDepartments' => Department::count(),
        ];

        return view('dashboard', $data);
    }
} 