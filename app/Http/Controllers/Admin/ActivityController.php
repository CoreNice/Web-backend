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
            'image'       => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();
        $imagePath = null;

        // Handle image upload - save with original filename (no prefix, no randomization)
        // if ($request->hasFile('image')) {
        //     $file = $request->file('image');
        //     $originalName = $file->getClientOriginalName();
        //     $filename = $originalName; // Use original filename without randomization
        //     $path = $file->storeAs('activities', $filename, 'public');
        //     $imagePath = $originalName; // Store only filename
        // }

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
            'image'       => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();

        // Ensure image field uses submitted image string when provided
        // if ($request->hasFile('image')) {
        //     // Delete old image if exists
        //     if ($activity->image) {
        //         $oldImagePath = 'activities/' . $activity->image;
        //         if (Storage::disk('public')->exists($oldImagePath)) {
        //             Storage::disk('public')->delete($oldImagePath);
        //         }
        //     }
        //     $file = $request->file('image');
        //     $originalName = $file->getClientOriginalName();
        //     $filename = $originalName; // Use original filename
        //     $path = $file->storeAs('activities', $filename, 'public');
        //     $data['image'] = $originalName; // Store only filename
        // }

        if (isset($data['image'])) {
            $activity->image = $data['image'];
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
