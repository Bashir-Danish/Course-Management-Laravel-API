<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::oldest();
        
        if ($request->has('search') && strlen($request->search) >= 3) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('first_name', 'like', "%{$searchTerm}%")
                  ->orWhere('last_name', 'like', "%{$searchTerm}%")
                  ->orWhere('id', 'like', "%{$searchTerm}%")
                  ->orWhere('gender', 'like', "%{$searchTerm}%")
                  ->orWhere('phone', 'like', "%{$searchTerm}%")
                  ->orWhere('address', 'like', "%{$searchTerm}%")
                  ->orWhere('dob', 'like', "%{$searchTerm}%");
            });
            $students = $query->get();
            $isPaginated = false;
        } else {
            $students = $query->paginate(10)->withQueryString();
            $isPaginated = true;
        }
        
        if ($request->ajax()) {
            return view('students.table', compact('students', 'isPaginated'))->render();
        }
        
        return view('List-Of-Students', compact('students', 'isPaginated'));
    }

    public function create()
    {
        return view('Add-Student');
    }

    public function store(Request $request)
    {
        try {
            \Log::info('Student creation attempt with data:', $request->all());
            
            $validated = $request->validate([
                'first_name' => 'required|string|min:2|max:50',
                'last_name' => 'required|string|min:2|max:50',
                'phone' => [
                    'required',
                    'string',
                    Rule::unique('students','phone'),
                ],
                // 'phone' => 'nullable|string|min:10|max:15',
                'address' => 'nullable|string|min:5|max:200',
                'gender' => 'required|in:Male,Female',
                'dob' => 'required|date',

            ]);

            \Log::info('Validation passed, validated data:', $validated);

            $student = Student::create([
                'first_name' => $validated['first_name'], 
                'last_name' => $validated['last_name'], 
                'phone' => $validated['phone'], 
                'address' => $validated['address'], 
                'gender' => $validated['gender'], 
                'dob' => $validated['dob'], 
            ]);

            \Log::info('Student created successfully:', $student->toArray());

            return response()->json([
                'success' => true,
                'message' => 'Student created successfully',
                'student' => $student
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation error:', $e->errors());
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error creating student: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => config('app.debug') ? $e->getMessage() : 'An error occurred while creating the student'
            ], 500);
        }
    }

    public function edit($id)
    {
        try {
            $student = Student::findOrFail($id);
            if ($student) {
                return view('Add-Student', compact('student'));
            }
            return redirect()->route('students.index')
                ->with('error', 'Student not found');
        } catch (\Exception $e) {
            return redirect()->route('students.index')
                ->with('error', 'Error loading student');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $student = Student::findOrFail($id);
            
            $validated = $request->validate([
                'first_name' => 'required|string|min:2|max:50',
                'last_name' => 'required|string|min:2|max:50',
                'phone' => 'nullable|string|min:10|max:15',
                'address' => 'nullable|string|min:5|max:200',
                'gender' => 'required|in:Male,Female',
                'dob' => 'required|date',
            ]);

            $student->update([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'gender' => $validated['gender'],
                'dob' => $validated['dob'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Student updated successfully',
                'student' => $student
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the student'
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $student = Student::findOrFail($id);
            $student->delete();

            return response()->json([
                'success' => true,
                'message' => 'Student deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting student'
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $student = Student::with(['registrations' => function($query) {
                $query->with('course');
            }])->findOrFail($id);

            $courses = Course::select('id', 'name', 'fees', 'available_time_slots')
                ->orderBy('name')
                ->get()
                ->map(function($course) {
                    return [
                        'id' => $course->id,
                        'name' => $course->name . ' (' . number_format($course->fees, 2) . ')',
                        'fees' => $course->fees,
                        'available_time_slots' => $course->getRawOriginal('available_time_slots')
                    ];
                });

            return view('students.show', compact('student', 'courses'));
        } catch (\Exception $e) {
            \Log::error('Error in student show: ' . $e->getMessage());
            return redirect()->route('students.index')
                ->with('error', 'Student not found');
        }
    }
} 