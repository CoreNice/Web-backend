<?php

namespace App\Http\Controllers;

use App\Models\ProfileCMS;
use Illuminate\Http\Request;

class ProfileCMSController extends Controller
{
    public function index()
    {
        $divisions = ProfileCMS::orderBy('order')->get();
        return response()->json($divisions);
    }

    public function show($id)
    {
        $division = ProfileCMS::find($id);
        if (!$division) {
            return response()->json(['message' => 'Not found'], 404);
        }
        return response()->json($division);
    }
}
