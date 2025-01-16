<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Teacher;
use App\Models\Course;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    public function students(Request $request)
    {
        $departments = Department::all(); 
        $courses = Course::all();
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

            if ($request->has('selected_department')) {
                $query->where('departments.id', $request->selected_department);
          }
          if ($request->has('selected_course')) {
            $query->where('courses.id', $request->selected_course);
      }   if ($request->has('selected_payment')) {
        $query->where('registrations.status', $request->selected_payment);
  }

        $reportType = $request->report_type ?? 'weekly';
        $selectedCourse = $request->selected_course ?? '';
        $selectedDepartment = $request->selected_department ?? '';
        $selectedPayment = $request->selected_payment ?? '';


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

        return view('Reports.Students-Reports', compact('students','courses','departments', 'reportType', 'selectedDepartment','selectedPayment', 'selectedCourse'));
    }

    public function teachers(Request $request)
    {
        $departments = Department::all(); 

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

                        if ($request->has('selected_department')) {
                            $query->where('departments.id', $request->selected_department);
                      }
                      if ($request->has('selected_gender')) {
                        $query->where('teachers.gender', $request->selected_gender);
                  }
                  if ($request->has('selected_salary')) {
                    $query->orderBy('teachers.salary', $request->selected_salary);
              }


        $reportType = $request->report_type ?? 'weekly';
        $selectedDepartment = $request->selected_department ?? '';
        $selectedGender = $request->selected_gender ?? '';
        $selectedSalary = $request->selected_salary ?? '';
        

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

     
        return view('Reports.Teachers-Reports', compact('teachers', 'reportType','departments','selectedSalary','selectedDepartment','selectedGender'));
    }

    public function courses(Request $request)
    {
        $departments = Department::all(); 

        $query = DB::table('courses')
            ->select('courses.*', 'departments.name as department_name')
            ->leftJoin('departments', 'courses.department_id', '=', 'departments.id');

            if ($request->has('selected_department')) {
                $query->where('courses.department_id', $request->selected_department);
          }
        $reportType = $request->report_type ?? 'weekly';
        $selectedDepartment = $request->selected_department ?? '';

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

        return view('Reports.Course-Reports', compact('courses','departments', 'reportType','selectedDepartment'));
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