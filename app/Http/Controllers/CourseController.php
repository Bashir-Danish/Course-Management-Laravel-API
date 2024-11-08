<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use App\Http\Resources\CourseResource;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use App\Traits\Pagination;

class CourseController extends Controller
{
    use Pagination;

    /**
     * Display a listing of courses.
     */
    public function index(Request $request)
    {
        try {
            $pagination = $this->getPaginationParams($request);
            
            $courses = Course::with('department')
                ->latest()
                ->paginate($pagination['limit'], ['*'], 'page', $pagination['page']);

            return response()->json([
                'status' => 'success',
                'data' => CourseResource::collection($courses),
                'meta' => [
                    'total' => $courses->total(),
                    'per_page' => $courses->perPage(),
                    'current_page' => $courses->currentPage(),
                    'last_page' => $courses->lastPage(),
                    'from' => $courses->firstItem(),
                    'to' => $courses->lastItem()
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to fetch courses: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch courses'
            ], 500);
        }
    }

    /**
     * Store a newly created course.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'fees' => 'required|numeric|min:0',
                'duration' => 'required|string',
                'department_id' => 'required|exists:departments,id',
                'available_time_slots' => 'required|array'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Check if course with same name exists in the same department
            if (Course::where('name', $request->name)
                    ->where('department_id', $request->department_id)
                    ->exists()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Course with this name already exists in the selected department'
                ], 409);
            }

            $course = Course::create($validator->validated());
            $course->load('department');

            return response()->json([
                'status' => 'success',
                'message' => 'Course created successfully',
                'data' => new CourseResource($course)
            ], 201);

        } catch (\Exception $e) {
            Log::error('Failed to create course: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create course',
                'details' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Display the specified course.
     */
    public function show($id)
    {
        try {
            $course = Course::with('department')->findOrFail($id);
            return response()->json([
                'status' => 'success',
                'data' => new CourseResource($course)
            ]);
        } catch (\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Course not found'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Failed to fetch course: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch course'
            ], 500);
        }
    }

    /**
     * Update the specified course.
     */
    public function update(Request $request, $id)
    {
        try {
            $course = Course::findOrFail($id);

            // Validate all possible fields
            $validator = Validator::make($request->all(), [
                'name' => [
                    'sometimes',
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('courses')->where(function ($query) use ($request, $course) {
                        return $query->where('department_id', $request->department_id ?? $course->department_id)
                                    ->where('id', '!=', $course->id);
                    })
                ],
                'fees' => 'sometimes|required|numeric|min:0',
                'duration' => 'sometimes|required|string|max:50',
                'department_id' => 'sometimes|required|exists:departments,id',
                'available_time_slots' => 'sometimes|required|array',
                'available_time_slots.*.time' => [
                    'required',
                    'string',
                    'regex:/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]-([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/'
                ]
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
                // Update all provided fields
                $course->update($validator->validated());
                
                // Load the department relationship for the response
                $course->load('department');

                DB::commit();

                return response()->json([
                    'status' => 'success',
                    'message' => 'Course updated successfully',
                    'data' => new CourseResource($course)
                ]);

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Course not found'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Failed to update course: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update course',
                'details' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Remove the specified course.
     */
    public function destroy($id)
    {
        try {
            // First check if course exists
            if (!Course::where('id', $id)->exists()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Course not found'
                ], 404);
            }

            $course = Course::findOrFail($id);
            
            // Only check registrations if the model exists
            if (class_exists('App\Models\Registration') && Schema::hasTable('registrations')) {
                if ($course->registrations()->where('status', 'Active')->exists()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Cannot delete course with active registrations'
                    ], 409);
                }
            }

            DB::beginTransaction();
            try {
                $course->delete();
                DB::commit();

                return response()->json([
                    'status' => 'success',
                    'message' => 'Course deleted successfully'
                ]);

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (\Exception $e) {
            Log::error('Failed to delete course: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete course',
                'details' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
} 