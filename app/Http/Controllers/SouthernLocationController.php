<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\Province;
use App\Models\Subdistrict;
use Illuminate\Http\Request;

class SouthernLocationController extends Controller
{
    public function index(Request $request)
    {
        $query = Province::with('districts.subdistricts')
            ->whereIn('name', [
                'กระบี่', 'ชุมพร', 'ตรัง', 'นครศรีธรรมราช', 'นราธิวาส',
                'ปัตตานี', 'พังงา', 'พัทลุง', 'ภูเก็ต', 'ระนอง',
                'สงขลา', 'สตูล', 'สุราษฎร์ธานี', 'ยะลา'
            ]);

        // ค้นหาตามชื่อจังหวัด, อำเภอ, หรือตำบล
        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            
            $query->where('name', 'LIKE', "%{$searchTerm}%")
                ->orWhereHas('districts', function ($districtQuery) use ($searchTerm) {
                    $districtQuery->where('name', 'LIKE', "%{$searchTerm}%")
                        ->orWhereHas('subdistricts', function ($subdistrictQuery) use ($searchTerm) {
                            $subdistrictQuery->where('name', 'LIKE', "%{$searchTerm}%");
                        });
                });
        }

        $southernProvinces = $query->get();

        return response()->json($southernProvinces);
    }
    
    public function getDistricts($provinceName)
    {
        // ค้นหาจังหวัดจากชื่อ
        $province = Province::where('name', $provinceName)->first();
        
        if ($province) {
            // หากพบจังหวัดให้ค้นหาอำเภอในจังหวัดนั้น
            $districts = District::where('province_id', $province->id)->get(['id', 'name']);
            return response()->json($districts);
        } else {
            // หากไม่พบจังหวัด ให้ส่งกลับเป็นข้อมูลว่างเปล่า
            return response()->json([]);
        }
    }
    

    public function getSubdistricts($districtName)
    {
        // ค้นหาอำเภอจากชื่อ
        $district = District::where('name', $districtName)->first();
        
        if ($district) {
            // หากพบอำเภอให้ค้นหาตำบลในอำเภอนั้น
            $subdistricts = Subdistrict::where('district_id', $district->id)->get(['id', 'name']);
            return response()->json($subdistricts);
        } else {
            // หากไม่พบอำเภอ ให้ส่งกลับเป็นข้อมูลว่างเปล่า
            return response()->json([]);
        }
    }
    
}
