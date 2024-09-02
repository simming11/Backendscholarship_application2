<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Student;
use App\Models\Academic;

class AuthController extends Controller
{
    // Student login
    public function loginStudent(Request $request)
    {
        $fields = $request->validate([
            'StudentID' => 'required|digits_between:6,10',
            'Password' => 'required|string',
        ]);
    
        // Find the student
        $student = Student::where('StudentID', $fields['StudentID'])->first();
    
        // Check student existence
        if (!$student) {
            return response([
                'message' => 'Invalid Login: Student not found'
            ], 401);
        }
    
        // Check password
        if (!Hash::check($fields['Password'], $student->Password)) {
            return response([
                'message' => 'Invalid Login: Incorrect password'
            ], 401);
        }

        $student->tokens()->delete();

        // Create new token
        $token = $student->createToken($request->userAgent(), ['role' => 'student'])->plainTextToken;
    
        $response = [
            'user' => $student,
            'token' => $token
        ];
    
        return response($response, 200);
    }
    

    // Academic login
    public function loginAcademic(Request $request)
    {
        $fields = $request->validate([
            'AcademicID' => 'required|string',
            'Password' => 'required|string',
        ]);

        // Find the academic
        $academic = Academic::where('AcademicID', $fields['AcademicID'])->first();

        // Check academic existence
        if (!$academic) {
            return response([
                'message' => 'Invalid Login: Academic not found'
            ], 401);
        }

        // Check password
        if (!Hash::check($fields['Password'], $academic->Password)) {
            return response([
                'message' => 'Invalid Login: Incorrect password'
            ], 401);
        }

        // Delete old tokens
        $academic->tokens()->delete();

        // Create new token
        $token = $academic->createToken($request->userAgent(), ['role' => 'academic'])->plainTextToken;

        $response = [
            'user' => $academic,
            'token' => $token
        ];

        return response($response, 200);
    }

    // User logout
    public function logout(Request $request)
    {
        // Delete the current access token
        $request->user()->currentAccessToken()->delete();

        // Response after logout
        return response([
            'message' => 'Logged Out',
        ], 200);
    }
}
