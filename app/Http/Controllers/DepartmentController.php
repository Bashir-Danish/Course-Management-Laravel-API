<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use App\Http\Resources\DepartmentResource;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Schema;

class DepartmentController extends Controller
{
    /**
     * Display a listing of departments.
     */
    public function index()
    {
        try {
            $departments = Department::latest()->paginate(10);
            return response()->json([
                'status' => 'success',
                'data' => DepartmentResource::collection($departments),
                'meta' => [
                    'total' => $departments->total(),
                    'per_page' => $departments->perPage(),
                    'current_page' => $departments->currentPage(),
                    'last_page' => $departments->lastPage(),
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to fetch departments: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch departments'
            ], 500);
        }
    }

    /**
     * Store a newly created department.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'description' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Check if department with same name already exists
            if (Department::where('name', $request->name)->exists()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Department with this name already exists'
                ], 409);
            }

            $department = Department::create($validator->validated());

            return response()->json([
                'status' => 'success',
                'message' => 'Department created successfully',
                'data' => new DepartmentResource($department)
            ], 201);
        } catch (\Exception $e) {
            Log::error('Failed to create department: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create department'
            ], 500);
        }
    }

    /**
     * Display the specified department.
     */
    public function show($id)
    {
        try {
            $department = Department::findOrFail($id);
            return response()->json([
                'status' => 'success',
                'data' => new DepartmentResource($department)
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to fetch department: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Department not found'
            ], 404);
        }
    }

    /**
     * Update the specified department.
     */
    public function update(Request $request, $id)
    {
        try {
            $department = Department::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|required|string|max:255',
                'description' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Check if department with same name already exists (excluding current department)
            if (Department::where('name', $request->name)
                ->where('id', '!=', $id)
                ->exists()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Department with this name already exists'
                ], 409);
            }

            $department->update($validator->validated());

            return response()->json([
                'status' => 'success',
                'message' => 'Department updated successfully',
                'data' => new DepartmentResource($department)
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to update department: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update department'
            ], 500);
        }
    }

    /**
     * Remove the specified department.
     */
    public function destroy($id)
    {
        try {
            $department = Department::findOrFail($id);
            
            // Check if department has associated courses (if courses table exists)
            if (Schema::hasTable('courses')) {
                if ($department->courses()->exists()) {
                    Log::warning("Cannot delete department ID {$id} - Has associated courses");
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Cannot delete department with associated courses',
                        'details' => 'Please remove or reassign all courses before deleting this department'
                    ], 409);
                }
            }

            // Only check for teachers if the pivot table exists
            if (Schema::hasTable('department_teacher')) {
                if ($department->teachers()->exists()) {
                    Log::warning("Cannot delete department ID {$id} - Has associated teachers");
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Cannot delete department with associated teachers',
                        'details' => 'Please remove or reassign all teachers before deleting this department'
                    ], 409);
                }
            }

            // If no dependencies exist, proceed with deletion
            $department->delete();
            Log::info("Department ID {$id} deleted successfully");

            return response()->json([
                'status' => 'success',
                'message' => 'Department deleted successfully'
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error("Department not found with ID: {$id}");
            return response()->json([
                'status' => 'error',
                'message' => 'Department not found'
            ], 404);

        } catch (\Exception $e) {
            Log::error("Error deleting department {$id}: " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete department'
            ], 500);
        }
    }
}