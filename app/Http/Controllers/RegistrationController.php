<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Models\Course;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Http\Resources\RegistrationResource;
use App\Http\Resources\StudentResource;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class RegistrationController extends Controller
{
    /**
     * Create a new registration
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'course_id' => 'required|exists:courses,id',
                'student_id' => 'required|exists:students,id',
                'registration_date' => 'required|date',
                'fees_paid' => 'required|numeric|min:0',
                'time_slot' => 'required|array',
                'time_slot.time' => [
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

            // Get course details
            $course = Course::findOrFail($request->course_id);

            DB::beginTransaction();
            try {
                $registration = Registration::create([
                    'course_id' => $request->course_id,
                    'student_id' => $request->student_id,
                    'registration_date' => $request->registration_date,
                    'fees_total' => $course->fees,
                    'fees_paid' => $request->fees_paid,
                    'time_slot' => $request->time_slot,
                    'status' => 'Active'
                ]);

                $registration->load(['course', 'student']);
                DB::commit();

                return response()->json([
                    'status' => 'success',
                    'message' => 'Registration created successfully',
                    'data' => new RegistrationResource($registration)
                ], 201);

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (\Exception $e) {
            Log::error('Failed to create registration: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create registration',
                'details' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Get student registrations with balance details
     */
    public function getStudentRegistrations($studentId)
    {
        try {
            $student = Student::with(['registrations.course'])->findOrFail($studentId);
            
            $registrations = $student->registrations->map(function ($registration) {
                return [
                    'registration' => new RegistrationResource($registration),
                    'balance' => $registration->fees_total - $registration->fees_paid
                ];
            });

            $totalBalance = $student->registrations->sum(function ($registration) {
                return $registration->fees_total - $registration->fees_paid;
            });

            return response()->json([
                'status' => 'success',
                'data' => [
                    'student' => new StudentResource($student),
                    'registrations' => $registrations,
                    'total_balance' => $totalBalance
                ]
            ]);

        } catch (\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Student not found'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Failed to fetch student registrations: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch registrations'
            ], 500);
        }
    }

    /**
     * Update registration
     */
    public function update(Request $request, $id)
    {
        try {
            $registration = Registration::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'fees_paid' => [
                    'sometimes',
                    'required',
                    'numeric',
                    'min:0',
                    function ($attribute, $value, $fail) use ($registration) {
                        $newTotal = $registration->fees_paid + $value;
                        if ($newTotal > $registration->fees_total) {
                            $fail('The new payment would exceed the total fees amount.');
                        }
                    }
                ],
                'status' => 'sometimes|required|in:Active,Completed,Cancelled',
                'time_slot' => 'sometimes|required|array',
                'time_slot.time' => [
                    'required_with:time_slot',
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
                if ($request->has('fees_paid')) {
                    // Add new payment to existing fees_paid
                    $registration->fees_paid = $registration->fees_paid + $request->fees_paid;
                }

                // Update other fields if provided
                if ($request->has('status')) {
                    $registration->status = $request->status;
                }
                if ($request->has('time_slot')) {
                    $registration->time_slot = $request->time_slot;
                }

                // If payment is complete, update status
                if ($registration->fees_paid >= $registration->fees_total) {
                    $registration->status = 'Completed';
                }

                $registration->save();
                $registration->load(['course', 'student']);
                DB::commit();

                return response()->json([
                    'status' => 'success',
                    'message' => 'Registration updated successfully',
                    'data' => new RegistrationResource($registration),
                    'payment_details' => [
                        'fees_total' => $registration->fees_total,
                        'previous_payment' => $registration->fees_paid - ($request->fees_paid ?? 0),
                        'new_payment' => $request->fees_paid ?? 0,
                        'total_paid' => $registration->fees_paid,
                        'fees_remaining' => $registration->fees_total - $registration->fees_paid
                    ]
                ]);

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Registration not found'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Failed to update registration: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update registration',
                'details' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Delete registration
     */
    public function destroy($id)
    {
        try {
            // Check if registration exists
            if (!Registration::where('id', $id)->exists()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Registration not found'
                ], 404);
            }

            $registration = Registration::findOrFail($id);

            DB::beginTransaction();
            try {
                $registration->delete();
                DB::commit();

                return response()->json([
                    'status' => 'success',
                    'message' => 'Registration deleted successfully'
                ]);

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (\Exception $e) {
            Log::error('Failed to delete registration: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete registration',
                'details' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
} 