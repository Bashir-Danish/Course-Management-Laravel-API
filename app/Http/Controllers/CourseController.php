<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::with('department')->oldest();
        
        if ($request->has('search') && strlen($request->search) >= 3) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('fees', 'like', "%{$searchTerm}%")
                  ->orWhere('duration', 'like', "%{$searchTerm}%")
                  ->orWhereHas('department', function($q) use ($searchTerm) {
                      $q->where('name', 'like', "%{$searchTerm}%");
                  });
            });
            $courses = $query->get();
            $isPaginated = false;
        } else {
            $courses = $query->paginate(10)->withQueryString();
            $isPaginated = true;
        }
        
        $courses->each(function($course) {
            \Log::info('Course time slots data:', [
                'course_id' => $course->id,
                'course_name' => $course->name,
                'raw_time_slots' => $course->available_time_slots,
                'decoded_time_slots' => is_string($course->available_time_slots) 
                    ? json_decode($course->available_time_slots, true) 
                    : $course->available_time_slots
            ]);
        });
        
        if ($request->ajax()) {
            return view('courses.table', compact('courses', 'isPaginated'))->render();
        }
        
        return view('List-Of-Courses', compact('courses', 'isPaginated'));
    }

    public function create()
    {
        $departments = Department::all();
        return view('Add-Course', compact('departments'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => [
                    'required',
                    'string',
                    'min:2',
                    'max:100',
                    Rule::unique('courses', 'name'),
                ],
                'fees' => 'required|numeric|min:0',
                'duration' => 'required|string|max:50',
                'department_id' => 'required|exists:departments,id',
                'available_time_slots' => 'required|array|min:1',
            ]);

            $course = Course::create([
                'name' => $validated['name'],
                'fees' => $validated['fees'],
                'duration' => $validated['duration'],
                'department_id' => $validated['department_id'],
                'available_time_slots' => json_encode($validated['available_time_slots'], JSON_UNESCAPED_SLASHES),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Course created successfully',
                'course' => $course
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        $course = Course::findOrFail($id);
        $departments = Department::all();
        return view('Add-Course', compact('course', 'departments'));
    }

    public function update(Request $request, $id)
    {
        try {
            $course = Course::findOrFail($id);
            
            $validated = $request->validate([
                'name' => [
                    'required',
                    'string',
                    'min:2',
                    'max:100',
                    Rule::unique('courses')->ignore($course->id),
                ],
                'fees' => 'required|numeric|min:0',
                'duration' => 'required|string|max:50',
                'department_id' => 'required|exists:departments,id',
                'available_time_slots' => 'required|array|min:1',
            ]);

            $course->update([
                'name' => $validated['name'],
                'fees' => $validated['fees'],
                'duration' => $validated['duration'],
                'department_id' => $validated['department_id'],
                'available_time_slots' => json_encode($validated['available_time_slots'], JSON_UNESCAPED_SLASHES),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Course updated successfully',
                'course' => $course
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $course = Course::findOrFail($id);
            $course->delete();

            return response()->json([
                'success' => true,
                'message' => 'Course deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting course'
            ], 500);
        }
    }

    public function list()
    {
        try {
            $courses = Course::select('id', 'name', 'fees', 'available_time_slots', 'department_id')
                ->with('department')
                ->orderBy('name')
                ->get()
                ->map(function($course) {
                    $timeSlots = $course->available_time_slots;
                    if (is_string($timeSlots)) {
                        $timeSlots = json_decode($timeSlots, true);
                    }
                    $timeSlots = is_array($timeSlots) ? $timeSlots : [];
                    
                    return [
                        'id' => $course->id,
                        'name' => $course->name,
                        'fees' => $course->fees,
                        'available_time_slots' => json_encode($timeSlots),
                        'department' => $course->department->name ?? ''
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $courses
            ]);
        } catch (\Exception $e) {
            \Log::error('Course list error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to load courses'
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $course = Course::with('department')->findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => $course
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Course not found'
            ], 404);
        }
    }
} 