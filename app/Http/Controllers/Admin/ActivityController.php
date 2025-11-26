<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Activity;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class ActivityController extends Controller
{
    public function index()
    {
        $activities = Activity::orderBy('created_at', 'desc')->get();
        return response()->json($activities);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'       => 'required|string|max:190',
            'description' => 'required|string',
            'date'        => 'required|string',
            'location'    => 'required|string|max:190',
            'status'      => 'required|in:upcoming,past',
            'image'       => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();
        $imagePath = null;

        $activity = Activity::create([
            'title' => $data['title'],
            'description' => $data['description'],
            'date' => $data['date'],
            'location' => $data['location'],
            'status' => $data['status'],
            'image' => $data['image'] ?? $imagePath,
        ]);

        return response()->json($activity, 201);
    }

    public function show($id)
    {
        $activity = Activity::find($id);
        if (!$activity) {
            return response()->json(['message' => 'Not found'], 404);
        }
        return response()->json($activity);
    }

    public function update(Request $request, $id)
    {
        $activity = Activity::find($id);
        if (!$activity) return response()->json(['message' => 'Not found'], 404);

        $validator = Validator::make($request->all(), [
            'title'       => 'sometimes|required|string|max:190',
            'description' => 'sometimes|required|string',
            'date'        => 'sometimes|required|string',
            'location'    => 'sometimes|required|string|max:190',
            'status'      => 'sometimes|required|in:upcoming,past',
            'image'       => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();
        if (isset($data['image'])) {
            $activity->image = $data['image'];
        }

        $activity->fill($data);
        $activity->save();

        return response()->json($activity);
    }

    public function destroy($id)
    {
        $activity = Activity::find($id);
        if (!$activity) return response()->json(['message' => 'Not found'], 404);

        if ($activity->image) {
            $imagePath = 'activities/' . $activity->image;
            if (Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
        }

        $activity->delete();

        return response()->json(['message' => 'Deleted']);
    }
}
