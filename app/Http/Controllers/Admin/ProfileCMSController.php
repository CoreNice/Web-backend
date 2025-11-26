<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProfileCMS;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use MongoDB\BSON\ObjectId;

class ProfileCMSController extends Controller
{
    public function index()
    {
        $divisions = ProfileCMS::orderBy('order')->get();
        return response()->json($divisions);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'description' => 'required|string',
            'longDescription' => 'required|string',
            'icon' => 'required|string',
            'color' => 'required|string',
            'image' => 'nullable|string',
            'order' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();

        if (!isset($data['order'])) {
            $data['order'] = ProfileCMS::max('order') + 1 ?? 0;
        }

        $division = ProfileCMS::create($data);

        return response()->json($division, 201);
    }

    public function show($id)
    {
        $division = ProfileCMS::find($id);
        if (!$division) {
            return response()->json(['message' => 'Not found'], 404);
        }
        return response()->json($division);
    }

    public function update(Request $request, $id)
    {
        $division = ProfileCMS::find($id);
        if (!$division) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:100',
            'description' => 'sometimes|required|string',
            'longDescription' => 'sometimes|required|string',
            'icon' => 'sometimes|required|string',
            'color' => 'sometimes|required|string',
            'image' => 'nullable|string',
            'order' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();
        $division->fill($data);
        $division->save();

        return response()->json($division);
    }

    public function destroy($id)
    {
        $division = ProfileCMS::find($id);
        if (!$division) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $division->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
