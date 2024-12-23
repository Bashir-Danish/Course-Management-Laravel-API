<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Models\Student;
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
            return response()->json([
                'success' => false,
                'message' => 'Error creating registration'
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