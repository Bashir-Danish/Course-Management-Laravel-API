<?php

namespace App\Http\Controllers;


use App\Models\Registration;
use App\Models\Student;
use App\Models\Course;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    public function studentRegistrations($studentId)
    {
        $registrations = Registration::with('course')
            ->where('student_id', $studentId)
            ->latest()
            ->get();

        return view('students.registrations-table', compact('registrations'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'student_id' => 'required|exists:students,id',
                'course_id' => 'required|exists:courses,id',
                'time_slot' => 'required|string',
                'fees_paid' => 'required|numeric|min:0',
                'fees_total' => 'required|numeric|min:0',
                'registration_date' => 'required|date',
                'status' => 'required|in:Unpaid,Paid,Cancelled'
            ]);

            $course = Course::findOrFail($validated['course_id']);
            
            $existingRegistration = Registration::where('student_id', $validated['student_id'])
                ->where('course_id', $validated['course_id'])
                ->where('status', '!=', 'Cancelled')
                ->latest()
                ->first();

            if ($existingRegistration) {
                try {
                    $durationParts = explode(' ', trim(strtolower($course->duration)));
                    if (count($durationParts) !== 2) {
                        \Log::error('Invalid duration format: ' . $course->duration);
                        throw new \Exception('Invalid duration format: ' . $course->duration);
                    }

                    $durationValue = (int) $durationParts[0];
                    $durationType = trim($durationParts[1]); 

                    $methodMap = [
                        'month' => 'addMonths',
                        'months' => 'addMonths',
                        'mounth' => 'addMonths',
                        'mounths' => 'addMonths',
                        'year' => 'addYears',
                        'years' => 'addYears',
                        'day' => 'addDays',
                        'days' => 'addDays',
                        'week' => 'addWeeks',
                        'weeks' => 'addWeeks',
                        'mo' => 'addMonths',
                        'yr' => 'addYears',
                        'wk' => 'addWeeks'
                    ];

                    if (!isset($methodMap[$durationType])) {
                        \Log::error('Unsupported duration type: ' . $durationType);
                        throw new \Exception('Unsupported duration type: "' . $durationType . '". Supported types are: ' . implode(', ', array_keys($methodMap)));
                    }

                    $method = $methodMap[$durationType];
                    
                    $registrationDate = Carbon::parse($existingRegistration->created_at);
                    $expiryDate = $registrationDate->copy()->$method($durationValue);

                    if (Carbon::now()->lt($expiryDate)) {
                        return response()->json([
                            'success' => false,
                            'message' => sprintf(
                                'Student is already enrolled in this course. Current registration expires on %s (Duration: %s)',
                                $expiryDate->format('Y-m-d'),
                                $course->duration
                            )
                        ], 422);
                    }
                } catch (\Exception $e) {
                    \Log::error('Duration calculation error: ' . $e->getMessage());
                    return response()->json([
                        'success' => false,
                        'message' => 'Error validating course duration. Please contact administrator.',
                        'debug_message' => $e->getMessage()
                    ], 422);
                }
            }

            $validated['time_slot'] = json_encode([$validated['time_slot']]);
            $registration = Registration::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Registration created successfully',
                'registration' => $registration
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Registration error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Error creating registration',
                'debug_message' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $registration = Registration::findOrFail($id);
            
            $validated = $request->validate([
                'course_id' => 'required|exists:courses,id',
                'time_slot' => 'required|string',
                'fees_total' => 'required|numeric|min:0',
                'fees_paid' => 'nullable|numeric|min:0'
            ]);

            $validated['time_slot'] = json_encode([$validated['time_slot']]);

            if (isset($validated['fees_paid'])) {
                $validated['fees_paid'] = $registration->fees_paid + $validated['fees_paid'];
       
                $validated['status'] = $validated['fees_paid'] >= $validated['fees_total'] ? 'Paid' : 'Unpaid';
            }

            $registration->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Registration updated successfully',
                'registration' => $registration
            ]);
        } catch (\Exception $e) {
            \Log::error('Registration update error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating registration'
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $registration = Registration::findOrFail($id);
            $registration->delete();

            return response()->json([
                'success' => true,
                'message' => 'Registration deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
} 