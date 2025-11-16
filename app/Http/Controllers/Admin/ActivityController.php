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
    // List all activities for admin
    public function index()
    {
        $activities = Activity::orderBy('created_at', 'desc')->get();
        return response()->json($activities);
    }

    // Store new activity (multipart/form-data support)
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'       => 'required|string|max:190',
            'description' => 'required|string',
            'date'        => 'required|string',
            'location'    => 'required|string|max:190',
            'status'      => 'required|in:upcoming,past',
            'image'       => 'nullable|file|image|max:5120' // max 5MB
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();

        // Handle image upload
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = 'activities/' . Str::random(20) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('', $filename, 'public');
            $data['image'] = $path;
        }

        $activity = Activity::create([
            'title' => $data['title'],
            'description' => $data['description'],
            'date' => $data['date'],
            'location' => $data['location'],
            'status' => $data['status'],
            'image' => $data['image'] ?? null,
        ]);

        return response()->json($activity, 201);
    }

    // Show single activity
    public function show($id)
    {
        $activity = Activity::find($id);
        if (!$activity) {
            return response()->json(['message' => 'Not found'], 404);
        }
        return response()->json($activity);
    }

    // Update activity (multipart/form-data)
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
            'image'       => 'nullable|file|image|max:5120'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();

        // Handle image replacement
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($activity->image && Storage::disk('public')->exists($activity->image)) {
                Storage::disk('public')->delete($activity->image);
            }
            $file = $request->file('image');
            $filename = 'activities/' . Str::random(20) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('', $filename, 'public');
            $data['image'] = $path;
        }

        $activity->fill($data);
        $activity->save();

        return response()->json($activity);
    }

    // Delete activity
    public function destroy($id)
    {
        $activity = Activity::find($id);
        if (!$activity) return response()->json(['message' => 'Not found'], 404);

        // Delete image file
        if ($activity->image && Storage::disk('public')->exists($activity->image)) {
            Storage::disk('public')->delete($activity->image);
        }

        $activity->delete();

        return response()->json(['message' => 'Deleted']);
    }
}

