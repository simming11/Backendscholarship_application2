<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    // Display a listing of reports
    public function index()
    {
        $reports = Report::all();
        return response()->json($reports);
    }

    // Store a newly created report in the database
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'ReportContent' => 'required|string',
            'CreatedDate' => 'required|date',
            'ApplicationID' => 'nullable|string|max:15|exists:application_internals,ApplicationID',
            'Application_EtID' => 'nullable|string|max:15|exists:applications_external,Application_EtID',
        ]);

        $report = Report::create($validatedData);

        return response()->json($report, 201); // 201 Created
    }

    // Display the specified report
    public function show($id)
    {
        $report = Report::findOrFail($id);
        return response()->json($report);
    }

    // Update the specified report in the database
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'ReportContent' => 'sometimes|required|string',
            'CreatedDate' => 'sometimes|required|date',
            'ApplicationID' => 'nullable|string|max:15|exists:application_internals,ApplicationID',
            'Application_EtID' => 'nullable|string|max:15|exists:applications_external,Application_EtID',
        ]);

        $report = Report::findOrFail($id);
        $report->update($validatedData);

        return response()->json($report);
    }

    // Remove the specified report from the database
    public function destroy($id)
    {
        $report = Report::findOrFail($id);
        $report->delete();

        return response()->json(null, 204); // 204 No Content
    }
}
