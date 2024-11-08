<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;
use App\Http\Resources\TeacherResource;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Traits\Pagination;

class TeacherController extends Controller
{
    use Pagination;

    /**
     * Display a listing of teachers.
     */
    public function index(Request $request)
    {
        try {
            $pagination = $this->getPaginationParams($request);
            
            $teachers = Teacher::with('departments')
                ->latest()
                ->paginate($pagination['limit'], ['*'], 'page', $pagination['page']);

            return response()->json([
                'status' => 'success',
                'data' => TeacherResource::collection($teachers),
                'meta' => [
                    'total' => $teachers->total(),
                    'per_page' => $teachers->perPage(),
                    'current_page' => $teachers->currentPage(),
                    'last_page' => $teachers->lastPage(),
                    'from' => $teachers->firstItem(),
                    'to' => $teachers->lastItem()
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to fetch teachers: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch teachers'
            ], 500);
        }
    }

    /**
     * Store a newly created teacher.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'address' => 'required|string',
                'phone' => 'nullable|string|max:20',
                'email' => 'required|email|unique:teachers,email',
                'gender' => ['required', Rule::in(['Male', 'Female'])],
                'salary' => 'required|numeric|min:0',
                'department_ids' => 'required|array',
                'department_ids.*' => 'exists:departments,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();
            try {
                // Create teacher with validated data except department_ids
                $teacher = Teacher::create($validator->validated());

                // Attach departments
                if ($request->has('department_ids')) {
                    $teacher->departments()->attach($request->department_ids);
                }

                DB::commit();

                // Load departments for the response
                $teacher->load('departments');

                return response()->json([
                    'status' => 'success',
                    'message' => 'Teacher created successfully',
                    'data' => new TeacherResource($teacher)
                ], 201);

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (\Exception $e) {
            Log::error('Failed to create teacher: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create teacher',
                'details' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Display the specified teacher.
     */
    public function show($id)
    {
        try {
            $teacher = Teacher::with('departments')->findOrFail($id);
            return response()->json([
                'status' => 'success',
                'data' => new TeacherResource($teacher)
            ]);
        } catch (\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Teacher not found'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Failed to fetch teacher: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch teacher details'
            ], 500);
        }
    }

    /**
     * Update the specified teacher.
     */
    public function update(Request $request, $id)
    {
        try {
            $teacher = Teacher::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'first_name' => 'sometimes|required|string|max:255',
                'last_name' => 'sometimes|required|string|max:255',
                'address' => 'sometimes|required|string',
                'phone' => 'nullable|string|max:20',
                'email' => 'sometimes|required|email|unique:teachers,email,' . $id,
                'gender' => ['sometimes', 'required', Rule::in(['Male', 'Female'])],
                'salary' => 'sometimes|required|numeric|min:0',
                'department_ids' => 'sometimes|required|array',
                'department_ids.*' => 'exists:departments,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Begin transaction
            DB::beginTransaction();

            try {
                // Get validated data
                $validatedData = $validator->validated();
                
                // Remove department_ids from the data to be updated
                if (isset($validatedData['department_ids'])) {
                    unset($validatedData['department_ids']);
                }

                // Update teacher details
                $teacher->update($validatedData);

                // Sync departments if provided
                if ($request->has('department_ids')) {
                    $teacher->departments()->sync($request->department_ids);
                }

                DB::commit();

                // Load departments for response
                $teacher->load('departments');

                return response()->json([
                    'status' => 'success',
                    'message' => 'Teacher updated successfully',
                    'data' => new TeacherResource($teacher)
                ]);

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (\ModelNotFoundException $e) {
            Log::error('Teacher not found: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Teacher not found'
            ], 404);

        } catch (\Exception $e) {
            Log::error('Failed to update teacher: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update teacher',
                'details' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Remove the specified teacher.
     */
    public function destroy($id)
    {
        try {
            $teacher = Teacher::findOrFail($id);
            
            // Detach all departments before deleting
            $teacher->departments()->detach();
            $teacher->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Teacher deleted successfully'
            ]);
        } catch (\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Teacher not found'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Failed to delete teacher: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete teacher'
            ], 500);
        }
    }
} 