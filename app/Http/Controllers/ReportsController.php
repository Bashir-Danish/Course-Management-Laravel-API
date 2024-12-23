<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Teacher;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    public function students(Request $request)
    {
        $query = DB::table('students')
            ->select(
                'students.*',
                'courses.name as course_name',
                'departments.name as department_name',
                'registrations.fees_total',
                'registrations.fees_paid',
                'registrations.time_slot',
                'registrations.registration_date'
            )
            ->leftJoin('registrations', 'students.id', '=', 'registrations.student_id')
            ->leftJoin('courses', 'registrations.course_id', '=', 'courses.id')
            ->leftJoin('departments', 'courses.department_id', '=', 'departments.id');

        $reportType = $request->report_type ?? 'weekly';

        switch ($reportType) {
            case 'weekly':
                $query->whereBetween('students.created_at', [now()->subWeek(), now()]);
                break;
            case 'monthly':
                $query->whereBetween('students.created_at', [now()->subMonth(), now()]);
                break;
            case 'yearly':
                $query->whereBetween('students.created_at', [now()->subYear(), now()]);
                break;
        }

        $students = $query->get();

        return view('Reports.Students-Reports', compact('students', 'reportType'));
    }

    public function teachers(Request $request)
    {
        $query = Teacher::select('teachers.*')
                        ->selectRaw('GROUP_CONCAT(departments.name) as department_name')
                        ->leftJoin('teacher_departments', 'teachers.id', '=', 'teacher_departments.teacher_id')
                        ->leftJoin('departments', 'teacher_departments.department_id', '=', 'departments.id')
                        ->groupBy(
                            'teachers.id',
                            'teachers.first_name',
                            'teachers.last_name',
                            'teachers.email',
                            'teachers.address',
                            'teachers.phone',
                            'teachers.gender',
                            'teachers.salary',
                            'teachers.created_at',
                            'teachers.updated_at'
                        );

        $reportType = $request->report_type ?? 'weekly';

        switch ($reportType) {
            case 'weekly':
                $query->whereBetween('teachers.created_at', [now()->subWeek(), now()]);
                break;
            case 'monthly':
                $query->whereBetween('teachers.created_at', [now()->subMonth(), now()]);
                break;
            case 'yearly':
                $query->whereBetween('teachers.created_at', [now()->subYear(), now()]);
                break;
        }

        $teachers = $query->get();

        return view('Reports.Teachers-Reports', compact('teachers', 'reportType'));
    }

    public function courses(Request $request)
    {
        $query = DB::table('courses')
            ->select('courses.*', 'departments.name as department_name')
            ->leftJoin('departments', 'courses.department_id', '=', 'departments.id');

        $reportType = $request->report_type ?? 'weekly';

        switch ($reportType) {
            case 'weekly':
                $query->whereBetween('courses.created_at', [now()->subWeek(), now()]);
                break;
            case 'monthly':
                $query->whereBetween('courses.created_at', [now()->subMonth(), now()]);
                break;
            case 'yearly':
                $query->whereBetween('courses.created_at', [now()->subYear(), now()]);
                break;
        }

        $courses = $query->get();

        return view('Reports.Course-Reports', compact('courses', 'reportType'));
    }

    public function departments(Request $request)
    {
        $query = Department::query();

        switch ($request->report_type) {
            case 'weekly':
                $query->whereBetween('created_at', [now()->subWeek(), now()]);
                break;
            case 'monthly':
                $query->whereBetween('created_at', [now()->subMonth(), now()]);
                break;
            case 'yearly':
                $query->whereBetween('created_at', [now()->subYear(), now()]);
                break;
            default:
                break;
        }

        $departments = $query->get();

        return view('Reports.Department-Reports', compact('departments'));
    }
} 