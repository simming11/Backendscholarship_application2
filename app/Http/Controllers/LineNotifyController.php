<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LineNotify;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class LineNotifyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $lineNotifies = LineNotify::all();
        return response()->json($lineNotifies);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'StudentID' => 'required|exists:students,StudentID',
            'LineToken' => 'required|string',
            'SentDate' => 'required|date_format:Y-m-d',
            'Message' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $lineNotify = new LineNotify([
            'StudentID' => $request->get('StudentID'),
            'LineToken' => $request->get('LineToken'),
            'SentDate' => $request->get('SentDate'),
        ]);

        // Send notification to Line
        $response = $this->sendLineNotification($request->get('LineToken'), $request->get('Message'));

        // Check if response is successful
        if ($response->status() !== 200) {
            return response()->json(['message' => 'Failed to send notification to Line', 'error' => $response->body()], $response->status());
        }

        $lineNotify->save();

        return response()->json($lineNotify, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $lineNotify = LineNotify::find($id);
        if ($lineNotify) {
            return response()->json($lineNotify);
        } else {
            return response()->json(['message' => 'Line Notify not found'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'StudentID' => 'required|exists:students,StudentID',
            'LineToken' => 'required|string',
            'SentDate' => 'required|date_format:Y-m-d',
            'Message' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $lineNotify = LineNotify::find($id);
        if ($lineNotify) {
            $lineNotify->StudentID = $request->get('StudentID', $lineNotify->StudentID);
            $lineNotify->LineToken = $request->get('LineToken', $lineNotify->LineToken);
            $lineNotify->SentDate = $request->get('SentDate', $lineNotify->SentDate);

            // Send notification to Line
            $response = $this->sendLineNotification($request->get('LineToken'), $request->get('Message'));

            // Check if response is successful
            if ($response->status() !== 200) {
                return response()->json(['message' => 'Failed to send notification to Line', 'error' => $response->body()], $response->status());
            }

            $lineNotify->save();

            return response()->json($lineNotify);
        } else {
            return response()->json(['message' => 'Line Notify not found'], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $lineNotify = LineNotify::find($id);
        if ($lineNotify) {
            $lineNotify->delete();
            return response()->json(['message' => 'Line Notify deleted']);
        } else {
            return response()->json(['message' => 'Line Notify not found'], 404);
        }
    }

    /**
     * Send notification to Line
     */
    private function sendLineNotification($token, $message)
    {
        Log::info('Message before sending to Line', ['message' => $message]);

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->asForm()->post('https://notify-api.line.me/api/notify', [
            'message' => $message,
        ]);

        Log::info('Line Notify Response', ['status' => $response->status(), 'body' => $response->body()]);

        return $response;
    }
}

