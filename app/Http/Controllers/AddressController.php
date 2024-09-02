<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    // Display a listing of the addresses
    public function index()
    {
        $addresses = Address::all();
        return response()->json($addresses);
    }

    // Store a newly created address in the database
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'ApplicationID' => 'required|string|max:255',
            'AddressLine' => 'required|string|max:255',
            'Subdistrict' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'District' => 'required|string|max:255',
            'PostalCode' => 'required|string|max:10',
            'Type' => 'required|string|max:255',
        ]);

        $address = Address::create($validatedData);

        return response()->json($address, 201); // 201 Created
    }

    // Display the specified addresses
public function show($applicationID)
{
    // ค้นหาข้อมูล Address ทั้งหมดที่มี ApplicationID ตรงกับค่าที่ระบุ
    $addresses = Address::where('ApplicationID', $applicationID)->get();

    // ส่งผลลัพธ์กลับมาในรูปแบบ JSON
    return response()->json(['addresses' => $addresses]);
}



 // Update the specified addresses in the database by ApplicationID
public function update(Request $request, $applicationID)
{
    // Validate the incoming request data
    $validatedData = $request->validate([
        'addresses' => 'required|array',  // Ensure 'addresses' is an array
        'addresses.*.AddressLine' => 'required|string|max:255',
        'addresses.*.Subdistrict' => 'required|string|max:255',
        'addresses.*.province' => 'required|string|max:255',
        'addresses.*.District' => 'required|string|max:255',
        'addresses.*.PostalCode' => 'required|string|max:10',
        'addresses.*.Type' => 'required|string|max:255',
    ]);

    $addressesData = $validatedData['addresses'];

    // Find all addresses by ApplicationID
    $addresses = Address::where('ApplicationID', $applicationID)->get();

    // Loop through each address and update it with the corresponding data
    foreach ($addresses as $index => $address) {
        if (isset($addressesData[$index])) {
            $address->update($addressesData[$index]);
        }
    }

    // Return the updated addresses as a JSON response
    return response()->json($addresses);
}


    // Remove the specified address from the database
    public function destroy($id)
    {
        $address = Address::findOrFail($id);
        $address->delete();

        return response()->json(null, 204); // 204 No Content
    }
}
