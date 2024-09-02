<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // ดึงข้อมูลการแจ้งเตือนทั้งหมด
        $notifications = Notification::all();
        return response()->json($notifications);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // ตรวจสอบและบันทึกข้อมูลการแจ้งเตือนใหม่
        $request->validate([
            'StudentID' => 'required|exists:students,StudentID',
            'Message' => 'required|string',
            'SentDate' => 'required|date',
        ]);

        $notification = new Notification([
            'StudentID' => $request->get('StudentID'),
            'Message' => $request->get('Message'),
            'SentDate' => $request->get('SentDate'),
        ]);

        $notification->save();

        return response()->json($notification, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // แสดงข้อมูลการแจ้งเตือนตาม ID
        $notification = Notification::find($id);
        if ($notification) {
            return response()->json($notification);
        } else {
            return response()->json(['message' => 'Notification not found'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // อัปเดตข้อมูลการแจ้งเตือนตาม ID
        $request->validate([
            'StudentID' => 'sometimes|required|exists:students,StudentID',
            'Message' => 'sometimes|required|string',
            'SentDate' => 'sometimes|required|date',
        ]);

        $notification = Notification::find($id);
        if ($notification) {
            $notification->StudentID = $request->get('StudentID', $notification->StudentID);
            $notification->Message = $request->get('Message', $notification->Message);
            $notification->SentDate = $request->get('SentDate', $notification->SentDate);

            $notification->save();

            return response()->json($notification);
        } else {
            return response()->json(['message' => 'Notification not found'], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // ลบการแจ้งเตือนตาม ID
        $notification = Notification::find($id);
        if ($notification) {
            $notification->delete();
            return response()->json(['message' => 'Notification deleted']);
        } else {
            return response()->json(['message' => 'Notification not found'], 404);
        }
    }
}
