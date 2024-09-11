<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    // Display a listing of students
    public function index()
    {
        $students = Student::all();
        return response()->json($students);
    }

    public function store(Request $request)
    {
        // Custom validation rule for checking English characters only
        $englishOnlyRule = function ($attribute, $value, $fail) {
            if (!preg_match('/^[\p{L}\p{N}\p{P}\p{Z}]+$/u', $value)) {
                $fail('ช่อง ' . $attribute . ' ต้องประกอบด้วยตัวอักษรภาษาอังกฤษเท่านั้น');
            }
        };
    
        // Validate the request data
        $validatedData = $request->validate([
            'StudentID' => [
                'required', 
                'string', 
                'max:255', 
                'unique:students,StudentID', // Ensure StudentID is unique
                $englishOnlyRule
            ],
            'Password' => ['required', 'string', 'min:8', $englishOnlyRule], // Apply the rule here as well
            'FirstName' => ['required', 'string', 'max:255'],
            'LastName' => ['required', 'string', 'max:255'],
            'Email' => ['required', 'string', 'email', 'max:255', 'unique:students,Email', $englishOnlyRule],
            'GPA' => ['required', 'numeric', 'between:0,4.0'],
            'Year_Entry' => ['required', 'integer'],
            'Religion' => ['required', 'string', 'max:255'],
            'PrefixName' => ['required', 'string', 'max:255', $englishOnlyRule],
            'Phone' => ['required', 'string', 'max:15', $englishOnlyRule],
            'DOB' => ['required', 'date_format:d/m/Y'], // Validate for "วัน/เดือน/ปี" format
            'Course' => ['required', 'string', 'max:255'],
        ], [
            // Custom error messages
            'required' => 'กรุณากรอก :attribute',
            'string' => ':attribute ต้องเป็นข้อความ',
            'max' => ':attribute ต้องไม่เกิน :max ตัวอักษร',
            'unique' => ':attribute นี้ถูกใช้งานแล้ว', // Error message if StudentID or Email is not unique
            'email' => ':attribute ต้องเป็นอีเมลที่ถูกต้อง',
            'numeric' => ':attribute ต้องเป็นตัวเลข',
            'between' => ':attribute ต้องมีค่าระหว่าง :min ถึง :max',
            'integer' => ':attribute ต้องเป็นจำนวนเต็ม',
            'min' => ':attribute ต้องมีอย่างน้อย :min ตัวอักษร',
            'date_format' => ':attribute ต้องอยู่ในรูปแบบ d/m/Y', // Error for incorrect date format
        ]);
    
        // Encrypt the password before saving
        $validatedData['Password'] = bcrypt($validatedData['Password']);
    
        // Convert the "วัน/เดือน/ปี" format from the Buddhist year to the Gregorian year
        $dobInBuddhistYear = $validatedData['DOB'];
        $dobInGregorianYear = Carbon::createFromFormat('d/m/Y', $dobInBuddhistYear)
            ->subYears(543) // Convert from Buddhist year to Gregorian year
            ->toDateString();
    
        // Replace the DOB with the converted date
        $validatedData['DOB'] = $dobInGregorianYear;
    
        // Create the student record
        $student = Student::create($validatedData);
    
        // Create a token for the student
        $token = $student->createToken($request->userAgent(), ['role' => 'student'])->plainTextToken;
    
        // Log in the student by returning the token and user details
        $response = [
            'user' => $student,
            'token' => $token,
        ];
    
        return response()->json($response, 201); // 201 Created
    }



    // Display the specified student
    public function show($id)
    {
        $student = Student::findOrFail($id);
        return response()->json($student);
    }

    // Update the specified student in the database
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'Password' => 'sometimes|required|string|min:8',
            'FirstName' => 'sometimes|required|string|max:255',
            'LastName' => 'sometimes|required|string|max:255',
            'Email' => 'sometimes|required|string|email|max:255|unique:students,Email,' . $id . ',StudentID',
            'GPA' => 'sometimes|required|numeric|between:0,4.0',
            'Year_Entry' => 'sometimes|required|integer',
            'Religion' => 'sometimes|required|string|max:255',
            'PrefixName' => 'sometimes|required|string|max:255',
            'Phone' => 'sometimes|required|string|max:15',
            'DOB' => 'sometimes|required|date',
            'Course' => 'sometimes|required|string|max:255',
        ]);

        $student = Student::findOrFail($id);
        $student->update($validatedData);

        return response()->json($student);
    }

    // Remove the specified student from the database
    public function destroy($id)
    {
        $student = Student::findOrFail($id);
        $student->delete();

        return response()->json(null, 204); // 204 No Content
    }
}
