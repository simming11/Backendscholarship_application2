<?php

namespace App\Http\Controllers;

use App\Models\ApplicationInternal;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use PDF;
use TCPDF;

class ApplicationInternalController extends Controller
{
    // Display a listing of the application internals
    public function index()
    {
        $applications = ApplicationInternal::with([
            'student',
            'scholarship',
            'applicationFiles',
            'addresses',
            'siblings',
            'scholarshipHistories',
            'guardians',
            'activities',
            'workExperiences',
        ])->get();

        return response()->json($applications);
    }

    public function generatePdf($id)
    {
        $application = ApplicationInternal::with([
            'student',
            'scholarship',
            'applicationFiles',
            'addresses',
            'siblings',
            'scholarshipHistories',
            'guardians',
            'activities',
            'workExperiences',
        ])->findOrFail($id);
    
        // ตรวจสอบว่าแต่ละคอลเลกชันไม่เป็น null หรือ empty
        $application->addresses = $application->addresses ?? collect();
        $application->siblings = $application->siblings ?? collect();
        $application->guardians = $application->guardians ?? collect();
        $application->scholarship_histories = $application->scholarship_histories ?? collect();
        $application->activities = $application->activities ?? collect();
        $application->workExperiences = $application->workExperiences ?? collect();
    
        $pdf = new TCPDF();
        $pdf->SetCreator('Laravel');
        $pdf->SetAuthor('Your Name');
        $pdf->SetTitle('Application Report');
        $pdf->AddPage();
        $pdf->SetFont('thsarabunnew', '', 14); 
        $html = view('pdf_template', ['application' => $application])->render();
        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->Output('application_report.pdf', 'I');
    }
    
    
    
    
    


// Store a newly created application internal in the database
public function store(Request $request)
{
    // Validate the request data
    $validatedData = $request->validate([
        'StudentID' => 'required|string|max:10|exists:students,StudentID',
        'ScholarshipID' => 'required|integer|exists:scholarships,ScholarshipID',
        'Status' => 'required|string|max:255',
        'AdvisorName' => 'string|max:20',
        'MonthlyIncome' => 'numeric',
        'GPAYear1' => 'required|numeric',
        'GPAYear2' => 'required|numeric',
        'GPAYear3' => 'required|numeric',
        'MonthlyExpenses' => 'numeric',
        'NumberOfSiblings' => 'integer',
        'NumberOfSisters' => 'integer',
        'NumberOfBrothers' => 'integer',
    ]);

    // Generate the ApplicationDate in the Thai format (Buddhist Era year)
    $currentDate = now(); // Get the current date and time
    $thaiYear = $currentDate->year + 543; // Convert Gregorian year to Buddhist Era
    $thaiDate = $currentDate->format("Y-m-d H:i:s");
    $thaiDate = str_replace($currentDate->year, $thaiYear, $thaiDate); // Replace the year with the Thai year

    $validatedData['ApplicationDate'] = $thaiDate;

    // Create the application internal record
    $application = ApplicationInternal::create($validatedData);

    return response()->json($application, 201); // 201 Created
}


    // Display the specified application internal based on StudentID
    public function showByStudentId($studentId)
    {
        $applications = ApplicationInternal::with([
            'student',
            'scholarship',
            'applicationFiles',
            'addresses',
            'siblings',
            'scholarshipHistories',
            'guardians',
            'activities',
            'workExperiences',
        ])
        ->where('StudentID', $studentId) // Filter by StudentID
        ->get(); // Get all results that match the StudentID
    
        return response()->json($applications);
    }
    



    // Display the specified application internal
    public function show($id)
    {
        $application = ApplicationInternal::with([
            'student',
            'scholarship',
            'applicationFiles',
            'addresses',
            'siblings',
            'scholarshipHistories',
            'guardians',
            'activities',
            'workExperiences',
        ])->findOrFail($id);

        return response()->json($application);
    }

    // Update the specified application internal in the database
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'StudentID' => 'required|string|max:10|exists:students,StudentID',
            'ScholarshipID' => 'required|integer|exists:scholarships,ScholarshipID',
            'ApplicationDate' => 'required|date',
            'Status' => 'required|string|max:255',
            'AdvisorName' => 'required|string|max:20',
            'MonthlyIncome' => 'required|numeric',
            'GPAYear1' => 'required|numeric',
            'GPAYear2' => 'required|numeric',
            'GPAYear3' => 'required|numeric',
            'MonthlyExpenses' => 'required|numeric',
            'NumberOfSiblings' => 'required|integer',
            'NumberOfSisters' => 'required|integer',
            'NumberOfBrothers' => 'required|integer',
        ]);

        $application = ApplicationInternal::findOrFail($id);
        $application->update($validatedData);

        return response()->json($application);
    }

    // Remove the specified application internal from the database
    public function destroy($id)
    {
        $application = ApplicationInternal::findOrFail($id);
        $application->delete();

        return response()->json(null, 204); // 204 No Content
    }
}
