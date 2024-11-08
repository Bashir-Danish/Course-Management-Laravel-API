<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\RegistrationResource;
use App\Models\Registration;
use App\Models\Student;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function generate(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'type' => ['required', 'string', 'in:registration,student,teacher'],
                'period' => ['required', 'string', 'in:weekly,monthly,yearly']
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $startDate = Carbon::now();
            
            // Calculate start date based on period
            switch ($request->period) {
                case 'weekly':
                    $startDate = $startDate->subWeek()->startOfDay();
                    break;
                case 'monthly':
                    $startDate = $startDate->subMonth()->startOfDay();
                    break;
                case 'yearly':
                    $startDate = $startDate->subYear()->startOfDay();
                    break;
            }

            $endDate = Carbon::now()->endOfDay();

            // Generate report based on type
            switch ($request->type) {
                case 'registration':
                    return $this->registrationReport($startDate, $endDate);
                case 'student':
                    return $this->studentReport($startDate, $endDate);
                case 'teacher':
                    return $this->teacherReport($startDate, $endDate);
                default:
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Invalid report type'
                    ], 400);
            }

        } catch (\Exception $e) {
            Log::error('Failed to generate report: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to generate report',
                'details' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    private function registrationReport($startDate, $endDate)
    {
        $registrations = Registration::with(['student', 'course'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        $totalFees = $registrations->sum('fees_total');
        $totalPaid = $registrations->sum('fees_paid');
        $totalUnpaid = $totalFees - $totalPaid;

        return response()->json([
            'status' => 'success',
            'data' => [
                'period' => [
                    'start' => $startDate->toDateString(),
                    'end' => $endDate->toDateString()
                ],
                'summary' => [
                    'total_registrations' => $registrations->count(),
                    'total_fees' => $totalFees,
                    'total_paid' => $totalPaid,
                    'total_unpaid' => $totalUnpaid,
                    'status_counts' => [
                        'active' => $registrations->where('status', 'Active')->count(),
                        'completed' => $registrations->where('status', 'Completed')->count(),
                        'cancelled' => $registrations->where('status', 'Cancelled')->count()
                    ]
                ],
                'registrations' => RegistrationResource::collection($registrations)
            ]
        ]);
    }

    private function studentReport($startDate, $endDate)
    {
        $students = Student::whereBetween('created_at', [$startDate, $endDate])->get();
        
        $activeRegistrations = Registration::where('status', 'Active')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        return response()->json([
            'status' => 'success',
            'data' => [
                'period' => [
                    'start' => $startDate->toDateString(),
                    'end' => $endDate->toDateString()
                ],
                'summary' => [
                    'total_new_students' => $students->count(),
                    'active_registrations' => $activeRegistrations
                ],
                'students' => $students
            ]
        ]);
    }

    private function teacherReport($startDate, $endDate)
    {
        $teachers = Teacher::whereBetween('created_at', [$startDate, $endDate])->get();
        
        $departmentStats = DB::table('teacher_departments')
            ->join('teachers', 'teachers.id', '=', 'teacher_departments.teacher_id')
            ->join('departments', 'departments.id', '=', 'teacher_departments.department_id')
            ->whereBetween('teachers.created_at', [$startDate, $endDate])
            ->select('departments.name', DB::raw('count(*) as count'))
            ->groupBy('departments.id', 'departments.name')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'period' => [
                    'start' => $startDate->toDateString(),
                    'end' => $endDate->toDateString()
                ],
                'summary' => [
                    'total_new_teachers' => $teachers->count(),
                    'department_distribution' => $departmentStats,
                    'average_salary' => $teachers->avg('salary')
                ],
                'teachers' => $teachers
            ]
        ]);
    }
} 