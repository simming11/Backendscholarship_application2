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


    public function getByAcademicID($academicID)
{
    // ค้นหา LineNotify ตาม AcademicID
    $lineNotifies = LineNotify::where('AcademicID', $academicID)->get();

    // ตรวจสอบว่าพบข้อมูลหรือไม่
    if ($lineNotifies->isEmpty()) {
        return response()->json(['message' => 'No LineNotify found for this AcademicID'], 404);
    }

    return response()->json($lineNotifies);
}

    

    public function store(Request $request)
    {
        // ตรวจสอบค่าอื่นๆ ยกเว้น LineToken
        $validator = Validator::make($request->all(), [
            'SentDate' => 'date_format:Y-m-d',
            'notify_client_id' => 'required|string',
            'client_secret' => 'required|string',
            'AcademicID' => 'required|exists:academics,AcademicID',
        ]);
    
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
    
        // สร้าง LineNotify object และบันทึกลงฐานข้อมูล โดยไม่มี LineToken
        $lineNotify = new LineNotify([
            'SentDate' => $request->get('SentDate'),
            'notify_client_id' => $request->get('notify_client_id'),
            'client_secret' => $request->get('client_secret'),
            'AcademicID' => $request->get('AcademicID'),
        ]);
    
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

    public function updateByAcademicID(Request $request, $academicID)
    {
        $validator = Validator::make($request->all(), [
            'LineToken' => 'nullable|string',
            'notify_client_id' => 'nullable|string',
            'client_secret' => 'nullable|string',
        ]);
    
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
    
        // ค้นหา LineNotify ตาม AcademicID
        $lineNotify = LineNotify::where('AcademicID', $academicID)->first();
    
        if ($lineNotify) {
            // อัปเดตฟิลด์ต่าง ๆ ถ้ามีการส่งค่ามา
            if ($request->has('LineToken')) {
                $lineNotify->LineToken = $request->get('LineToken');
            }
            $lineNotify->notify_client_id = $request->get('notify_client_id', $lineNotify->notify_client_id);
            $lineNotify->client_secret = $request->get('client_secret', $lineNotify->client_secret);
    
            // บันทึกการเปลี่ยนแปลง
            $lineNotify->save();
    
            return response()->json($lineNotify);
        } else {
            return response()->json(['message' => 'Line Notify not found for this AcademicID'], 404);
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
     * ส่งข้อความไปยัง LINE Notify
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
