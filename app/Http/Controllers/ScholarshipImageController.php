<?php

namespace App\Http\Controllers;

use App\Models\ScholarshipImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ScholarshipImageController extends Controller
{
    public function index()
    {
        // ดึงรายการภาพทั้งหมด
        $images = ScholarshipImage::all();
        return response()->json($images);
    }

    public function store(Request $request)
    {
        // ตรวจสอบข้อมูลที่ได้รับจากการส่งคำขอ
        $validatedData = $request->validate([
            'ScholarshipID' => 'required|integer|exists:scholarships,ScholarshipID',
            'ImagePath' => 'required|file|mimes:jpg,png,jpeg|max:2048',
            'Description' => 'nullable|string|max:255',
        ]);

        // อัปโหลดไฟล์ภาพ
        if ($request->hasFile('ImagePath')) {
            $file = $request->file('ImagePath');
            $originalFileName = $file->getClientOriginalName();
            $filePath = $file->storeAs('images', $originalFileName, 'public');
            $validatedData['ImagePath'] = $filePath; // เก็บเส้นทางไฟล์ในฐานข้อมูล
        }

        // สร้างรายการภาพใหม่ในฐานข้อมูล
        $image = ScholarshipImage::create($validatedData);

        return response()->json($image, 201); // 201 Created
    }

    public function show($id)
    {
        // ดึงภาพทั้งหมดที่เกี่ยวข้องกับ ScholarshipID ที่ระบุ
        $existingImages = ScholarshipImage::where('ScholarshipID', $id)->get();
    
        // สร้าง URL สำหรับแต่ละภาพ
        $existingImages->transform(function ($image) {
            $image->ImagePath = asset('storage/' . $image->ImagePath); // สร้าง URL ที่ถูกต้อง
            return $image;
        });
    
        return response()->json($existingImages);
    }
    
    public function update(Request $request, $scholarshipID)
    {
        // ตรวจสอบข้อมูลที่ได้รับ
        $validatedData = $request->validate([
            'ImagePath' => 'nullable|file|mimes:jpg,png,jpeg|max:2048',
            'Description' => 'nullable|string|max:255',
        ]);
    
        // ค้นหาไฟล์รูปภาพที่เกี่ยวข้องกับ ScholarshipID
        $existingImages = ScholarshipImage::where('ScholarshipID', $scholarshipID)->get();
    
        // ตรวจสอบว่ามีการอัปโหลดไฟล์ใหม่หรือไม่
        if ($request->hasFile('ImagePath')) {
            // ลบไฟล์เก่าจาก storage และลบรายการจากฐานข้อมูล
            foreach ($existingImages as $existingImage) {
                if (Storage::disk('public')->exists($existingImage->ImagePath)) {
                    Storage::disk('public')->delete($existingImage->ImagePath);
                }
                $existingImage->delete(); // ลบจากฐานข้อมูล
            }
    
            // อัปโหลดไฟล์ใหม่
            $file = $request->file('ImagePath');
            $originalFileName = $file->getClientOriginalName();
            $filePath = $file->storeAs('images', $originalFileName, 'public');
            $validatedData['ImagePath'] = $filePath; // อัปเดตเส้นทางไฟล์ในฐานข้อมูล
    
            // สร้างรายการภาพใหม่ในฐานข้อมูล
            $validatedData['ScholarshipID'] = $scholarshipID;
            $newImage = ScholarshipImage::create($validatedData);
    
            return response()->json($newImage, 201); // 201 Created
        }
    
        // ถ้าไม่มีไฟล์ใหม่ ให้เพียงแค่ทำการอัปเดตคำอธิบาย (ถ้ามี)
        if ($request->filled('Description')) {
            foreach ($existingImages as $existingImage) {
                $existingImage->Description = $validatedData['Description'];
                $existingImage->save();
            }
            return response()->json($existingImages, 200); // 200 OK
        }
    
        return response()->json(['message' => 'No new image uploaded or description provided.'], 400); // Bad Request
    }
    
    public function destroy($id)
    {
        $image = ScholarshipImage::findOrFail($id);

        // ลบไฟล์จากสตอเรจ
        if ($image->ImagePath) {
            Storage::disk('public')->delete($image->ImagePath);
        }

        // ลบรายการภาพจากฐานข้อมูล
        $image->delete();

        return response()->json(null, 204); // 204 No Content
    }
}
