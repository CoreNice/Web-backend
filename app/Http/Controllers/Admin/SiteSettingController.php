<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Validator;

class SiteSettingController extends Controller
{
    public function index()
    {
        $settings = SiteSetting::all();
        // return as key => value
        $result = [];
        foreach ($settings as $s) {
            $result[$s->key] = $s->value;
        }
        return response()->json($result);
    }

    public function show($key)
    {
        $setting = SiteSetting::where('key', $key)->first();
        if (!$setting) return response()->json(['value' => null]);
        return response()->json(['value' => $setting->value]);
    }

    public function update(Request $request, $key)
    {
        $validator = Validator::make($request->all(), [
            'value' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        $value = $request->input('value');

        $setting = SiteSetting::firstOrNew(['key' => $key]);
        $setting->value = $value;
        $setting->save();

        return response()->json(['key' => $key, 'value' => $value]);
    }

    public function destroy($key)
    {
        $setting = SiteSetting::where('key', $key)->first();
        if ($setting) {
            $setting->delete();
        }
        return response()->json(['message' => 'Deleted']);
    }
}
