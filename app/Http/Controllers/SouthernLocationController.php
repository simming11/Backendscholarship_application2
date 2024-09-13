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
                'กระบี่', 'กรุงเทพมหานคร', 'กาญจนบุรี', 'กาฬสินธุ์', 'กำแพงเพชร',
                'ขอนแก่น', 'จันทบุรี', 'ฉะเชิงเทรา', 'ชลบุรี', 'ชัยนาท',
                'ชัยภูมิ', 'ชุมพร', 'เชียงราย', 'เชียงใหม่', 'ตรัง',
                'ตราด', 'ตาก', 'นครนายก', 'นครปฐม', 'นครพนม',
                'นครราชสีมา', 'นครศรีธรรมราช', 'นครสวรรค์', 'นนทบุรี', 'นราธิวาส',
                'น่าน', 'บึงกาฬ', 'บุรีรัมย์', 'ปทุมธานี', 'ประจวบคีรีขันธ์',
                'ปราจีนบุรี', 'ปัตตานี', 'พระนครศรีอยุธยา', 'พังงา', 'พัทลุง',
                'พิจิตร', 'พิษณุโลก', 'เพชรบุรี', 'เพชรบูรณ์', 'แพร่',
                'พะเยา', 'ภูเก็ต', 'มหาสารคาม', 'มุกดาหาร', 'แม่ฮ่องสอน',
                'ยโสธร', 'ยะลา', 'ร้อยเอ็ด', 'ระนอง', 'ระยอง',
                'ราชบุรี', 'ลพบุรี', 'ลำปาง', 'ลำพูน', 'เลย',
                'ศรีสะเกษ', 'สกลนคร', 'สงขลา', 'สตูล', 'สมุทรปราการ',
                'สมุทรสงคราม', 'สมุทรสาคร', 'สระแก้ว', 'สระบุรี', 'สิงห์บุรี',
                'สุโขทัย', 'สุพรรณบุรี', 'สุราษฎร์ธานี', 'สุรินทร์', 'หนองคาย',
                'หนองบัวลำภู', 'อ่างทอง', 'อุดรธานี', 'อุตรดิตถ์', 'อุทัยธานี',
                'อุบลราชธานี', 'อำนาจเจริญ'
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
