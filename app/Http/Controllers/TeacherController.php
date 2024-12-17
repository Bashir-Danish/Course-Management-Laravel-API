<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        $query = Teacher::with('departments')->oldest();
        
        if ($request->has('search') && strlen($request->search) >= 3) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('first_name', 'like', "%{$searchTerm}%")
                  ->orWhere('last_name', 'like', "%{$searchTerm}%")
                  ->orWhere('email', 'like', "%{$searchTerm}%")
                  ->orWhere('phone', 'like', "%{$searchTerm}%")
                  ->orWhere('salary', 'like', "%{$searchTerm}%");
            });
            $teachers = $query->get();
            $isPaginated = false;
        } else {
            $teachers = $query->paginate(10);
            $isPaginated = true;
        }
        
        if ($request->ajax()) {
            return view('teachers.table', compact('teachers', 'isPaginated'))->render();
        }
        
        return view('List-Of-Teachers', compact('teachers', 'isPaginated'));
    }

    public function create()
    {
        $departments = Department::all();
        return view('Add-Teacher', compact('departments'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'first_name' => 'required|string|min:2|max:50',
                'last_name' => 'required|string|min:2|max:50',
                'email' => [
                    'required',
                    'email',
                    Rule::unique('teachers', 'email'),
                ],
                'phone' => 'required|string|min:10|max:15',
                'address' => 'required|string|min:5|max:200',
                'department_ids' => 'required|array|min:1',
                'department_ids.*' => 'exists:departments,id',
                'gender' => 'required|in:male,female',
                'salary' => 'required|numeric|min:0',
            ]);

            \Log::info('Validated data:', $validated);

            $teacher = Teacher::create([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'gender' => $validated['gender'],
                'salary' => $validated['salary'],
            ]);

            \Log::info('Teacher created:', $teacher->toArray());

            $teacher->departments()->attach($validated['department_ids']);

            \Log::info('Departments attached');

            return response()->json([
                'success' => true,
                'message' => 'Teacher created successfully',
                'teacher' => $teacher
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation error:', $e->errors());
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error creating teacher: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the teacher'
            ], 500);
        }
    }

    public function edit($id)
    {
        $teacher = Teacher::with('departments')->findOrFail($id);
        $departments = Department::all();
        return view('Add-Teacher', compact('teacher', 'departments'));
    }

    public function update(Request $request, $id)
    {
        try {
            $teacher = Teacher::findOrFail($id);
            
            $validated = $request->validate([
                'first_name' => 'required|string|min:2|max:50',
                'last_name' => 'required|string|min:2|max:50',
                'email' => [
                    'required',
                    'email',
                    Rule::unique('teachers')->ignore($teacher->id),
                ],
                'phone' => 'required|string|min:10|max:15',
                'address' => 'required|string|min:5|max:200',
                'department_ids' => 'required|array|min:1',
                'department_ids.*' => 'exists:departments,id',
                'gender' => 'required|in:male,female',
                'salary' => 'required|numeric|min:0',
            ]);

            \Log::info('Update validated data:', $validated);

            $teacher->update([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'gender' => $validated['gender'],
                'salary' => $validated['salary'],
            ]);

            \Log::info('Teacher updated:', $teacher->toArray());

            // Sync departments
            $teacher->departments()->sync($validated['department_ids']);

            \Log::info('Departments synced');

            return response()->json([
                'success' => true,
                'message' => 'Teacher updated successfully',
                'teacher' => $teacher->load('departments')
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Update validation error:', $e->errors());
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error updating teacher: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the teacher'
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $teacher = Teacher::findOrFail($id);
            $teacher->delete();

            return response()->json([
                'success' => true,
                'message' => 'Teacher deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting teacher'
            ], 500);
        }
    }
} 