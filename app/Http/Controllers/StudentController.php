<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use App\Http\Resources\StudentResource;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Traits\Pagination;

class StudentController extends Controller
{
    use Pagination;

    /**
     * Display a listing of students.
     */
    public function index(Request $request)
    {
        try {
            $pagination = $this->getPaginationParams($request);
            
            $students = Student::latest()
                ->paginate($pagination['limit'], ['*'], 'page', $pagination['page']);

            return response()->json([
                'status' => 'success',
                'data' => StudentResource::collection($students),
                'meta' => [
                    'total' => $students->total(),
                    'per_page' => $students->perPage(),
                    'current_page' => $students->currentPage(),
                    'last_page' => $students->lastPage(),
                    'from' => $students->firstItem(),
                    'to' => $students->lastItem()
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to fetch students: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch students'
            ], 500);
        }
    }

    /**
     * Store a newly created student.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'gender' => ['required', Rule::in(['Male', 'Female'])],
                'address' => 'nullable|string',
                'phone' => 'nullable|string|max:20',
                'dob' => 'required|date|before:today'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $student = Student::create($validator->validated());

            return response()->json([
                'status' => 'success',
                'message' => 'Student created successfully',
                'data' => new StudentResource($student)
            ], 201);

        } catch (\Exception $e) {
            Log::error('Failed to create student: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create student',
                'details' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Display the specified student.
     */
    public function show($id)
    {
        try {
            $student = Student::findOrFail($id);
            return response()->json([
                'status' => 'success',
                'data' => new StudentResource($student)
            ]);
        } catch (\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Student not found'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Failed to fetch student: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch student'
            ], 500);
        }
    }

    /**
     * Update the specified student.
     */
    public function update(Request $request, $id)
    {
        try {
            $student = Student::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'first_name' => 'sometimes|required|string|max:255',
                'last_name' => 'sometimes|required|string|max:255',
                'gender' => ['sometimes', 'required', Rule::in(['Male', 'Female'])],
                'address' => 'nullable|string',
                'phone' => 'nullable|string|max:20',
                'dob' => 'sometimes|required|date|before:today'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $student->update($validator->validated());

            return response()->json([
                'status' => 'success',
                'message' => 'Student updated successfully',
                'data' => new StudentResource($student)
            ]);

        } catch (\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Student not found'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Failed to update student: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update student',
                'details' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Remove the specified student.
     */
    public function destroy($id)
    {
        try {
            if (!Student::where('id', $id)->exists()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Student not found'
                ], 404);
            }

            $student = Student::findOrFail($id);
            
            // Check for active registrations if Registration model exists
            if (class_exists('App\Models\Registration') && Schema::hasTable('registrations')) {
                if ($student->registrations()->where('status', 'Active')->exists()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Cannot delete student with active registrations'
                    ], 409);
                }
            }

            DB::beginTransaction();
            try {
                $student->delete();
                DB::commit();

                return response()->json([
                    'status' => 'success',
                    'message' => 'Student deleted successfully'
                ]);

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (\Exception $e) {
            Log::error('Failed to delete student: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete student',
                'details' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
} 