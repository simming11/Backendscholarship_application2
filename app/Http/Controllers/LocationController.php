<?php

namespace App\Http\Controllers;

use App\Models\Province;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index()
    {
        $provinces = Province::with('districts.subdistricts')->get();
        return response()->json($provinces);
    }
}
