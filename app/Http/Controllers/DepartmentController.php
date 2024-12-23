<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Department::oldest();
        
        if ($request->has('search') && strlen($request->search) >= 3) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%");
            });
            // without pagination when searching
            $departments = $query->get();
            $isPaginated = false;
        } else {
            $departments = $query->paginate(10);
            $isPaginated = true;
        }
        
        if ($request->ajax()) {
            return view('departments.table', compact('departments', 'isPaginated'))->render();
        }
        
        return view('List-Of-Departments', compact('departments', 'isPaginated'));
    }

    public function create()
    {
        return view('Add-Department');
    }

    public function edit($id)
    {
        $department = Department::findOrFail($id);
        return view('Add-Department', compact('department'));
    }

    public function destroy($id)
    {
        try {
            $department = Department::findOrFail($id);
            $department->delete();

            return response()->json([
                'success' => true,
                'message' => 'Department deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting department'
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => [
                    'required',
                    'string',
                    'min:2',
                    'max:50',
                    Rule::unique('departments', 'name'),
                ],
                'description' => 'required|string|min:4|max:500',
            ], [
                'name.unique' => 'This Department name already exists.',
            ]);

            $department = Department::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Department created successfully',
                'department' => $department
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the department'
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $department = Department::findOrFail($id);
            
            $validated = $request->validate([
                'name' => [
                    'required',
                    'string',
                    'min:2',
                    'max:50',
                    Rule::unique('departments', 'name')->ignore($department->id),
                ],
                'description' => 'required|string|min:4|max:500',
            ], [
                'name.unique' => 'This Department name already exists.',
            ]);

            $department->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Department updated successfully',
                'department' => $department
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the department'
            ], 500);
        }
    }
}